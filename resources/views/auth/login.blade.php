<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login · modern card</title>
  <!-- Font Awesome 6 (free) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Bootstrap 5 + custom CSS overlay -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* ----- global reset & base ----- */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(145deg, #f6f9fc 0%, #e9f2f9 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, 'Helvetica Neue', sans-serif;
    }

    /* card elegance */
    .card {
      border: none;
      border-radius: 28px;
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(4px);
      box-shadow: 0 20px 40px -12px rgba(0, 20, 40, 0.25), 0 8px 24px -6px rgba(0, 32, 64, 0.08);
      transition: transform 0.2s ease, box-shadow 0.25s ease;
    }

    .card:hover {
      transform: translateY(-4px);
      box-shadow: 0 30px 50px -16px rgba(0, 40, 80, 0.3);
    }

    .card-body {
      padding: 2.5rem 2rem !important;   /* p-5 override with extra polish */
    }

    /* icon + heading */
    .fa-briefcase {
      background: linear-gradient(135deg, #1e4f8a, #3a7bd5);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      filter: drop-shadow(0 4px 6px rgba(26, 67, 143, 0.15));
    }

    h3 {
      font-weight: 700;
      letter-spacing: -0.02em;
      color: #0b2b4a;
      margin-top: 0.25rem;
    }

    .text-muted {
      color: #5b6f82 !important;
      font-weight: 400;
      font-size: 0.95rem;
    }

    /* form labels */
    .form-label {
      font-weight: 600;
      color: #1a334a;
      margin-bottom: 0.4rem;
      font-size: 0.9rem;
      letter-spacing: 0.02em;
    }

    /* input group styling */
    .input-group-text {
      background: #f0f5fb;
      border: 1px solid #dae4ed;
      border-right: none;
      color: #2a4b6e;
      padding: 0.65rem 1rem;
      border-radius: 14px 0 0 14px;
      font-size: 1rem;
    }

    .form-control {
      border: 1px solid #dae4ed;
      border-left: none;
      padding: 0.65rem 1rem;
      font-size: 0.95rem;
      border-radius: 0 14px 14px 0;
      background: #ffffff;
      transition: border 0.2s, box-shadow 0.15s;
    }

    .form-control:focus {
      border-color: #3a7bd5;
      box-shadow: 0 0 0 4px rgba(58, 123, 213, 0.12);
      background: #ffffff;
    }

    .input-group:focus-within .input-group-text {
      border-color: #3a7bd5;
      background: #e9f2fc;
    }

    /* invalid feedback */
    .invalid-feedback {
      font-size: 0.8rem;
      font-weight: 500;
      margin-top: 0.4rem;
      color: #d9534f;
      display: block;
    }

    .is-invalid {
      border-color: #e06b6b !important;
      box-shadow: 0 0 0 3px rgba(221, 75, 57, 0.08);
    }

    .is-invalid:focus {
      box-shadow: 0 0 0 4px rgba(221, 75, 57, 0.15);
    }

    /* checkbox style */
    .form-check-input {
      border-radius: 5px;
      border: 1.5px solid #b8ccdd;
      margin-top: 0.2rem;
      transition: 0.15s;
    }

    .form-check-input:checked {
      background-color: #2b6eb0;
      border-color: #2b6eb0;
      box-shadow: 0 2px 6px rgba(43, 110, 176, 0.25);
    }

    .form-check-label {
      font-size: 0.9rem;
      color: #1f3f5c;
      font-weight: 450;
    }

    /* primary button */
    .btn-primary {
      background: linear-gradient(145deg, #1e4f8a, #2f6eb3);
      border: none;
      border-radius: 40px;
      padding: 0.7rem 1.5rem;
      font-weight: 600;
      font-size: 1rem;
      letter-spacing: 0.3px;
      transition: all 0.2s;
      box-shadow: 0 6px 14px rgba(30, 79, 138, 0.25);
      color: white;
    }

    .btn-primary:hover {
      background: linear-gradient(145deg, #16416e, #1f5a9e);
      transform: scale(1.01);
      box-shadow: 0 10px 20px -6px rgba(26, 67, 128, 0.4);
    }

    .btn-primary:active {
      transform: scale(0.97);
      box-shadow: 0 4px 10px rgba(26, 67, 128, 0.3);
    }

    .btn-primary i {
      font-size: 0.95rem;
    }

    hr {
      opacity: 0.4;
      width: 70%;
      margin: 1.5rem auto 1.2rem;
      border-top: 1.5px dashed #b8ccdd;
    }

    /* sign up link */
    .text-primary.fw-semibold {
      color: #1f5a9e !important;
      font-weight: 600;
      border-bottom: 2px solid transparent;
      transition: 0.15s;
      padding-bottom: 1px;
    }

    .text-primary.fw-semibold:hover {
      border-bottom-color: #1f5a9e;
      color: #0e3d70 !important;
    }

    /* extra spacing & polish for mobile */
    @media (max-width: 576px) {
      .card-body {
        padding: 1.75rem 1.25rem !important;
      }

      .btn-primary {
        font-size: 0.95rem;
      }

      h3 {
        font-size: 1.6rem;
      }
    }

    /* row alignment - 80vh minimal height */
    .row {
      min-height: 80vh;
    }

    /* subtle background pattern */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image: radial-gradient(circle at 10% 20%, rgba(58, 123, 213, 0.02) 0%, transparent 50%),
                        radial-gradient(circle at 90% 70%, rgba(30, 79, 138, 0.03) 0%, transparent 60%);
      pointer-events: none;
      z-index: 0;
    }

    .container {
      position: relative;
      z-index: 1;
    }

    /* remember me alignment */
    .d-flex.align-items-center .form-check {
      margin-bottom: 0;
    }

    .mb-3.d-flex {
      flex-wrap: wrap;
      gap: 0.25rem 0;
    }

    /* small icon in button */
    .btn i.fa-sign-in-alt {
      font-size: 0.9rem;
    }

    /* card shadow-lg but softer */
    .shadow-lg {
      box-shadow: 0 16px 40px -12px rgba(10, 30, 60, 0.18), 0 8px 20px -6px rgba(0, 0, 0, 0.02) !important;
    }

    /* optional: floating label effect (just for style) */
    .input-group .form-control::placeholder {
      color: #99b0c4;
      font-weight: 350;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- exact row from the snippet, with min-height 80vh -->
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-lg">
          <div class="card-body p-5">
            <div class="text-center mb-4">
              <i class="fas fa-briefcase fa-3x text-primary mb-3"></i>
              <h3 class="fw-bold">Welcome Back</h3>
              <p class="text-muted">Sign in to your account</p>
            </div>
            
            <form method="POST" action="{{ url('/login') }}">
              @csrf
              
              <div class="mb-3">
                <label class="form-label fw-semibold">Email Address</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" 
                         name="email" value="{{ old('email') }}" placeholder="Enter your email" required>
                </div>
                @error('email')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                  <input type="password" class="form-control @error('password') is-invalid @enderror" 
                         name="password" placeholder="Enter your password" required>
                </div>
                @error('password')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="mb-3 d-flex justify-content-between align-items-center">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="remember" id="remember">
                  <label class="form-check-label" for="remember">Remember me</label>
                </div>
              </div>
              
              <button type="submit" class="btn btn-primary w-100 py-2">
                <i class="fas fa-sign-in-alt me-2"></i> Sign In
              </button>
            </form>
            
            <hr class="my-4">
            <p class="text-center text-muted mb-0">
              Don't have an account? 
              <a href="{{ url('/register') }}" class="text-primary fw-semibold text-decoration-none">Sign Up</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- optional bootstrap js (not required for style) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>