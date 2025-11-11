<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Dashboard Controller - Handles dashboard views for different roles
 * File Location: app/Http/Controllers/DashboardController.php
 */
class DashboardController extends Controller
{
    /**
     * Display dashboard based on user role
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isAgent()) {
            return $this->agentDashboard();
        } else {
            return $this->customerDashboard();
        }
    }

    /**
     * Admin Dashboard - Overview of entire system
     */
    private function adminDashboard()
    {
        $stats = [
            'total_tickets' => Ticket::count(),
            'open_tickets' => Ticket::where('status', 'open')->count(),
            'in_progress_tickets' => Ticket::where('status', 'in_progress')->count(),
            'resolved_tickets' => Ticket::where('status', 'resolved')->count(),
            'overdue_tickets' => Ticket::overdue()->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_agents' => User::where('role', 'agent')->count(),
        ];

        // Recent tickets
        $recentTickets = Ticket::with(['user', 'assignedAgent'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Tickets by priority
        $ticketsByPriority = Ticket::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->get()
            ->pluck('count', 'priority');

        // Tickets by status
        $ticketsByStatus = Ticket::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Agent performance
        $agentPerformance = User::where('role', 'agent')
            ->withCount(['assignedTickets as total_tickets'])
            ->withCount(['assignedTickets as resolved_tickets' => function($query) {
                $query->where('status', 'resolved');
            }])
            ->get();

        return view('dashboard.admin', compact(
            'stats',
            'recentTickets',
            'ticketsByPriority',
            'ticketsByStatus',
            'agentPerformance'
        ));
    }

    /**
     * Agent Dashboard - Assigned tickets overview
     */
    private function agentDashboard()
    {
        $agent = auth()->user();

        $stats = [
            'assigned_tickets' => $agent->assignedTickets()->count(),
            'open_tickets' => $agent->assignedTickets()->where('status', 'open')->count(),
            'in_progress_tickets' => $agent->assignedTickets()->where('status', 'in_progress')->count(),
            'resolved_today' => $agent->assignedTickets()
                ->where('status', 'resolved')
                ->whereDate('resolved_at', today())
                ->count(),
        ];

        // My assigned tickets
        $myTickets = $agent->assignedTickets()
            ->with('user')
            ->whereNotIn('status', ['closed'])
            ->orderByRaw("FIELD(status, 'open', 'in_progress', 'pending', 'resolved')")
            ->orderBy('priority', 'desc')
            ->get();

        // Overdue tickets
        $overdueTickets = $agent->assignedTickets()
            ->overdue()
            ->with('user')
            ->get();

        return view('dashboard.agent', compact('stats', 'myTickets', 'overdueTickets'));
    }

    /**
     * Customer Dashboard - My tickets
     */
    private function customerDashboard()
    {
        $customer = auth()->user();

        $stats = [
            'total_tickets' => $customer->tickets()->count(),
            'open_tickets' => $customer->tickets()->where('status', 'open')->count(),
            'in_progress_tickets' => $customer->tickets()->where('status', 'in_progress')->count(),
            'resolved_tickets' => $customer->tickets()->where('status', 'resolved')->count(),
        ];

        // My tickets
        $myTickets = $customer->tickets()
            ->with('assignedAgent')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.customer', compact('stats', 'myTickets'));
    }
}