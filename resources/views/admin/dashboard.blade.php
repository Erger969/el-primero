@extends('layouts.admin')

@section('header_title', 'Dashboard Estadístico')

@section('content')
<div class="space-y-8 animate-fade-in">
    
    <!-- KPI Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-admin-stat-card title="Usuarios Totales" :value="$totalUsers" icon="o-users" color="blue" />
        <x-admin-stat-card title="Publicaciones" :value="$totalPosts" icon="o-document-text" color="emerald" />
        <x-admin-stat-card title="Reportes Pendientes" :value="$totalReports" icon="o-exclamation-triangle" color="rose" :pulse="$totalReports > 0" />
        <x-admin-stat-card title="Solicitudes Master" :value="$totalMasterRequests" icon="o-star" color="amber" :pulse="$totalMasterRequests > 0" />
    </div>

    <!-- Middle Section: Visuals & Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Activity Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700">
            <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                <x-heroicon-o-chart-bar class="w-5 h-5 text-blue-500" />
                Actividad de Publicaciones (Últimos 7 días)
            </h3>
            <div class="h-64">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        <!-- Career Distribution Chart -->
        <div class="lg:col-span-1 bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700">
            <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                <x-heroicon-o-chart-pie class="w-5 h-5 text-purple-500" />
                Carreras Activas
            </h3>
            <div class="h-64">
                <canvas id="careerChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Analytics Section: Viral & Logs -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Viral Posts Ranking -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700">
            <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                <x-heroicon-o-fire class="w-5 h-5 text-orange-500" />
                Publicaciones más Virales
            </h3>
            <div class="space-y-4">
                @foreach($viralPosts as $post)
                    <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 font-bold">
                                {{ $loop->iteration }}
                            </div>
                            <div class="max-w-[200px]">
                                <p class="text-sm font-bold truncate">{{ $post->title }}</p>
                                <p class="text-[10px] text-slate-500">Por: {{ $post->user->name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 text-xs font-bold text-slate-400">
                            <span class="flex items-center gap-1"><x-heroicon-s-heart class="w-3 h-3 text-rose-500"/> {{ $post->reactions_count }}</span>
                            <span class="flex items-center gap-1"><x-heroicon-s-chat-bubble-left class="w-3 h-3 text-blue-500"/> {{ $post->comments_count }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Admin Activity Logs -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <x-heroicon-o-clipboard-document-list class="w-5 h-5 text-indigo-500" />
                    Actividad de Administradores
                </h3>
                <a href="{{ route('admin.logs.index') }}" class="text-xs text-blue-500 hover:underline">Ver bitácora</a>
            </div>
            <div class="space-y-4">
                @foreach($recentLogs as $log)
                    <div class="flex items-center gap-3 p-2 border-b border-slate-50 dark:border-slate-800 last:border-0">
                        <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                        <div class="flex-1">
                            <p class="text-xs">
                                <span class="font-bold">{{ $log->user->name }}</span>
                                <span class="text-slate-500">realizó</span>
                                <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ str_replace('_', ' ', $log->action) }}</span>
                            </p>
                            <p class="text-[10px] text-slate-400">{{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Moderation Section -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        <!-- Reportes Recientes -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-rose-500" />
                    Reportes de Contenido
                </h3>
                <a href="{{ route('admin.reports.index') }}" class="text-xs text-blue-500 hover:underline">Ver todos</a>
            </div>
            <div class="space-y-4">
                @forelse($recentReports as $report)
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center text-rose-600">
                                <x-heroicon-s-shield-exclamation class="w-4 h-4" />
                            </div>
                            <div>
                                <p class="text-xs font-bold">{{ Str::limit($report->post->title ?? 'Post eliminado', 30) }}</p>
                                <p class="text-[10px] text-slate-500">Por: {{ $report->user->name }} • {{ $report->reason }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.reports.index') }}" class="p-1.5 text-slate-400 hover:text-rose-500 transition-colors">
                            <x-heroicon-o-chevron-right class="w-5 h-5" />
                        </a>
                    </div>
                @empty
                    <p class="text-center text-xs text-slate-500 py-4 italic">No hay reportes pendientes.</p>
                @endforelse
            </div>
        </div>

        <!-- Solicitudes Master -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <x-heroicon-o-star class="w-5 h-5 text-amber-500" />
                    Solicitudes de Master
                </h3>
                <a href="{{ route('admin.master-requests.index') }}" class="text-xs text-blue-500 hover:underline">Ver todas</a>
            </div>
            <div class="space-y-4">
                @forelse($recentMasterRequests as $request)
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 font-bold text-[10px]">
                                {{ substr($request->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-xs font-bold">{{ $request->user->name }} {{ $request->user->lastname }}</p>
                                <p class="text-[10px] text-slate-500">{{ $request->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.master-requests.index') }}" class="px-3 py-1 bg-amber-500 hover:bg-amber-600 text-white text-[10px] font-bold rounded-lg transition-colors">
                            Revisar
                        </a>
                    </div>
                @empty
                    <p class="text-center text-xs text-slate-500 py-4 italic">No hay solicitudes pendientes.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Configuración común de Dark Mode para gráficos
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#94a3b8' : '#64748b';
    const gridColor = isDark ? '#1e293b' : '#f1f5f9';

    // Gráfico de Actividad Semanal
    const ctxActivity = document.getElementById('activityChart').getContext('2d');
    new Chart(ctxActivity, {
        type: 'line',
        data: {
            labels: @json($days),
            datasets: [{
                label: 'Publicaciones',
                data: @json($postsPerDay),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: '#3b82f6',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor },
                    ticks: { color: textColor, stepSize: 1 }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: textColor }
                }
            }
        }
    });

    // Gráfico de Carreras
    const ctxCareer = document.getElementById('careerChart').getContext('2d');
    new Chart(ctxCareer, {
        type: 'doughnut',
        data: {
            labels: @json($usersByCareer->pluck('career.nombre')),
            datasets: [{
                data: @json($usersByCareer->pluck('total')),
                backgroundColor: [
                    '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'
                ],
                borderWidth: 0,
                cutout: '70%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        color: textColor,
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 11 }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection