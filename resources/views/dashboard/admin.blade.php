@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Admin Dashboard</h2>

    <!-- Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-lg-2 col-md-4">
            <div class="card stat-card border-primary">
                <div class="card-body text-center">
                    <i class="bi bi-ticket-perforated display-6 text-primary"></i>
                    <h3 class="mt-2">{{ $stats['total_tickets'] }}</h3>
                    <small class="text-muted">Total Tickets</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4">
            <div class="card stat-card border-warning">
                <div class="card-body text-center">
                    <i class="bi bi-exclamation-circle display-6 text-warning"></i>
                    <h3 class="mt-2">{{ $stats['open_tickets'] }}</h3>
                    <small class="text-muted">Open</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4">
            <div class="card stat-card border-info">
                <div class="card-body text-center">
                    <i class="bi bi-arrow-repeat display-6 text-info"></i>
                    <h3 class="mt-2">{{ $stats['in_progress_tickets'] }}</h3>
                    <small class="text-muted">In Progress</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4">
            <div class="card stat-card border-success">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle display-6 text-success"></i>
                    <h3 class="mt-2">{{ $stats['resolved_tickets'] }}</h3>
                    <small class="text-muted">Resolved</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4">
            <div class="card stat-card border-danger">
                <div class="card-body text-center">
                    <i class="bi bi-clock-history display-6 text-danger"></i>
                    <h3 class="mt-2">{{ $stats['overdue_tickets'] }}</h3>
                    <small class="text-muted">Overdue</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4">
            <div class="card stat-card border-secondary">
                <div class="card-body text-center">
                    <i class="bi bi-people display-6 text-secondary"></i>
                    <h3 class="mt-2">{{ $stats['total_customers'] }}</h3>
                    <small class="text-muted">Customers</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Tickets -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Tickets</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTickets as $ticket)
                                <tr>
                                    <td>#{{ $ticket->id }}</td>
                                    <td>{{ Str::limit($ticket->title, 30) }}</td>
                                    <td>{{ $ticket->user->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $ticket->getStatusColor() }}">
                                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $ticket->getPriorityColor() }}">
                                            {{ ucfirst($ticket->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agent Performance -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Agent Performance</h5>
                </div>
                <div class="card-body">
                    @foreach($agentPerformance as $agent)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <strong>{{ $agent->name }}</strong><br>
                            <small class="text-muted">
                                {{ $agent->total_tickets }} assigned | 
                                {{ $agent->resolved_tickets }} resolved
                            </small>
                        </div>
                        <span class="badge bg-primary">
                            {{ $agent->total_tickets > 0 ? round(($agent->resolved_tickets / $agent->total_tickets) * 100) : 0 }}%
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection