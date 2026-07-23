@extends('layouts.adminlayouts')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/verifications.css') }}">
@endpush

@section('content')


        <div class="row align-items-center">
            <div class="col-md-6">
                <h2><i class="fas fa-edit me-2"></i>Edit Verification</h2>
                <p>#{{ $verification->id }} - {{ $verification->full_name }}</p>
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

        <form action="{{ url('admin/verifications/' . $verification->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-user"></i>Basic Information
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" name="full_name" id="full_name" 
                               class="form-control @error('full_name') is-invalid @enderror" 
                               value="{{ old('full_name', $verification->full_name) }}">
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" 
                                class="form-control @error('status') is-invalid @enderror">
                            <option value="pending" {{ $verification->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $verification->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $verification->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-file-alt"></i>Documents
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Citizenship Front</label>
                        @if($verification->citizenship_front)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $verification->citizenship_front) }}" 
                                     alt="Current Front" class="current-image">
                                <br>
                                <span class="text-muted small">Current image</span>
                            </div>
                        @endif
                        <div class="file-upload-wrapper">
                            <div class="file-text">
                                <strong>Click to change</strong><br>
                                <small>JPG, JPEG, PNG (Max 4MB)</small>
                            </div>
                            <input type="file" name="citizenship_front" id="citizenship_front" 
                                   accept="image/*">
                        </div>
                        <div class="hint-text">
                            <i class="fas fa-info-circle"></i> Leave empty to keep current image
                        </div>
                        @error('citizenship_front')
                            <div class="text-danger mt-2 small">{{ $message }}</div>
                        @enderror
                        <div id="frontPreview" class="image-preview-container"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Citizenship Back</label>
                        @if($verification->citizenship_back)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $verification->citizenship_back) }}" 
                                     alt="Current Back" class="current-image">
                                <br>
                                <span class="text-muted small">Current image</span>
                            </div>
                        @endif
                        <div class="file-upload-wrapper">
                            <div class="file-text">
                                <strong>Click to change</strong><br>
                                <small>JPG, JPEG, PNG (Max 4MB)</small>
                            </div>
                            <input type="file" name="citizenship_back" id="citizenship_back" 
                                   accept="image/*">
                        </div>
                        <div class="hint-text">
                            <i class="fas fa-info-circle"></i> Leave empty to keep current image
                        </div>
                        @error('citizenship_back')
                            <div class="text-danger mt-2 small">{{ $message }}</div>
                        @enderror
                        <div id="backPreview" class="image-preview-container"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">PAN Card</label>
                        @if($verification->pan_card)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $verification->pan_card) }}" 
                                     alt="Current PAN" class="current-image">
                                <br>
                                <span class="text-muted small">Current image</span>
                            </div>
                        @endif
                        <div class="file-upload-wrapper">
                            <div class="file-text">
                                <strong>Click to change</strong><br>
                                <small>JPG, JPEG, PNG (Max 4MB)</small>
                            </div>
                            <input type="file" name="pan_card" id="pan_card" 
                                   accept="image/*">
                        </div>
                        <div class="hint-text">
                            <i class="fas fa-info-circle"></i> Leave empty to keep current image
                        </div>
                        @error('pan_card')
                            <div class="text-danger mt-2 small">{{ $message }}</div>
                        @enderror
                        <div id="panPreview" class="image-preview-container"></div>
                    </div>
                </div>
            </div>

            <!-- Remarks -->
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-comment"></i>Remarks
                </div>
                <div class="mb-3">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea name="remarks" id="remarks" rows="4" 
                              class="form-control @error('remarks') is-invalid @enderror" 
                              placeholder="Add any remarks or notes about this verification...">{{ old('remarks', $verification->remarks) }}</textarea>
                    @error('remarks')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Submit -->
            <div class="text-center mt-4">
                <button type="button" class="btn btn-back me-3" onclick="window.location.href='admin/verifications'">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-save me-2"></i>Update Verification
                </button>
            </div>
        </form>
   
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
                    <span style="display: block; margin-top: 10px; color: #28a745; font-size: 14px;">
                        <i class="fas fa-check-circle"></i> New image selected
                    </span>
                `;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
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
</script>
@endpush