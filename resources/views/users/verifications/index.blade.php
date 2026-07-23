@extends('layouts.adminlayouts')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/verifications.css') }}">
@endpush

@section('content')

   
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2><i class="fas fa-shield-alt me-2"></i>Verification Management</h2>
                <p>Manage user verification requests and documents</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ url('admin/verifications/create') }}" class="btn btn-create">
                    <i class="fas fa-plus me-2"></i>New Verification
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Total</div>
                        <div class="stat-number">{{ $verifications->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                        <i class="fas fa-list"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-left-color: #ffc107;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Pending</div>
                        <div class="stat-number">{{ $verifications->where('status', 'pending')->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background: #ffc107;">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-left-color: #28a745;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Approved</div>
                        <div class="stat-number">{{ $verifications->where('status', 'approved')->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background: #28a745;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-left-color: #dc3545;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Rejected</div>
                        <div class="stat-number">{{ $verifications->where('status', 'rejected')->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background: #dc3545;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-card">
        <div class="table-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0"><i class="fas fa-table me-2"></i>All Verifications</h5>
                </div>
                <div class="col-md-6 text-end">
                    <span class="badge bg-primary">{{ $verifications->count() }} records</span>
                </div>
            </div>
        </div>

        @if($verifications->isEmpty())
            <div class="empty-state">
                <i class="fas fa-file-alt"></i>
                <h4>No Verifications Found</h4>
                <p class="text-muted">Start by creating a new verification request.</p>
                <a href="{{ url('admin/verifications/create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus me-2"></i>Create First Verification
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover" id="verificationsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Full Name</th>
                            <th>Documents</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Verified</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($verifications as $verification)
                        <tr>
                            <td><strong>#{{ $verification->id }}</strong></td>
                            <td>
                                <span class="badge bg-info">User {{ $verification->user_id }}</span>
                            </td>
                            <td>{{ $verification->full_name }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    @if($verification->citizenship_front)
                                        <a href="{{ asset('storage/' . $verification->citizenship_front) }}" target="_blank" title="Citizenship Front">
                                            <img src="{{ asset('storage/' . $verification->citizenship_front) }}" 
                                                 alt="Front" class="image-preview">
                                        </a>
                                    @endif
                                    @if($verification->citizenship_back)
                                        <a href="{{ asset('storage/' . $verification->citizenship_back) }}" target="_blank" title="Citizenship Back">
                                            <img src="{{ asset('storage/' . $verification->citizenship_back) }}" 
                                                 alt="Back" class="image-preview">
                                        </a>
                                    @endif
                                    @if($verification->pan_card)
                                        <a href="{{ asset('storage/' . $verification->pan_card) }}" target="_blank" title="PAN Card">
                                            <img src="{{ asset('storage/' . $verification->pan_card) }}" 
                                                 alt="PAN" class="image-preview">
                                        </a>
                                    @else
                                        <span class="text-muted small">No PAN</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge-status badge-{{ $verification->status }}">
                                    <i class="fas fa-{{ $verification->status == 'pending' ? 'clock' : ($verification->status == 'approved' ? 'check' : 'times') }} me-1"></i>
                                    {{ ucfirst($verification->status) }}
                                </span>
                            </td>
                            <td>
                                @if($verification->remarks)
                                    <span class="text-muted small">{{ Str::limit($verification->remarks, 30) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($verification->verified_at)
                                    <span class="text-success small">{{ date('Y-m-d', strtotime($verification->verified_at)) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons text-center">
                                    <a href="{{ url('admin/verifications/' . $verification->user_id) }}" 
                                       class="btn btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ url('admin/verifications/' . $verification->id . '/edit') }}" 
                                       class="btn btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ url('admin/verifications/' . $verification->id) }}" 
                                          method="POST" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this verification?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
  @endsection
  
@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#verificationsTable').DataTable({
            order: [[0, 'desc']],
            pageLength: 10,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search verifications...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries"
            },
            columnDefs: [
                { orderable: false, targets: [3, 4, 6, 7] }
            ]
        });
    });
</script>
@endpush