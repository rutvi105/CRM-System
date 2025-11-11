<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Facades\Hash;

/**
 * Database Seeder - Creates demo data
 * File Location: database/seeders/DatabaseSeeder.php
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@crm.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'package_type' => null,
        ]);

        // Create Agents
        $agent1 = User::create([
            'name' => 'John Agent',
            'email' => 'agent@crm.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'package_type' => null,
        ]);

        $agent2 = User::create([
            'name' => 'Sarah Agent',
            'email' => 'agent2@crm.com',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'package_type' => null,
        ]);

        // Create Customers with different packages
        $customer1 = User::create([
            'name' => 'Alice Customer',
            'email' => 'customer@crm.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'package_type' => 'gold',
        ]);

        $customer2 = User::create([
            'name' => 'Bob Customer',
            'email' => 'customer2@crm.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'package_type' => 'ultimate',
        ]);

        $customer3 = User::create([
            'name' => 'Charlie Customer',
            'email' => 'customer3@crm.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'package_type' => 'basic',
        ]);

        // Create Sample Tickets
        $tickets = [
            [
                'user_id' => $customer1->id,
                'title' => 'Website not loading properly',
                'description' => 'When I try to access the dashboard, the page shows a white screen.',
                'priority' => 'high',
                'status' => 'open',
                'assigned_to' => $agent1->id,
            ],
            [
                'user_id' => $customer1->id,
                'title' => 'Email notifications not working',
                'description' => 'I am not receiving any email notifications for new updates.',
                'priority' => 'medium',
                'status' => 'in_progress',
                'assigned_to' => $agent1->id,
            ],
            [
                'user_id' => $customer2->id,
                'title' => 'Cannot upload files',
                'description' => 'Getting an error when trying to upload PDF files larger than 5MB.',
                'priority' => 'high',
                'status' => 'open',
                'assigned_to' => $agent2->id,
            ],
            [
                'user_id' => $customer2->id,
                'title' => 'Password reset not working',
                'description' => 'The password reset link in email is expired or invalid.',
                'priority' => 'medium',
                'status' => 'resolved',
                'assigned_to' => $agent2->id,
                'resolved_at' => now()->subDays(1),
            ],
            [
                'user_id' => $customer3->id,
                'title' => 'Mobile app crashes on startup',
                'description' => 'The mobile application crashes immediately after opening.',
                'priority' => 'high',
                'status' => 'pending',
                'pending_reason' => 'Waiting for customer to provide device logs',
                'assigned_to' => $agent1->id,
            ],
        ];

        foreach ($tickets as $ticketData) {
            $ticket = Ticket::create($ticketData);
            $ticket->calculateSLA();
            $ticket->save();
            
            // Add initial history
            $ticket->logHistory('created', null, 'Ticket created', $ticket->user_id);
            
            if ($ticket->assigned_to) {
                $ticket->logHistory('assigned', null, $ticket->assignedAgent->name, $admin->id);
            }
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('📧 Login Credentials:');
        $this->command->info('Admin: admin@crm.com / password');
        $this->command->info('Agent: agent@crm.com / password');
        $this->command->info('Customer: customer@crm.com / password');
    }
}