@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">My Dashboard</h2>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card border-primary">
                <div class="card-body">
                    <h6 class="text-muted">Total Tickets</h6>
                    <h2 class="mb-0">{{ $stats['total_tickets'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card border-warning">
                <div class="card-body">
                    <h6 class="text-muted">Open Tickets</h6>
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
                    <h6 class="text-muted">Resolved</h6>
                    <h2 class="mb-0">{{ $stats['resolved_tickets'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Package Info -->
    @if(auth()->user()->package_type)
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        Your SLA Package: <strong>{{ auth()->user()->getPackageDisplayName() }}</strong>
    </div>
    @endif

    <!-- My Tickets -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">My Tickets</h5>
            <a href="{{ route('tickets.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i> New Ticket
            </a>
        </div>
        <div class="card-body">
            @if($myTickets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Assigned To</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myTickets as $ticket)
                            <tr>
                                <td>#{{ $ticket->id }}</td>
                                <td>{{ Str::limit($ticket->title, 40) }}</td>
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
                                <td>{{ $ticket->assignedAgent->name ?? 'Unassigned' }}</td>
                                <td>{{ $ticket->created_at->format('M d, Y') }}</td>
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
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <p class="mt-3">No tickets yet. Create your first ticket!</p>
                    <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                        Create Ticket
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection