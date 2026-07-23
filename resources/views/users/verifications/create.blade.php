@extends('layouts.adminlayouts')
 
@push('styles')
<link rel="stylesheet" href="{{ asset('css/verifications.css') }}">
@endpush

@section('content')


  
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2><i class="fas fa-upload me-2"></i>New Verification Request</h2>
                <p>Submit documents for user verification</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ url('admin/verifications') }}" class="btn btn-back" style="background: rgba(255,255,255,0.2); color: white; border-color: rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="form-card">
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('admin/verifications') }}" method="POST" enctype="multipart/form-data" id="verificationForm">
            @csrf

            <!-- User Information -->
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-user"></i>User Information
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="user_id" class="form-label">
                            User ID <span class="required">*</span>
                        </label>
                        <input type="number" name="user_id" id="user_id" 
                               class="form-control @error('user_id') is-invalid @enderror" 
                               value="{{ old('user_id') }}" required 
                               placeholder="Enter user ID">
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="full_name" class="form-label">
                            Full Name <span class="required">*</span>
                        </label>
                        <input type="text" name="full_name" id="full_name" 
                               class="form-control @error('full_name') is-invalid @enderror" 
                               value="{{ old('full_name') }}" required 
                               placeholder="Enter full name">
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-file-alt"></i>Documents Upload
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Citizenship Front <span class="required">*</span>
                        </label>
                        <div class="file-upload-wrapper">
                            <div class="file-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="file-text">
                                <strong>Click to upload</strong><br>
                                <small>JPG, JPEG, PNG (Max 4MB)</small>
                            </div>
                            <input type="file" name="citizenship_front" id="citizenship_front" 
                                   accept="image/*" required>
                        </div>
                        @error('citizenship_front')
                            <div class="text-danger mt-2 small">{{ $message }}</div>
                        @enderror
                        <div id="frontPreview" class="image-preview-container"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Citizenship Back <span class="required">*</span>
                        </label>
                        <div class="file-upload-wrapper">
                            <div class="file-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="file-text">
                                <strong>Click to upload</strong><br>
                                <small>JPG, JPEG, PNG (Max 4MB)</small>
                            </div>
                            <input type="file" name="citizenship_back" id="citizenship_back" 
                                   accept="image/*" required>
                        </div>
                        @error('citizenship_back')
                            <div class="text-danger mt-2 small">{{ $message }}</div>
                        @enderror
                        <div id="backPreview" class="image-preview-container"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            PAN Card <span class="text-muted">(Optional)</span>
                        </label>
                        <div class="file-upload-wrapper">
                            <div class="file-icon">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div class="file-text">
                                <strong>Click to upload</strong><br>
                                <small>JPG, JPEG, PNG (Max 4MB)</small>
                            </div>
                            <input type="file" name="pan_card" id="pan_card" 
                                   accept="image/*">
                        </div>
                        @error('pan_card')
                            <div class="text-danger mt-2 small">{{ $message }}</div>
                        @enderror
                        <div id="panPreview" class="image-preview-container"></div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="text-center mt-4">
                <button type="button" class="btn btn-back me-3" onclick="window.location.href='admin/verifications'">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-paper-plane me-2"></i>Submit Verification
                </button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <span class="remove-image" onclick="removeImage('${previewId}')">
                        <i class="fas fa-times-circle"></i> Remove
                    </span>
                `;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage(previewId) {
        const preview = document.getElementById(previewId);
        preview.innerHTML = '';
        preview.style.display = 'none';
        const inputId = previewId.replace('Preview', '');
        document.getElementById(inputId).value = '';
    }

    document.getElementById('citizenship_front').addEventListener('change', function() {
        previewImage(this, 'frontPreview');
    });

    document.getElementById('citizenship_back').addEventListener('change', function() {
        previewImage(this, 'backPreview');
    });

    document.getElementById('pan_card').addEventListener('change', function() {
        previewImage(this, 'panPreview');
    });

    document.getElementById('verificationForm').addEventListener('submit', function(e) {
        const front = document.getElementById('citizenship_front');
        const back = document.getElementById('citizenship_back');
        
        if (!front.files || !front.files[0]) {
            e.preventDefault();
            alert('Please upload the citizenship front image.');
            return false;
        }
        
        if (!back.files || !back.files[0]) {
            e.preventDefault();
            alert('Please upload the citizenship back image.');
            return false;
        }
        
        return true;
    });
</script>
@endpush