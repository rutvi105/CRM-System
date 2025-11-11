<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Report Controller - Analytics and reporting
 * File Location: app/Http/Controllers/ReportController.php
 */
class ReportController extends Controller
{
    /**
     * Display reports dashboard
     */
    public function index()
    {
        $dateRange = request('range', '30'); // Default 30 days
        $startDate = now()->subDays($dateRange);

        // Overall statistics
        $stats = [
            'total_tickets' => Ticket::where('created_at', '>=', $startDate)->count(),
            'resolved_tickets' => Ticket::where('created_at', '>=', $startDate)
                ->where('status', 'resolved')->count(),
            'average_resolution_time' => $this->getAverageResolutionTime($startDate),
            'sla_compliance_rate' => $this->getSLAComplianceRate($startDate),
        ];

        // Tickets created over time
        $ticketsTrend = Ticket::where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Tickets by status
        $ticketsByStatus = Ticket::where('created_at', '>=', $startDate)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Tickets by priority
        $ticketsByPriority = Ticket::where('created_at', '>=', $startDate)
            ->select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->get();

        // Top performing agents
        $topAgents = User::where('role', 'agent')
            ->withCount(['assignedTickets as resolved_count' => function($query) use ($startDate) {
                $query->where('status', 'resolved')
                      ->where('resolved_at', '>=', $startDate);
            }])
            ->orderBy('resolved_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.reports.index', compact(
            'stats',
            'ticketsTrend',
            'ticketsByStatus',
            'ticketsByPriority',
            'topAgents',
            'dateRange'
        ));
    }

    /**
     * SLA Compliance Report
     */
    public function slaCompliance()
    {
        $tickets = Ticket::with(['user', 'assignedAgent'])
            ->whereNotNull('sla_due_at')
            ->whereIn('status', ['resolved', 'closed'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $allTickets = Ticket::whereNotNull('sla_due_at')
            ->whereIn('status', ['resolved', 'closed'])
            ->get();

        $complianceStats = [
            'total' => $allTickets->count(),
            'met_sla' => $allTickets->filter(function($ticket) {
                return $ticket->resolved_at && $ticket->resolved_at <= $ticket->sla_due_at;
            })->count(),
            'breached_sla' => $allTickets->filter(function($ticket) {
                return $ticket->resolved_at && $ticket->resolved_at > $ticket->sla_due_at;
            })->count(),
        ];

        return view('admin.reports.sla-compliance', compact('tickets', 'complianceStats'));
    }

    /**
     * Agent Performance Report
     */
    public function agentPerformance()
    {
        $agents = User::where('role', 'agent')
            ->withCount([
                'assignedTickets as total_assigned',
                'assignedTickets as resolved_count' => function($query) {
                    $query->where('status', 'resolved');
                },
                'assignedTickets as open_count' => function($query) {
                    $query->where('status', 'open');
                },
                'assignedTickets as overdue_count' => function($query) {
                    $query->where('sla_due_at', '<', now())
                          ->whereNotIn('status', ['resolved', 'closed']);
                },
            ])
            ->get()
            ->map(function($agent) {
                $agent->resolution_rate = $agent->total_assigned > 0 
                    ? round(($agent->resolved_count / $agent->total_assigned) * 100, 1)
                    : 0;
                return $agent;
            });

        return view('admin.reports.agent-performance', compact('agents'));
    }

    /**
     * Export reports (CSV)
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'tickets');
        $filename = $type . '_report_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        if ($type === 'tickets') {
            return response()->stream(function() {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Title', 'Customer', 'Status', 'Priority', 'Assigned To', 'Created', 'Resolved']);

                Ticket::with(['user', 'assignedAgent'])->chunk(100, function($tickets) use ($file) {
                    foreach ($tickets as $ticket) {
                        fputcsv($file, [
                            $ticket->id,
                            $ticket->title,
                            $ticket->user->name,
                            $ticket->status,
                            $ticket->priority,
                            $ticket->assignedAgent->name ?? 'Unassigned',
                            $ticket->created_at->format('Y-m-d H:i'),
                            $ticket->resolved_at ? $ticket->resolved_at->format('Y-m-d H:i') : 'N/A',
                        ]);
                    }
                });

                fclose($file);
            }, 200, $headers);
        }

        return back()->with('error', 'Invalid export type');
    }

    /**
     * Helper: Calculate average resolution time
     */
    private function getAverageResolutionTime($startDate)
    {
        $avg = Ticket::where('created_at', '>=', $startDate)
            ->whereNotNull('resolved_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_hours'))
            ->first()
            ->avg_hours;

        return round($avg ?? 0, 1);
    }

    /**
     * Helper: Calculate SLA compliance rate
     */
    private function getSLAComplianceRate($startDate)
    {
        $total = Ticket::where('created_at', '>=', $startDate)
            ->whereIn('status', ['resolved', 'closed'])
            ->whereNotNull('sla_due_at')
            ->count();

        if ($total === 0) return 100;

        $metSLA = Ticket::where('created_at', '>=', $startDate)
            ->whereIn('status', ['resolved', 'closed'])
            ->whereNotNull('sla_due_at')
            ->whereColumn('resolved_at', '<=', 'sla_due_at')
            ->count();

        return round(($metSLA / $total) * 100, 1);
    }
}