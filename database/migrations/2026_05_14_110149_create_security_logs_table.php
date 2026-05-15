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
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_type'); // login_attempt, sql_injection, xss_attempt, brute_force, etc.
            $table->string('severity')->default('medium'); // low, medium, high, critical
            $table->text('message');
            $table->json('context')->nullable(); // Additional data like IP, user_agent, etc.
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('username')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable();
            $table->json('request_data')->nullable();
            $table->boolean('alert_sent')->default(false);
            $table->timestamp('alert_sent_at')->nullable();
            $table->timestamps();

            $table->index(['event_type', 'severity']);
            $table->index(['ip_address']);
            $table->index(['user_id']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_logs');
    }
};
