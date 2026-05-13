<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
