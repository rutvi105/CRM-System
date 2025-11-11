<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User Model - Enhanced with CRM features
 * File Location: app/Models/User.php (REPLACE existing file)
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'package_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==================== RELATIONSHIPS ====================
    
    // Tickets created by this user
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    // Tickets assigned to this user (for agents)
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    // Activity logs for this user
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // ==================== ROLE CHECKS ====================
    
    // Check if user is admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Check if user is agent
    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }

    // Check if user is customer
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    // ==================== SLA HELPERS ====================
    
    // Get SLA hours based on package type
    public function getSlaHours(): int
    {
        return match($this->package_type) {
            'gold' => 24,      // 24 hours (1 day)
            'ultimate' => 72,  // 72 hours (3 days)
            'basic' => 120,    // 120 hours (5 days)
            default => 120,
        };
    }

    // Get package display name
    public function getPackageDisplayName(): string
    {
        return match($this->package_type) {
            'gold' => 'Gold (24 hours)',
            'ultimate' => 'Ultimate (3 days)',
            'basic' => 'Basic (5 days)',
            default => 'No Package',
        };
    }
}