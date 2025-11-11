@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>SLA Compliance Report</h2>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Reports
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h3 class="text-primary">{{ $complianceStats['total'] }}</h3>
                    <p class="text-muted mb-0">Total Tickets</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h3 class="text-success">{{ $complianceStats['met_sla'] }}</h3>
                    <p class="text-muted mb-0">Met SLA</p>
                    <small class="text-success">
                        {{ $complianceStats['total'] > 0 ? round(($complianceStats['met_sla'] / $complianceStats['total']) * 100, 1) : 0 }}%
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <h3 class="text-danger">{{ $complianceStats['breached_sla'] }}</h3>
                    <p class="text-muted mb-0">Breached SLA</p>
                    <small class="text-danger">
                        {{ $complianceStats['total'] > 0 ? round(($complianceStats['breached_sla'] / $complianceStats['total']) * 100, 1) : 0 }}%
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Resolved Tickets - SLA Compliance Details</h5>
        </div>
        <div class="card-body">
            @if($tickets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Ticket ID</th>
                                <th>Customer</th>
                                <th>Agent</th>
                                <th>Priority</th>
                                <th>SLA Due</th>
                                <th>Resolved At</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                            @php
                                $metSLA = $ticket->resolved_at && $ticket->sla_due_at && $ticket->resolved_at <= $ticket->sla_due_at;
                            @endphp
                            <tr class="{{ !$metSLA ? 'table-danger' : 'table-success' }}">
                                <td><strong>#{{ $ticket->id }}</strong></td>
                                <td>{{ $ticket->user->name }}</td>
                                <td>{{ $ticket->assignedAgent->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $ticket->getPriorityColor() }}">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </td>
                                <td>
                                    @if($ticket->sla_due_at)
                                        <small>{{ $ticket->sla_due_at->format('M d, Y H:i') }}</small>
                                    @else
                                        <small class="text-muted">No SLA</small>
                                    @endif
                                </td>
                                <td>
                                    @if($ticket->resolved_at)
                                        <small>{{ $ticket->resolved_at->format('M d, Y H:i') }}</small>
                                    @else
                                        <small class="text-muted">Not Resolved</small>
                                    @endif
                                </td>
                                <td>
                                    @if($metSLA)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i> Met SLA
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle me-1"></i> Breached SLA
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $tickets->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <p class="mt-3">No resolved tickets found.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection