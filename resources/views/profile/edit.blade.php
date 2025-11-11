@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">My Profile</h2>

    <div class="row">
        <!-- Profile Info Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-primary text-white rounded-circle mx-auto d-flex align-items-center justify-content-center" 
                             style="width: 120px; height: 120px; font-size: 48px;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                    <h4>{{ auth()->user()->name }}</h4>
                    <p class="text-muted">{{ auth()->user()->email }}</p>
                    <span class="badge bg-primary">{{ ucfirst(auth()->user()->role) }}</span>
                    @if(auth()->user()->package_type)
                    <br>
                    <span class="badge bg-info mt-2">{{ ucfirst(auth()->user()->package_type) }} Package</span>
                    @endif
                </div>
            </div>

            <!-- User Statistics -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Statistics</h6>
                </div>
                <div class="card-body">
                    @if(auth()->user()->isCustomer())
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Tickets:</span>
                            <strong>{{ auth()->user()->tickets()->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Open Tickets:</span>
                            <strong class="text-warning">{{ auth()->user()->tickets()->where('status', 'open')->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Resolved:</span>
                            <strong class="text-success">{{ auth()->user()->tickets()->where('status', 'resolved')->count() }}</strong>
                        </div>
                    @elseif(auth()->user()->isAgent())
                        <div class="d-flex justify-content-between mb-2">
                            <span>Assigned Tickets:</span>
                            <strong>{{ auth()->user()->assignedTickets()->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Resolved:</span>
                            <strong class="text-success">{{ auth()->user()->assignedTickets()->where('status', 'resolved')->count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Pending:</span>
                            <strong class="text-warning">{{ auth()->user()->assignedTickets()->whereIn('status', ['open', 'in_progress'])->count() }}</strong>
                        </div>
                    @else
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Users:</span>
                            <strong>{{ \App\Models\User::count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Tickets:</span>
                            <strong>{{ \App\Models\Ticket::count() }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>System Uptime:</span>
                            <strong class="text-success">Active</strong>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Account Info -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Account Info</h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">Member Since</small>
                    <p class="mb-2">{{ auth()->user()->created_at->format('M d, Y') }}</p>
                    
                    <small class="text-muted">Last Updated</small>
                    <p class="mb-0">{{ auth()->user()->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Profile Forms -->
        <div class="col-md-8">
            <!-- Update Profile Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Update Profile Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                                <small class="text-warning">
                                    Your email address is unverified.
                                </small>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>

            <!-- Update Password -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Update Password</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimum 8 characters</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-key me-1"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>

            <!-- Delete Account -->
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Delete Account</h5>
                </div>
                <div class="card-body">
                    <p class="text-danger">
                        <strong>Warning:</strong> Once your account is deleted, all of its resources and data will be permanently deleted. 
                        Before deleting your account, please download any data or information that you wish to retain.
                    </p>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="bi bi-trash me-1"></i> Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Account Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                    <div class="mb-3">
                        <label for="password_delete" class="form-label">Enter your password to confirm:</label>
                        <input type="password" class="form-control" id="password_delete" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete My Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection