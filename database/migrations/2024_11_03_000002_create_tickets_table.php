<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create tickets table
 * File Location: database/migrations/2024_11_03_000002_create_tickets_table.php
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            
            // Foreign key to users (ticket creator)
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');
            
            // Ticket details
            $table->string('title');
            $table->text('description');
            
            // Status tracking
            $table->enum('status', ['open', 'in_progress', 'pending', 'resolved', 'closed'])
                  ->default('open');
            
            // Priority levels
            $table->enum('priority', ['low', 'medium', 'high'])
                  ->default('medium');
            
            // Assigned agent (nullable - might not be assigned yet)
            $table->foreignId('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            
            // SLA deadline based on package
            $table->timestamp('sla_due_at')->nullable();
            
            // Additional tracking
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->text('pending_reason')->nullable(); // Why ticket is pending
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('status');
            $table->index('priority');
            $table->index('assigned_to');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};