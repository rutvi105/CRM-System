<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Ticket Model - Represents customer support tickets
 * File Location: app/Models/Ticket.php
 */
class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'priority',
        'assigned_to',
        'sla_due_at',
        'resolved_at',
        'closed_at',
        'pending_reason',
    ];

    protected $casts = [
        'sla_due_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    // Customer who created the ticket
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Agent assigned to the ticket
    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Ticket history/audit trail
    public function history()
    {
        return $this->hasMany(TicketHistory::class)->orderBy('created_at', 'desc');
    }

    // Scope for open tickets
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    // Scope for overdue tickets (past SLA)
    public function scopeOverdue($query)
    {
        return $query->where('sla_due_at', '<', now())
                     ->whereNotIn('status', ['resolved', 'closed']);
    }

    // Check if ticket is overdue
    public function isOverdue(): bool
    {
        if (!$this->sla_due_at || in_array($this->status, ['resolved', 'closed'])) {
            return false;
        }
        return $this->sla_due_at->isPast();
    }

    // Get time remaining until SLA deadline
    public function getTimeRemaining(): string
    {
        if (!$this->sla_due_at) {
            return 'No SLA';
        }

        if ($this->isOverdue()) {
            return 'Overdue by ' . $this->sla_due_at->diffForHumans(null, true);
        }

        return $this->sla_due_at->diffForHumans();
    }

    // Get status badge color
    public function getStatusColor(): string
    {
        return match($this->status) {
            'open' => 'primary',
            'in_progress' => 'info',
            'pending' => 'warning',
            'resolved' => 'success',
            'closed' => 'secondary',
            default => 'secondary',
        };
    }

    // Get priority badge color
    public function getPriorityColor(): string
    {
        return match($this->priority) {
            'high' => 'danger',
            'medium' => 'warning',
            'low' => 'success',
            default => 'secondary',
        };
    }

    // Calculate and set SLA due date based on user's package
    public function calculateSLA(): void
    {
        if ($this->user && $this->user->package_type) {
            $hours = $this->user->getSlaHours();
            $this->sla_due_at = now()->addHours($hours);
        }
    }

    // Log a change to ticket history
    public function logHistory(string $action, $oldValue = null, $newValue = null, $changedBy = null): void
    {
        $this->history()->create([
            'changed_by' => $changedBy ?? auth()->id(),
            'action' => $action,
            'old_value' => $oldValue,
            'new_value' => $newValue,
        ]);
    }
}