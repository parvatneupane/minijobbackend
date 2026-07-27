<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-briefcase"></i>
            <span>FreelanceHub</span>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ url('/admin/dashboard') }}">
                <i class="fas fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ url('/admin/usersettings') }}">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
     <a href="{{ url('/admin/verifications') }}" class="{{ request()->is('admin/verifications*') ? 'active' : '' }}">
                <i class="fas fa-shield-alt"></i>
                <span>Verifications</span>
            </a>
            
            <a href="{{ url('/admin/tasks') }}">
                <i class="fas fa-tasks"></i>
                <span>Tasks</span>
            </a>

            <a href="{{ url('/admin/contracts') }}">
                <i class="fas fa-handshake"></i>
                <span>Contracts</span>
            </a>

            <a href="{{ url('/admin/payments') }}">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Payments</span>
            </a>

            <a href="{{url('/admin/account/accountsetting') }}">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </nav>

        <div class="sidebar-footer">
        
          <form action="{{ route('admin.logout') }}" method="POST">
    @csrf

    <button type="submit">
        <i class="fas fa-arrow-right-from-bracket"></i>
        <span>Logout</span>
    </button>
</form>

        </div>


</div>



    <main class="main">
        @yield('content')
    </main>

    <script>
        function toggleSwitch(element){
            element.classList.toggle('active');
        }

        function saveSettings(e){
            if(e) e.preventDefault();
        }

        function confirmDelete(){
            if(confirm('Delete account?')){
                alert('Deleted');
            }
        }

        function confirmDeactivate(){
            if(confirm('Deactivate account?')){
                alert('Deactivated');
            }
        }
    </script>

    @stack('scripts')

</body>
</html>
