@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Reports & Analytics</h2>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3>{{ $stats['total_tickets'] }}</h3>
                    <p class="text-muted mb-0">Total Tickets</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3>{{ $stats['resolved_tickets'] }}</h3>
                    <p class="text-muted mb-0">Resolved</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3>{{ $stats['average_resolution_time'] }}h</h3>
                    <p class="text-muted mb-0">Avg Resolution</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3>{{ $stats['sla_compliance_rate'] }}%</h3>
                    <p class="text-muted mb-0">SLA Compliance</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Available Reports</h5>
            <a href="{{ route('admin.reports.export', ['type' => 'tickets']) }}" class="btn btn-sm btn-success">
                <i class="bi bi-download me-1"></i> Export CSV
            </a>
        </div>
        <div class="card-body">
            <div class="list-group">
                <a href="{{ route('admin.reports.sla') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-clock-history me-2"></i> SLA Compliance Report
                    <p class="mb-0 text-muted small">View tickets resolved within SLA timeline</p>
                </a>
                <a href="{{ route('admin.reports.agent') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-people me-2"></i> Agent Performance Report
                    <p class="mb-0 text-muted small">Track agent productivity and resolution rates</p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection