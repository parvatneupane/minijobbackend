<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FreelanceHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .register-card { background: white; border-radius: 20px; padding: 40px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-width: 480px; width: 100%; }
        .register-card h3 { font-weight: 700; color: #333; }
        .form-control { border-radius: 10px; padding: 12px; }
        .btn-register { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 10px; padding: 12px; font-weight: 600; color: white; width: 100%; }
        .btn-register:hover { opacity: 0.9; color: white; }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="text-center mb-4">
            <h3><i class="fas fa-briefcase text-primary me-2"></i>FreelanceHub</h3>
            <p class="text-muted">Create your account</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">I am a</label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input type="radio" name="role" value="client" id="client" class="form-check-input" checked>
                        <label class="form-check-label" for="client">Client</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="role" value="freelancer" id="freelancer" class="form-check-input">
                        <label class="form-check-label" for="freelancer">Freelancer</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn-register">Create Account</button>
        </form>

        <p class="text-center mt-3">
            Already have an account? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Sign In</a>
        </p>
    </div>
</body>
</html>