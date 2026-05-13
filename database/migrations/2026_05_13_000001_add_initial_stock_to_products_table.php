<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'initial_stock')) {
                $table->integer('initial_stock')->default(0)->after('stock');
            }
        });

        DB::statement('
            UPDATE products
            LEFT JOIN (
                SELECT order_items.product_id, COALESCE(SUM(order_items.quantity), 0) AS sold_quantity
                FROM order_items
                INNER JOIN orders ON orders.id = order_items.order_id
                WHERE orders.status != "cancelled"
                GROUP BY order_items.product_id
            ) sales ON sales.product_id = products.id
            SET products.initial_stock = products.stock + COALESCE(sales.sold_quantity, 0)
        ');
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'initial_stock')) {
                $table->dropColumn('initial_stock');
            }
        });
    }
};
