@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Agent Performance Report</h2>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Reports
        </a>
    </div>

    <!-- Agent Performance Cards -->
    <div class="row g-4">
        @foreach($agents as $agent)
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-person-circle me-2"></i>{{ $agent->name }}
                        </h5>
                        <span class="badge bg-light text-dark">
                            {{ $agent->resolution_rate }}% Resolution Rate
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-3">
                            <div class="p-3 bg-light rounded">
                                <h4 class="mb-0 text-primary">{{ $agent->total_assigned }}</h4>
                                <small class="text-muted">Total Assigned</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-3 bg-success bg-opacity-10 rounded">
                                <h4 class="mb-0 text-success">{{ $agent->resolved_count }}</h4>
                                <small class="text-muted">Resolved</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-3 bg-warning bg-opacity-10 rounded">
                                <h4 class="mb-0 text-warning">{{ $agent->open_count }}</h4>
                                <small class="text-muted">Open</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-3 bg-danger bg-opacity-10 rounded">
                                <h4 class="mb-0 text-danger">{{ $agent->overdue_count }}</h4>
                                <small class="text-muted">Overdue</small>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-3">
                        <label class="form-label small text-muted">Performance</label>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar 
                                {{ $agent->resolution_rate >= 80 ? 'bg-success' : ($agent->resolution_rate >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                role="progressbar" 
                                style="width: {{ $agent->resolution_rate }}%"
                                aria-valuenow="{{ $agent->resolution_rate }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                {{ $agent->resolution_rate }}%
                            </div>
                        </div>
                    </div>

                    <!-- Performance Rating -->
                    <div class="text-center">
                        @if($agent->resolution_rate >= 80)
                            <span class="badge bg-success px-3 py-2">
                                <i class="bi bi-star-fill me-1"></i> Excellent Performance
                            </span>
                        @elseif($agent->resolution_rate >= 50)
                            <span class="badge bg-warning px-3 py-2">
                                <i class="bi bi-star-half me-1"></i> Good Performance
                            </span>
                        @else
                            <span class="badge bg-danger px-3 py-2">
                                <i class="bi bi-star me-1"></i> Needs Improvement
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    <small class="text-muted">
                        <i class="bi bi-envelope me-1"></i>{{ $agent->email }}
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($agents->count() == 0)
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox display-1 text-muted"></i>
            <p class="mt-3">No agents found in the system.</p>
        </div>
    </div>
    @endif

    <!-- Summary Table -->
    @if($agents->count() > 0)
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Performance Summary</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Agent Name</th>
                            <th>Email</th>
                            <th>Total Assigned</th>
                            <th>Resolved</th>
                            <th>Open</th>
                            <th>Overdue</th>
                            <th>Resolution Rate</th>
                            <th>Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agents->sortByDesc('resolution_rate') as $agent)
                        <tr>
                            <td><strong>{{ $agent->name }}</strong></td>
                            <td><small>{{ $agent->email }}</small></td>
                            <td><span class="badge bg-primary">{{ $agent->total_assigned }}</span></td>
                            <td><span class="badge bg-success">{{ $agent->resolved_count }}</span></td>
                            <td><span class="badge bg-warning">{{ $agent->open_count }}</span></td>
                            <td><span class="badge bg-danger">{{ $agent->overdue_count }}</span></td>
                            <td>
                                <strong class="{{ $agent->resolution_rate >= 80 ? 'text-success' : ($agent->resolution_rate >= 50 ? 'text-warning' : 'text-danger') }}">
                                    {{ $agent->resolution_rate }}%
                                </strong>
                            </td>
                            <td>
                                @if($agent->resolution_rate >= 80)
                                    <i class="bi bi-star-fill text-success"></i>
                                    <i class="bi bi-star-fill text-success"></i>
                                    <i class="bi bi-star-fill text-success"></i>
                                @elseif($agent->resolution_rate >= 50)
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                @else
                                    <i class="bi bi-star-fill text-danger"></i>
                                    <i class="bi bi-star text-danger"></i>
                                    <i class="bi bi-star text-danger"></i>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection