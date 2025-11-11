<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create ticket_history table for audit trail
 * File Location: database/migrations/2024_11_03_000003_create_ticket_history_table.php
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_history', function (Blueprint $table) {
            $table->id();
            
            // Link to ticket
            $table->foreignId('ticket_id')
                  ->constrained()
                  ->onDelete('cascade');
            
            // Who made the change
            $table->foreignId('changed_by')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            // What action was performed
            $table->string('action'); // e.g., "status_changed", "reassigned", "created"
            
            // Old and new values for tracking changes
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            
            // Index for faster queries
            $table->index('ticket_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_history');
    }
};