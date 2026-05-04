<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: true, darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="darkMode ? 'dark' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside 
            :class="sidebarOpen ? 'w-64' : 'w-20'" 
            class="sidebar-transition relative z-20 flex flex-col h-full bg-slate-800 dark:bg-slate-950 text-white shadow-xl overflow-hidden"
        >
            <!-- Logo Area -->
            <div class="flex items-center justify-between h-16 px-6 bg-slate-900">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                        <x-heroicon-s-academic-cap class="w-5 h-5 text-white" />
                    </div>
                    <span x-show="sidebarOpen" x-transition:enter="sidebar-transition" class="text-xl font-bold whitespace-nowrap">UniSocial</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <x-admin-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" icon="o-home" label="Dashboard" />
                
                <div class="pt-4 pb-2">
                    <span x-show="sidebarOpen" class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-2">Gestión</span>
                    <hr x-show="!sidebarOpen" class="border-slate-700 mx-2">
                </div>

                <x-admin-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.index')" icon="o-users" label="Usuarios" />
                <x-admin-nav-link href="{{ route('admin.users.suspended') }}" :active="request()->routeIs('admin.users.suspended')" icon="o-no-symbol" label="Suspensiones" />
                <x-admin-nav-link href="{{ route('admin.careers.index') }}" :active="request()->routeIs('admin.careers.*')" icon="o-academic-cap" label="Carreras" />
                <x-admin-nav-link href="{{ route('admin.posts.index') }}" :active="request()->routeIs('admin.posts.*')" icon="o-document-text" label="Publicaciones" />
                <x-admin-nav-link href="{{ route('admin.comments.index') }}" :active="request()->routeIs('admin.comments.*')" icon="o-chat-bubble-bottom-center-text" label="Comentarios" />
                <x-admin-nav-link href="{{ route('admin.media.index') }}" :active="request()->routeIs('admin.media.*')" icon="o-photo" label="Multimedia" />
                
                <div class="pt-4 pb-2">
                    <span x-show="sidebarOpen" class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-2">Moderación</span>
                    <hr x-show="!sidebarOpen" class="border-slate-700 mx-2">
                </div>

                <x-admin-nav-link href="{{ route('admin.reports.index') }}" :active="request()->routeIs('admin.reports.*')" icon="o-exclamation-triangle" label="Reportes" />
                <x-admin-nav-link href="{{ route('admin.master-requests.index') }}" :active="request()->routeIs('admin.master-requests.*')" icon="o-star" label="Solicitudes Master" />
                <x-admin-nav-link href="{{ route('admin.logs.index') }}" :active="request()->routeIs('admin.logs.index')" icon="o-clipboard-document-list" label="Bitácora de Admins" />
                <x-admin-nav-link href="{{ route('admin.master-activity.index') }}" :active="request()->routeIs('admin.master-activity.*')" icon="o-identification" label="Actividad Masters" />
            </nav>

            <!-- Bottom Toggle -->
            <div class="p-4 bg-slate-900/50">
                <button @click="sidebarOpen = !sidebarOpen" class="flex items-center justify-center w-full p-2 rounded-lg hover:bg-slate-700 transition-colors">
                    <template x-if="sidebarOpen">
                        <x-heroicon-o-chevron-double-left class="w-6 h-6" />
                    </template>
                    <template x-if="!sidebarOpen">
                        <x-heroicon-o-chevron-double-right class="w-6 h-6" />
                    </template>
                </button>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="flex items-center justify-between h-16 px-8 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-sm relative z-10">
                <div class="flex items-center gap-4">
                    <h2 class="text-xl font-semibold text-slate-800 dark:text-white">
                        @yield('header_title', 'Administración')
                    </h2>
                </div>

                <div class="flex items-center gap-6">
                    <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode" class="p-2 text-slate-500 hover:text-blue-500 transition-colors">
                        <template x-if="!darkMode">
                            <x-heroicon-o-moon class="w-6 h-6" />
                        </template>
                        <template x-if="darkMode">
                            <x-heroicon-o-sun class="w-6 h-6" />
                        </template>
                    </button>

                    <!-- User Menu -->
                    <div class="flex items-center gap-3 pl-6 border-l border-slate-200 dark:border-slate-700">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-slate-800 dark:text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500">Administrador</p>
                        </div>
                        <a href="{{ route('profile.show', Auth::id()) }}" class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                <x-heroicon-o-arrow-left-on-rectangle class="w-6 h-6" />
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 rounded-xl flex items-center gap-3">
                        <x-heroicon-s-check-circle class="w-5 h-5" />
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800 rounded-xl flex items-center gap-3">
                        <x-heroicon-s-x-circle class="w-5 h-5" />
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
