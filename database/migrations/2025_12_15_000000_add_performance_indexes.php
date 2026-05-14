<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for frequently queried columns (catch duplicate key errors)
        try {
            Schema::table('products', function (Blueprint $table) {
                $table->index('stock'); // Filter by stock > 0
            });
        } catch (\Exception $e) {
            // Index may already exist, that's fine
        }

        try {
            Schema::table('products', function (Blueprint $table) {
                $table->index('category_id'); // Filter by category
            });
        } catch (\Exception $e) {
            // Index may already exist, that's fine
        }

        try {
            Schema::table('products', function (Blueprint $table) {
                $table->index('created_at'); // Sort by created_at
            });
        } catch (\Exception $e) {
            // Index may already exist, that's fine
        }

        try {
            Schema::table('products', function (Blueprint $table) {
                $table->index('slug'); // Lookup by slug
            });
        } catch (\Exception $e) {
            // Index may already exist, that's fine
        }

        try {
            Schema::table('categories', function (Blueprint $table) {
                $table->index('slug'); // Lookup by slug
            });
        } catch (\Exception $e) {
            // Index may already exist, that's fine
        }

        try {
            if (Schema::hasColumn('categories', 'seller_id')) {
                Schema::table('categories', function (Blueprint $table) {
                    $table->index('seller_id'); // Filter by seller_id
                });
            }
        } catch (\Exception $e) {
            // Index may already exist, that's fine
        }

        try {
            Schema::table('product_descriptions', function (Blueprint $table) {
                $table->index('product_id'); // Relationship query
            });
        } catch (\Exception $e) {
            // Index may already exist, that's fine
        }

        try {
            Schema::table('product_media', function (Blueprint $table) {
                $table->index('product_id'); // Relationship query
            });
        } catch (\Exception $e) {
            // Index may already exist, that's fine
        }
    }

    private function indexExists($table, $indexName)
    {
        $sm = \Illuminate\Support\Facades\DB::connection()->getDoctrineSchemaManager();
        $indexes = $sm->listTableIndexes($table);
        return isset($indexes[strtolower($indexName)]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['stock']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['slug']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['seller_id']);
        });

        Schema::table('product_descriptions', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });

        Schema::table('product_media', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
        });
    }
};
