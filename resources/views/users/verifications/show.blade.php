@extends('layouts.adminlayouts')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/verifications.css') }}">
@endpush

@section('content')


        <div class="row align-items-center">
            <div class="col-md-6">
                <h2><i class="fas fa-info-circle me-2"></i>Verification Details</h2>
                <p>#{{ $verification['id'] }} - {{ $verification['full_name'] }}</p>
            </div>
            <div class="col-md-6 text-end">
                <span class="status-badge-large status-{{ $verification['status'] }}">
                    <i class="fas fa-{{ $verification['status'] == 'pending' ? 'clock' : ($verification['status'] == 'approved' ? 'check-circle' : 'times-circle') }} me-2"></i>
                    {{ ucfirst($verification['status']) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Information -->
    <div class="row">
        <div class="col-md-6">
            <div class="detail-card">
                <div class="card-title">
                    <i class="fas fa-user"></i>User Information
                </div>
                <div class="info-row">
                    <span class="info-label">ID</span>
                    <span class="info-value">#{{ $verification['id'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">User ID</span>
                    <span class="info-value">
                        <span class="badge bg-info">User {{ $verification['user_id'] }}</span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Full Name</span>
                    <span class="info-value"><strong>{{ $verification['full_name'] }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        <span class="status-badge-large status-{{ $verification['status'] }}" style="font-size: 14px; padding: 5px 20px;">
                            {{ ucfirst($verification['status']) }}
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Remarks</span>
                    <span class="info-value">{{ $verification['remarks'] ?? 'No remarks provided' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Verified At</span>
                    <span class="info-value">{{ $verification['verified_at'] ?? 'Not verified yet' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Created At</span>
                    <span class="info-value">{{ $verification['created_at'] }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Updated At</span>
                    <span class="info-value">{{ $verification['updated_at'] }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-card">
                <div class="card-title">
                    <i class="fas fa-images"></i>Documents
                </div>
                <div class="document-gallery">
                    @if($verification['citizenship_front'])
                    <div class="document-item">
                        <a href="{{ $verification['citizenship_front'] }}" target="_blank">
                            <img src="{{ $verification['citizenship_front'] }}" alt="Citizenship Front">
                        </a>
                        <span class="doc-label">
                            <i class="fas fa-id-card text-primary"></i> Citizenship Front
                        </span>
                    </div>
                    @endif

                    @if($verification['citizenship_back'])
                    <div class="document-item">
                        <a href="{{ $verification['citizenship_back'] }}" target="_blank">
                            <img src="{{ $verification['citizenship_back'] }}" alt="Citizenship Back">
                        </a>
                        <span class="doc-label">
                            <i class="fas fa-id-card text-primary"></i> Citizenship Back
                        </span>
                    </div>
                    @endif

                    @if($verification['pan_card'])
                    <div class="document-item">
                        <a href="{{ $verification['pan_card'] }}" target="_blank">
                            <img src="{{ $verification['pan_card'] }}" alt="PAN Card">
                        </a>
                        <span class="doc-label">
                            <i class="fas fa-file-invoice text-success"></i> PAN Card
                        </span>
                    </div>
                    @else
                    <div class="document-item" style="opacity: 0.6;">
                        <div style="padding: 40px 0;">
                            <i class="fas fa-file-invoice" style="font-size: 48px; color: #ccc;"></i>
                        </div>
                        <span class="doc-label text-muted">No PAN Card Uploaded</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="action-buttons">
        <a href="{{ url('admin/verifications') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
        <a href="{{ url('admin/verifications/' . $verification['id'] . '/edit') }}" class="btn btn-warning" style="color: white;">
            <i class="fas fa-edit me-2"></i>Edit Verification
        </a>
        <form action="{{ url('admin/verifications/' . $verification['id']) }}" method="POST" class="d-inline" 
              onsubmit="return confirm('Are you sure you want to delete this verification?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-2"></i>Delete Verification
            </button>
        </form>
 
@endsection