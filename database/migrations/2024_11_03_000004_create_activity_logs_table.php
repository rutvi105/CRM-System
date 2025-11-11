<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create activity_logs table for user activity tracking
 * File Location: database/migrations/2024_11_03_000004_create_activity_logs_table.php
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            
            // User who performed the action (nullable for guest actions)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('cascade');
            
            // Action performed
            $table->string('action'); // e.g., "login", "logout", "created_ticket", "updated_ticket"
            
            // IP address tracking
            $table->string('ip_address', 45)->nullable(); // IPv6 compatible
            
            // User agent (browser/device info)
            $table->string('user_agent')->nullable();
            
            // Additional metadata in JSON format
            $table->json('details')->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            
            // Indexes
            $table->index('user_id');
            $table->index('action');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};