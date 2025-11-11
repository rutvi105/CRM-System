<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketStatusChanged;
use App\Mail\TicketAssigned;

/**
 * Ticket Controller - Main CRUD operations for tickets
 * File Location: app/Http/Controllers/TicketController.php
 */
class TicketController extends Controller
{
    /**
     * Display listing of tickets
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Ticket::with(['user', 'assignedAgent']);

        // Role-based filtering
        if ($user->isCustomer()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isAgent()) {
            $query->where('assigned_to', $user->id);
        }

        // Apply filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority != '') {
            $query->where('priority', $request->priority);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show form to create new ticket
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store new ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket = new Ticket($validated);
        $ticket->user_id = auth()->id();
        $ticket->status = 'open';
        
        // Calculate SLA based on user's package
        $ticket->calculateSLA();
        $ticket->save();

        // Log ticket creation
        $ticket->logHistory('created', null, 'Ticket created', auth()->id());
        ActivityLog::log('created_ticket', ['ticket_id' => $ticket->id, 'title' => $ticket->title]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket created successfully! Ticket ID: #' . $ticket->id);
    }

    /**
     * Show specific ticket details
     */
    public function show(Ticket $ticket)
    {
        // Authorization check
        $user = auth()->user();
        if ($user->isCustomer() && $ticket->user_id != $user->id) {
            abort(403, 'Unauthorized access to ticket.');
        }
        if ($user->isAgent() && $ticket->assigned_to != $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized access to ticket.');
        }

        $ticket->load(['user', 'assignedAgent', 'history.changer']);
        
        // Get available agents for assignment (admin/current agent only)
        $agents = User::where('role', 'agent')->get();

        return view('tickets.show', compact('ticket', 'agents'));
    }

    /**
     * Update ticket status
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,pending,resolved,closed',
            'pending_reason' => 'required_if:status,pending|nullable|string',
        ]);

        $oldStatus = $ticket->status;
        $ticket->status = $validated['status'];
        
        // Update pending reason if status is pending
        if ($validated['status'] == 'pending') {
            $ticket->pending_reason = $validated['pending_reason'];
        } else {
            $ticket->pending_reason = null;
        }

        // Set resolved/closed timestamps
        if ($validated['status'] == 'resolved' && !$ticket->resolved_at) {
            $ticket->resolved_at = now();
        }
        if ($validated['status'] == 'closed' && !$ticket->closed_at) {
            $ticket->closed_at = now();
        }

        $ticket->save();

        // Log the change
        $ticket->logHistory('status_changed', $oldStatus, $validated['status']);
        // Send email notification to customer
        try {
            Mail::to($ticket->user->email)->send(new TicketStatusChanged($ticket, $oldStatus, $validated['status']));
        } catch (\Exception $e) {
            // Log error but don't stop execution
            \Log::error('Failed to send status change email: ' . $e->getMessage());
        }
        ActivityLog::log('updated_ticket_status', [
            'ticket_id' => $ticket->id,
            'old_status' => $oldStatus,
            'new_status' => $validated['status']
        ]);

        return back()->with('success', 'Ticket status updated successfully!');
    }

    /**
     * Assign ticket to agent
     */
    public function assign(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $oldAgent = $ticket->assigned_to;
        $ticket->assigned_to = $validated['assigned_to'];
        $ticket->save();

        // Log assignment
        $oldAgentName = $oldAgent ? User::find($oldAgent)->name : 'Unassigned';
        $newAgentName = User::find($validated['assigned_to'])->name;
        
        $ticket->logHistory('assigned', $oldAgentName, $newAgentName);
        ActivityLog::log('assigned_ticket', [
            'ticket_id' => $ticket->id,
            'assigned_to' => $newAgentName
        ]);
        // Send email notification to assigned agent
        try {
            $agent = User::find($validated['assigned_to']);
            if ($agent) {
                Mail::to($agent->email)->send(new TicketAssigned($ticket));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send assignment email: ' . $e->getMessage());
        }

        return back()->with('success', 'Ticket assigned successfully!');
    }

    /**
     * Update ticket priority
     */
    public function updatePriority(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'priority' => 'required|in:low,medium,high',
        ]);

        $oldPriority = $ticket->priority;
        $ticket->priority = $validated['priority'];
        $ticket->save();

        $ticket->logHistory('priority_changed', $oldPriority, $validated['priority']);

        return back()->with('success', 'Ticket priority updated!');
    }

    /**
     * Delete ticket (admin only)
     */
    public function destroy(Ticket $ticket)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only admins can delete tickets.');
        }

        ActivityLog::log('deleted_ticket', ['ticket_id' => $ticket->id, 'title' => $ticket->title]);
        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket deleted successfully!');
    }
}