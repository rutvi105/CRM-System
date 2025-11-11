<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to add role and package_type to users table
 * File Location: database/migrations/2024_11_03_000001_add_role_and_package_to_users_table.php
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add role column: Customer, Agent, Admin
            $table->enum('role', ['customer', 'agent', 'admin'])
                  ->default('customer')
                  ->after('password');
            
            // Add SLA package type: basic, gold, ultimate
            $table->enum('package_type', ['basic', 'gold', 'ultimate'])
                  ->nullable()
                  ->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'package_type']);
        });
    }
};