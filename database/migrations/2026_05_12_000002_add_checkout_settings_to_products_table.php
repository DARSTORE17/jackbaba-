<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('vat_enabled')->default(true)->after('is_advertised');
            $table->decimal('vat_rate', 5, 2)->default(18)->after('vat_enabled');
            $table->enum('delivery_payment', ['free', 'customer'])->default('customer')->after('vat_rate');
            $table->decimal('delivery_fee', 10, 2)->default(5000)->after('delivery_payment');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'vat_enabled',
                'vat_rate',
                'delivery_payment',
                'delivery_fee',
            ]);
        });
    }
};
