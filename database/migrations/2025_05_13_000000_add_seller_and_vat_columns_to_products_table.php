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
        Schema::table('products', function (Blueprint $table) {
            // Add seller_id (foreign key to users table)
            if (!Schema::hasColumn('products', 'seller_id')) {
                $table->foreignId('seller_id')->nullable()->constrained('users')->cascadeOnDelete();
            }

            // Add initial_stock
            if (!Schema::hasColumn('products', 'initial_stock')) {
                $table->integer('initial_stock')->default(0);
            }

            // Add VAT columns
            if (!Schema::hasColumn('products', 'vat_enabled')) {
                $table->boolean('vat_enabled')->default(false);
            }

            if (!Schema::hasColumn('products', 'vat_rate')) {
                $table->decimal('vat_rate', 5, 2)->default(0);
            }

            // Add delivery columns
            if (!Schema::hasColumn('products', 'delivery_payment')) {
                $table->enum('delivery_payment', ['free', 'customer'])->default('free');
            }

            if (!Schema::hasColumn('products', 'delivery_fee')) {
                $table->decimal('delivery_fee', 10, 2)->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = ['seller_id', 'initial_stock', 'vat_enabled', 'vat_rate', 'delivery_payment', 'delivery_fee'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
