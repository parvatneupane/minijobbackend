<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Freelance Platform')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
    
    <style>
        :root {
            --primary-color: #4F46E5;
            --secondary-color: #7C3AED;
            --success-color: #10B981;
            --warning-color: #F59E0B;
            --danger-color: #EF4444;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F3F4F6;
        }
        
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .navbar-custom .navbar-brand {
            font-weight: 700;
            color: white;
        }
        
        .navbar-custom .nav-link {
            color: rgba(255,255,255,0.9) !important;
            transition: all 0.3s;
        }
        
        .navbar-custom .nav-link:hover {
            color: white !important;
            transform: translateY(-1px);
        }
        
        .sidebar {
            background: white;
            min-height: calc(100vh - 72px);
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            padding: 20px 0;
        }
        
        .sidebar .nav-link {
            color: #4B5563;
            padding: 12px 24px;
            border-radius: 8px;
            margin: 4px 12px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            background: #EEF2FF;
            color: var(--primary-color);
        }
        
        .sidebar .nav-link.active {
            background: var(--primary-color);
            color: white;
        }
        
        .sidebar .nav-link i {
            width: 24px;
            margin-right: 12px;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #F3F4F6;
            padding: 20px 24px;
            font-weight: 600;
            border-radius: 12px 12px 0 0 !important;
        }
        
        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background: #4338CA;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background: #F9FAFB;
            border-bottom: 2px solid #E5E7EB;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        
        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 12px;
        }
        
        .badge-status.active { background: #D1FAE5; color: #065F46; }
        .badge-status.pending { background: #FEF3C7; color: #92400E; }
        .badge-status.completed { background: #DBEAFE; color: #1E40AF; }
        .badge-status.inactive { background: #FEE2E2; color: #991B1B; }
        
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            border-left: 4px solid var(--primary-color);
        }
        
        .stat-card .stat-icon {
            font-size: 32px;
            color: var(--primary-color);
        }
        
        .stat-card .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
        }
        
        .stat-card .stat-label {
            color: #6B7280;
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
                padding: 10px 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-briefcase me-2"></i> FreelanceHub
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="fas fa-user me-2"></i> Profile
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @auth
            <div class="col-md-2 col-lg-2 d-md-block sidebar">
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-chart-pie"></i> Dashboard
                    </a>
                    
                    @if(Auth::user()->role == 'client')
                    <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}" href="{{ route('tasks.index') }}">
                        <i class="fas fa-tasks"></i> My Tasks
                    </a>
                    <a class="nav-link {{ request()->routeIs('proposals.*') ? 'active' : '' }}" href="{{ route('proposals.index') }}">
                        <i class="fas fa-file-signature"></i> Proposals
                    </a>
                    @endif
                    
                    @if(Auth::user()->role == 'freelancer')
                    <a class="nav-link {{ request()->routeIs('freelancer.profile') ? 'active' : '' }}" href="{{ route('freelancer.profile') }}">
                        <i class="fas fa-user-tie"></i> My Profile
                    </a>
                    <a class="nav-link {{ request()->routeIs('submissions.*') ? 'active' : '' }}" href="{{ route('submissions.index') }}">
                        <i class="fas fa-upload"></i> Submissions
                    </a>
                    @endif
                    
                    <a class="nav-link {{ request()->routeIs('contracts.*') ? 'active' : '' }}" href="{{ route('contracts.index') }}">
                        <i class="fas fa-file-contract"></i> Contracts
                    </a>
                    
                    <a class="nav-link {{ request()->routeIs('milestones.*') ? 'active' : '' }}" href="{{ route('milestones.index') }}">
                        <i class="fas fa-flag-checkered"></i> Milestones
                    </a>
                    
                    <a class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}">
                        <i class="fas fa-credit-card"></i> Payments
                    </a>
                    
                    <a class="nav-link {{ request()->routeIs('chats.*') ? 'active' : '' }}" href="{{ route('chats.index') }}">
                        <i class="fas fa-comments"></i> Messages
                        <span class="badge bg-danger float-end">3</span>
                    </a>
                    
                    <a class="nav-link {{ request()->routeIs('reviews.*') ? 'active' : '' }}" href="{{ route('reviews.index') }}">
                        <i class="fas fa-star"></i> Reviews
                    </a>
                    
                    @if(Auth::user()->role == 'admin')
                    <hr>
                    <a class="nav-link {{ request()->routeIs('verifications.*') ? 'active' : '' }}" href="{{ route('verifications.index') }}">
                        <i class="fas fa-shield-alt"></i> Verifications
                    </a>
                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                        <i class="fas fa-tags"></i> Categories
                    </a>
                    @endif
                </nav>
            </div>
            @endauth

            <!-- Main Content -->
            <main class="col-md-10 col-lg-10 ms-sm-auto px-md-4 py-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container">
        @if(session('success'))
        <div class="toast align-items-center text-white bg-success border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        @endif
        
        @if(session('error'))
        <div class="toast align-items-center text-white bg-danger border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
        @endif
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Auto-hide toasts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelectorAll('.toast').forEach(function(toast) {
                    toast.classList.remove('show');
                });
            }, 5000);
        });
        
        // Confirm delete
        function confirmDelete(formId) {
            if (confirm('Are you sure you want to delete this item?')) {
                document.getElementById(formId).submit();
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>