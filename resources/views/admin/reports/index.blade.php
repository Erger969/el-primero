@extends('layouts.admin')

@section('header_title', 'Bandeja de Reportes')

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden animate-fade-in">
    <div class="p-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-rose-500" />
                    Moderación de Contenido
                </h3>
                <p class="text-sm text-slate-500">Revisa y gestiona las denuncias realizadas por la comunidad.</p>
            </div>
            
            <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-900 p-1 rounded-xl">
                <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 rounded-lg text-xs font-bold {{ !request('status') || request('status') == 'pending' ? 'bg-white dark:bg-slate-800 shadow-sm text-blue-600' : 'text-slate-500 hover:text-slate-700' }}">Pendientes</a>
                <a href="{{ route('admin.reports.index', ['status' => 'resolved']) }}" class="px-4 py-2 rounded-lg text-xs font-bold {{ request('status') == 'resolved' ? 'bg-white dark:bg-slate-800 shadow-sm text-blue-600' : 'text-slate-500 hover:text-slate-700' }}">Resueltos</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                        <th class="pb-4 px-4">Reportado por</th>
                        <th class="pb-4 px-4">Publicación</th>
                        <th class="pb-4 px-4">Motivo</th>
                        <th class="pb-4 px-4 text-center">Estado</th>
                        <th class="pb-4 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                    @forelse($reports as $report)
                    <tr class="group hover:bg-rose-50/30 dark:hover:bg-rose-900/10 transition-colors">
                        <td class="py-5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-400">
                                    {{ substr($report->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $report->user->name }} {{ $report->user->lastname }}</p>
                                    <p class="text-[10px] text-slate-500">{{ $report->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-4">
                            <div class="max-w-xs">
                                <a href="{{ route('posts.show', $report->post) }}" target="_blank" class="text-sm font-bold text-blue-500 hover:underline flex items-center gap-1">
                                    {{ Str::limit($report->post->title, 40) }}
                                    <x-heroicon-o-arrow-top-right-on-square class="w-3 h-3" />
                                </a>
                                <p class="text-[10px] text-slate-500 mt-1">Autor: {{ $report->post->user->name }}</p>
                            </div>
                        </td>
                        <td class="py-5 px-4">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                                @switch($report->category)
                                    @case('spam') Spam @break
                                    @case('acoso') Acoso @break
                                    @case('ofensivo') Ofensivo @break
                                    @case('desinformacion') Desinformación @break
                                    @default Otro
                                @endswitch
                            </span>
                        </td>
                        <td class="py-5 px-4 text-center">
                            @if($report->status == 'pending')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    Pendiente
                                </span>
                            @elseif($report->status == 'resolved')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                    Resuelto
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400">
                                    Rechazado
                                </span>
                            @endif
                        </td>
                        <td class="py-5 px-4">
                            <div class="flex justify-end gap-2">
                                @if($report->status == 'pending')
                                    <form action="{{ route('admin.reports.hide-post', $report->id) }}" method="POST" onsubmit="return confirm('¿Ocultar esta publicación?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="p-2 text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-all" title="Ocultar Post">
                                            <x-heroicon-o-eye-slash class="w-5 h-5" />
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.reports.resolve', $report->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="p-2 text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-all" title="Marcar como Resuelto">
                                            <x-heroicon-o-check-circle class="w-5 h-5" />
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.reports.reject', $report->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-all" title="Rechazar Reporte">
                                            <x-heroicon-o-x-circle class="w-5 h-5" />
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[10px] font-bold text-slate-400 uppercase italic">Procesado</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-slate-200">
                                    <x-heroicon-o-shield-check class="w-10 h-10" />
                                </div>
                                <p class="text-slate-500 font-medium">No hay reportes para mostrar.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection