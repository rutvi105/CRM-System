@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Agent Dashboard</h2>

    <!-- Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card border-primary">
                <div class="card-body">
                    <h6 class="text-muted">Assigned Tickets</h6>
                    <h2 class="mb-0">{{ $stats['assigned_tickets'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-warning">
                <div class="card-body">
                    <h6 class="text-muted">Open</h6>
                    <h2 class="mb-0">{{ $stats['open_tickets'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-info">
                <div class="card-body">
                    <h6 class="text-muted">In Progress</h6>
                    <h2 class="mb-0">{{ $stats['in_progress_tickets'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-success">
                <div class="card-body">
                    <h6 class="text-muted">Resolved Today</h6>
                    <h2 class="mb-0">{{ $stats['resolved_today'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Overdue Tickets Alert -->
    @if($overdueTickets->count() > 0)
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle me-2"></i>
        You have <strong>{{ $overdueTickets->count() }}</strong> overdue ticket(s)!
    </div>
    @endif

    <!-- My Assigned Tickets -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">My Assigned Tickets</h5>
        </div>
        <div class="card-body">
            @if($myTickets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>SLA Due</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myTickets as $ticket)
                            <tr class="{{ $ticket->isOverdue() ? 'table-danger' : '' }}">
                                <td>#{{ $ticket->id }}</td>
                                <td>{{ Str::limit($ticket->title, 40) }}</td>
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
                                    @if($ticket->sla_due_at)
                                        <small class="{{ $ticket->isOverdue() ? 'text-danger fw-bold' : '' }}">
                                            {{ $ticket->getTimeRemaining() }}
                                        </small>
                                    @else
                                        <small class="text-muted">No SLA</small>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-check-circle display-1 text-success"></i>
                    <p class="mt-3">No pending tickets assigned to you!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection