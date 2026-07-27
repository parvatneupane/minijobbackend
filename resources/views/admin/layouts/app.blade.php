<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | Freelance Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-gray-50 text-gray-800">
<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-gray-900 text-gray-200 flex-shrink-0 hidden md:block">
        <div class="px-5 py-5 border-b border-gray-800">
            <span class="text-lg font-bold text-white">⚙ Admin Panel</span>
        </div>
        <nav class="py-4 space-y-1 text-sm">
            @php
                $links = [
                    'admin.dashboard' => ['Dashboard', 'admin/dashboard'],
                    'admin.users.index' => ['Users', 'admin/users'],
                    'admin.freelancer-profiles.index' => ['Freelancer Profiles', 'admin/freelancer-profiles'],
                    'admin.verifications.index' => ['Verifications', 'admin/verifications'],
                    'admin.task-categories.index' => ['Task Categories', 'admin/task-categories'],
                    'admin.tasks.index' => ['Tasks', 'admin/tasks'],
                    'admin.proposals.index' => ['Proposals', 'admin/proposals'],
                    'admin.contracts.index' => ['Contracts', 'admin/contracts'],
                    'admin.chats.index' => ['Chats', 'admin/chats'],
                    'admin.submissions.index' => ['Submissions', 'admin/submissions'],
                    'admin.payments.index' => ['Payments', 'admin/payments'],
                    'admin.withdrawals.index' => ['Withdrawals', 'admin/withdrawals'],
                    'admin.reviews.index' => ['Reviews', 'admin/reviews'],
                    'admin.conflicts.index' => ['Conflicts', 'admin/conflicts'],
                    'admin.notifications.index' => ['Notifications', 'admin/notifications'],
                ];
            @endphp
            @foreach($links as $route => $meta)
                <a href="{{ route($route) }}"
                   class="block px-5 py-2.5 rounded-r-full mr-3 {{ request()->routeIs($route) || request()->routeIs(str_replace('.index','.*',$route)) ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800 text-gray-300' }}">
                    {{ $meta[0] }}
                </a>
            @endforeach
        </nav>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col">
        <header class="bg-white border-b px-6 py-4 flex items-center justify-between">
            <h1 class="text-xl font-semibold">@yield('title', 'Dashboard')</h1>
            <div class="flex items-center gap-4">
                @auth
                    <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm text-red-600 hover:underline">Logout</button>
                    </form>
                @endauth
            </div>
        </header>

        <main class="flex-1 p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-100 text-green-800 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 text-red-800 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 bg-red-100 text-red-800 px-4 py-3 rounded-lg text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
