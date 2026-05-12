<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('seller_vat_enabled')->default(true)->after('role');
            $table->decimal('seller_vat_rate', 5, 2)->default(18)->after('seller_vat_enabled');
            $table->enum('seller_delivery_payment', ['free', 'customer'])->default('customer')->after('seller_vat_rate');
            $table->decimal('seller_delivery_fee', 10, 2)->default(5000)->after('seller_delivery_payment');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'seller_vat_enabled',
                'seller_vat_rate',
                'seller_delivery_payment',
                'seller_delivery_fee',
            ]);
        });
    }
};
