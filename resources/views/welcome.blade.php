<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreelanceHub - Connect Freelancers & Clients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 100px 0; }
        .hero h1 { font-weight: 700; font-size: 3.5rem; }
        .feature-card { transition: transform 0.3s; padding: 30px; border-radius: 15px; }
        .feature-card:hover { transform: translateY(-10px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .btn-hero { background: white; color: #667eea; border-radius: 50px; padding: 12px 40px; font-weight: 600; }
        .btn-hero:hover { background: #f8f9fa; color: #667eea; }
        .btn-hero-outline { border: 2px solid white; color: white; border-radius: 50px; padding: 12px 40px; font-weight: 600; }
        .btn-hero-outline:hover { background: white; color: #667eea; }
        .stats-section { background: #f8f9fa; padding: 60px 0; }
        .stat-number { font-size: 3rem; font-weight: 700; color: #667eea; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-briefcase me-2"></i>FreelanceHub
            </a>
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#how-it-works">How It Works</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-primary text-white px-4" href="{{ route('register') }}">Get Started</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero text-center">
        <div class="container">
            <h1>Find the Perfect Freelancer</h1>
            <p class="lead mb-4">Connect with skilled professionals from around the world. Post projects, hire experts, and get work done.</p>
            <div>
                <a href="{{ route('register') }}" class="btn btn-hero me-3">Get Started Free</a>
               
            </div>
        </div>
    </section>

 
 
    <!-- How It Works -->
    <section id="how-it-works" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">How It Works</h2>
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="display-1 text-primary">1</div>
                    <h5>Post a Project</h5>
                    <p class="text-muted">Describe your project, set a budget, and post it for freelancers to see.</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="display-1 text-primary">2</div>
                    <h5>Receive Proposals</h5>
                    <p class="text-muted">Get proposals from qualified freelancers and choose the best fit.</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="display-1 text-primary">3</div>
                    <h5>Get Work Done</h5>
                    <p class="text-muted">Collaborate, track progress, and release payment upon completion.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div class="container text-center">
            <h2>Ready to Get Started?</h2>
            <p class="lead mb-4">Join thousands of freelancers and clients already using FreelanceHub.</p>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">Create Free Account</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} FreelanceHub. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>