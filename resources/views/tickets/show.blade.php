@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Ticket Details -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ticket #{{ $ticket->id }}</h5>
                    <div>
                        <span class="badge bg-{{ $ticket->getStatusColor() }} me-2">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                        <span class="badge bg-{{ $ticket->getPriorityColor() }}">
                            {{ ucfirst($ticket->priority) }} Priority
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <h4 class="mb-3">{{ $ticket->title }}</h4>
                    
                    <div class="mb-4 p-3 bg-light rounded">
                        <p class="mb-0">{{ $ticket->description }}</p>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <small class="text-muted">Created By</small>
                            <p class="mb-0"><strong>{{ $ticket->user->name }}</strong></p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Assigned To</small>
                            <p class="mb-0">
                                <strong>{{ $ticket->assignedAgent->name ?? 'Unassigned' }}</strong>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">SLA Due</small>
                            <p class="mb-0 {{ $ticket->isOverdue() ? 'text-danger fw-bold' : '' }}">
                                <strong>{{ $ticket->getTimeRemaining() }}</strong>
                            </p>
                        </div>
                    </div>

                    @if($ticket->pending_reason)
                    <div class="alert alert-warning">
                        <strong>Pending Reason:</strong> {{ $ticket->pending_reason }}
                    </div>
                    @endif

                    <!-- Action Buttons for Agent/Admin -->
                    @if(auth()->user()->isAgent() || auth()->user()->isAdmin())
                    <hr>
                    <h6>Quick Actions</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @if($ticket->status != 'in_progress')
                        <form method="POST" action="{{ route('tickets.update-status', $ticket) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="btn btn-sm btn-info">
                                <i class="bi bi-play-circle"></i> Start Working
                            </button>
                        </form>
                        @endif

                        @if($ticket->status != 'resolved')
                        <form method="POST" action="{{ route('tickets.update-status', $ticket) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="resolved">
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="bi bi-check-circle"></i> Mark Resolved
                            </button>
                        </form>
                        @endif

                        @if($ticket->status != 'closed')
                        <form method="POST" action="{{ route('tickets.update-status', $ticket) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="closed">
                            <button type="submit" class="btn btn-sm btn-secondary">
                                <i class="bi bi-x-circle"></i> Close Ticket
                            </button>
                        </form>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Ticket History -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Ticket History</h6>
                </div>
                <div class="card-body">
                    @foreach($ticket->history as $history)
                    <div class="d-flex mb-3 pb-3 border-bottom">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px;">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <strong>{{ $history->changer->name }}</strong>
                            <span class="text-muted">{{ $history->getFormattedAction() }}</span>
                            <br>
                            @if($history->old_value && $history->new_value)
                                <small class="text-muted">
                                    From <span class="badge bg-secondary">{{ $history->old_value }}</span>
                                    to <span class="badge bg-primary">{{ $history->new_value }}</span>
                                </small>
                            @endif
                            <br>
                            <small class="text-muted">{{ $history->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            <!-- Update Status -->
            @if(auth()->user()->isAgent() || auth()->user()->isAdmin())
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Update Status</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tickets.update-status', $ticket) }}">
                        @csrf
                        <div class="mb-3">
                            <select name="status" class="form-select" id="statusSelect" required>
                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div class="mb-3" id="pending-reason-box" style="display: {{ $ticket->status == 'pending' ? 'block' : 'none' }};">
                            <textarea name="pending_reason" class="form-control" rows="3" 
                                      placeholder="Reason for pending...">{{ $ticket->pending_reason }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                </div>
            </div>

            <!-- Assign Agent -->
            @if(auth()->user()->isAdmin())
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Assign Agent</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tickets.assign', $ticket) }}">
                        @csrf
                        <div class="mb-3">
                            <select name="assigned_to" class="form-select" required>
                                <option value="">Select Agent</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" 
                                            {{ $ticket->assigned_to == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Assign</button>
                    </form>
                </div>
            </div>

            <!-- Update Priority -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Update Priority</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('tickets.update-priority', $ticket) }}">
                        @csrf
                        <div class="mb-3">
                            <select name="priority" class="form-select" required>
                                <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Priority</button>
                    </form>
                </div>
            </div>
            @endif
            @endif

            <!-- Ticket Info -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Ticket Information</h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">Created</small>
                    <p>{{ $ticket->created_at->format('M d, Y H:i') }}</p>

                    @if($ticket->resolved_at)
                    <small class="text-muted">Resolved</small>
                    <p>{{ $ticket->resolved_at->format('M d, Y H:i') }}</p>
                    @endif

                    @if($ticket->closed_at)
                    <small class="text-muted">Closed</small>
                    <p>{{ $ticket->closed_at->format('M d, Y H:i') }}</p>
                    @endif

                    @if(auth()->user()->isAdmin())
                    <hr>
                    <form method="POST" action="{{ route('tickets.destroy', $ticket) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash"></i> Delete Ticket
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Show/hide pending reason field
    document.getElementById('statusSelect').addEventListener('change', function() {
        const reasonBox = document.getElementById('pending-reason-box');
        if (this.value === 'pending') {
            reasonBox.style.display = 'block';
        } else {
            reasonBox.style.display = 'none';
        }
    });
</script>
@endpush
@endsection