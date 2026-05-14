<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('thumbnail')->nullable()->change();
        });

        Schema::table('product_media', function (Blueprint $table) {
            $table->text('file_path')->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->text('image')->nullable()->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->text('passport')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('thumbnail')->nullable()->change();
        });

        Schema::table('product_media', function (Blueprint $table) {
            $table->string('file_path')->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('image')->nullable()->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('passport')->nullable()->change();
        });
    }
};
