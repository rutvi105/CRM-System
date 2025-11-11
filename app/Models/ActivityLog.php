<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ActivityLog Model - Tracks user activities in the system
 * File Location: app/Models/ActivityLog.php
 */
class ActivityLog extends Model
{
    use HasFactory;

    public $timestamps = false; // Only created_at
    
    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
        'user_agent',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
        'created_at' => 'datetime',
    ];

    // User who performed the action
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Log a new activity - Easy method to log actions
    public static function log(string $action, array $details = []): void
    {
        self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details' => $details,
        ]);
    }
}