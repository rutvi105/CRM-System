<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TicketHistory Model - Tracks all changes to tickets
 * File Location: app/Models/TicketHistory.php
 */
class TicketHistory extends Model
{
    use HasFactory;

    protected $table = 'ticket_history';
    
    public $timestamps = false; // Only created_at, no updated_at
    
    protected $fillable = [
        'ticket_id',
        'changed_by',
        'action',
        'old_value',
        'new_value',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Ticket this history belongs to
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // User who made the change
    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    // Get formatted action name
    public function getFormattedAction(): string
    {
        return str_replace('_', ' ', ucfirst($this->action));
    }
}