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
        // Add indexes for frequently queried columns
        Schema::table('products', function (Blueprint $table) {
            $table->index('stock'); // Filter by stock > 0
            $table->index('category_id'); // Filter by category
            $table->index('created_at'); // Sort by created_at
            $table->index('slug'); // Lookup by slug
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('slug'); // Lookup by slug
            $table->index('seller_id'); // Filter by seller_id
        });

        Schema::table('product_descriptions', function (Blueprint $table) {
            $table->index('product_id'); // Relationship query
        });

        Schema::table('product_media', function (Blueprint $table) {
            $table->index('product_id'); // Relationship query
        });
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
