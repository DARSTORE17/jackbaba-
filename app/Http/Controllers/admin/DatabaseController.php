<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseController extends AdminController
{
    public function index()
    {
        $connection = config('database.default');
        $database = config("database.connections.$connection.database");
        $driver = config("database.connections.$connection.driver");

        $tables = $this->getTableStats($driver, $database);
        $totalRows = collect($tables)->sum('rows');
        $totalSize = collect($tables)->sum('size_bytes');

        return view('admin.database', compact(
            'connection',
            'database',
            'driver',
            'tables',
            'totalRows',
            'totalSize'
        ));
    }

    public function backup(Request $request)
    {
        $connection = config('database.default');
        $database = config("database.connections.$connection.database");
        $driver = config("database.connections.$connection.driver");

        if ($driver !== 'mysql') {
            return back()->with('error', 'Database backup is currently available for MySQL connections only.');
        }

        $fileName = sprintf(
            '%s-backup-%s.sql',
            str_replace([' ', '/', '\\'], '-', $database ?: 'database'),
            now()->format('Y-m-d-His')
        );

        return response()->streamDownload(function () use ($database) {
            $this->writeMysqlDump($database);
        }, $fileName, [
            'Content-Type' => 'application/sql',
        ]);
    }

    public function editTable(Request $request, string $table)
    {
        $driver = config("database.connections." . config('database.default') . ".driver");

        if ($driver !== 'mysql') {
            return back()->with('error', 'Table editing is currently available for MySQL connections only.');
        }

        // Get table structure
        $columns = collect(DB::select("DESCRIBE `$table`"))->map(function ($column) {
            return [
                'field' => $column->Field,
                'type' => $column->Type,
                'null' => $column->Null === 'YES',
                'key' => $column->Key,
                'default' => $column->Default,
                'extra' => $column->Extra,
            ];
        });

        // Get sample data (first 10 rows)
        $sampleData = DB::table($table)->limit(10)->get();

        return view('admin.database-edit', compact('table', 'columns', 'sampleData'));
    }

    public function updateTable(Request $request, string $table)
    {
        $driver = config("database.connections." . config('database.default') . ".driver");

        if ($driver !== 'mysql') {
            return back()->with('error', 'Table editing is currently available for MySQL connections only.');
        }

        $request->validate([
            'action' => 'required|in:insert,update,delete',
            'data' => 'required|array',
        ]);

        try {
            if ($request->action === 'insert') {
                DB::table($table)->insert($request->data);
                $message = 'Record inserted successfully.';
            } elseif ($request->action === 'update') {
                $request->validate([
                    'where_column' => 'required|string',
                    'where_value' => 'required',
                ]);

                DB::table($table)
                    ->where($request->where_column, $request->where_value)
                    ->update($request->data);
                $message = 'Record updated successfully.';
            } elseif ($request->action === 'delete') {
                $request->validate([
                    'where_column' => 'required|string',
                    'where_value' => 'required',
                ]);

                DB::table($table)
                    ->where($request->where_column, $request->where_value)
                    ->delete();
                $message = 'Record deleted successfully.';
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Operation failed: ' . $e->getMessage());
        }
    }

    public function format(Request $request)
    {
        $driver = config("database.connections." . config('database.default') . ".driver");
        $database = config("database.connections." . config('database.default') . ".database");

        if ($driver !== 'mysql') {
            return back()->with('error', 'Database formatting is currently available for MySQL connections only.');
        }

        $request->validate([
            'confirm' => 'required|in:FORMAT_DATABASE',
        ]);

        try {
            // Get all tables
            $tables = collect(DB::select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"'))
                ->map(function ($row) {
                    $values = array_values((array) $row);
                    return $values[0] ?? null;
                })
                ->filter()
                ->values();

            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Truncate all tables
            foreach ($tables as $table) {
                DB::statement("TRUNCATE TABLE `$table`");
            }

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return back()->with('success', 'Database formatted successfully. All data has been cleared.');
        } catch (\Exception $e) {
            return back()->with('error', 'Database formatting failed: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $driver = config("database.connections." . config('database.default') . ".driver");

        if ($driver !== 'mysql') {
            return back()->with('error', 'Database import is currently available for MySQL connections only.');
        }

        $request->validate([
            'backup_file' => 'required|file|mimes:sql,txt',
        ]);

        try {
            $file = $request->file('backup_file');
            $sql = file_get_contents($file->getRealPath());

            // Split SQL into individual statements
            $statements = array_filter(array_map('trim', explode(';', $sql)));

            DB::beginTransaction();

            foreach ($statements as $statement) {
                if (!empty($statement) && !str_starts_with(strtoupper($statement), 'SET')) {
                    DB::statement($statement);
                }
            }

            DB::commit();

            return back()->with('success', 'Database import completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Database import failed: ' . $e->getMessage());
        }
    }

    private function getTableStats(string $driver, ?string $database): array
    {
        if ($driver === 'mysql') {
            return collect(DB::select('SHOW TABLE STATUS'))->map(function ($table) {
                return [
                    'name' => $table->Name,
                    'engine' => $table->Engine ?? 'n/a',
                    'rows' => (int) ($table->Rows ?? 0),
                    'size_bytes' => (int) (($table->Data_length ?? 0) + ($table->Index_length ?? 0)),
                    'collation' => $table->Collation ?? 'n/a',
                    'updated_at' => $table->Update_time ?? null,
                ];
            })->sortBy('name')->values()->all();
        }

        if ($driver === 'sqlite') {
            return collect(DB::select("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%'"))
                ->map(function ($table) {
                    $name = $table->name;

                    return [
                        'name' => $name,
                        'engine' => 'sqlite',
                        'rows' => (int) DB::table($name)->count(),
                        'size_bytes' => 0,
                        'collation' => 'n/a',
                        'updated_at' => null,
                    ];
                })->sortBy('name')->values()->all();
        }

        return [];
    }

    private function writeMysqlDump(?string $database): void
    {
        $pdo = DB::connection()->getPdo();
        $tables = collect(DB::select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"'))
            ->map(function ($row) {
                $values = array_values((array) $row);
                return $values[0] ?? null;
            })
            ->filter()
            ->values();

        echo "-- Bravus Market database backup\n";
        echo "-- Database: " . ($database ?: 'unknown') . "\n";
        echo "-- Generated: " . now()->toDateTimeString() . "\n\n";
        echo "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $quotedTable = $this->quoteIdentifier($table);

            echo "--\n-- Table structure for $table\n--\n\n";
            echo "DROP TABLE IF EXISTS $quotedTable;\n";

            $createRow = DB::selectOne("SHOW CREATE TABLE $quotedTable");
            $createSql = array_values((array) $createRow)[1] ?? null;
            if ($createSql) {
                echo $createSql . ";\n\n";
            }

            echo "--\n-- Data for $table\n--\n\n";

            DB::table($table)->orderByRaw('1')->chunk(500, function ($rows) use ($pdo, $table, $quotedTable) {
                if ($rows->isEmpty()) {
                    return;
                }

                $columns = array_keys((array) $rows->first());
                $columnSql = collect($columns)->map(fn ($column) => $this->quoteIdentifier($column))->implode(', ');

                foreach ($rows as $row) {
                    $values = collect((array) $row)->map(function ($value) use ($pdo) {
                        if ($value === null) {
                            return 'NULL';
                        }

                        if (is_bool($value)) {
                            return $value ? '1' : '0';
                        }

                        return $pdo->quote((string) $value);
                    })->implode(', ');

                    echo "INSERT INTO $quotedTable ($columnSql) VALUES ($values);\n";
                }

                echo "\n";
            });
        }

        echo "SET FOREIGN_KEY_CHECKS=1;\n";
    }

    private function quoteIdentifier(string $identifier): string
    {
        return '`' . str_replace('`', '``', $identifier) . '`';
    }
}
