@extends('layouts.admin')

@section('header_title', 'Registro de Actividad')

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
    <div class="p-8">
        <div class="mb-8">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Auditoría de Administradores</h3>
            <p class="text-sm text-slate-500">Historial de acciones realizadas por el equipo de administración.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                        <th class="pb-4 px-4">Fecha</th>
                        <th class="pb-4 px-4">Admin</th>
                        <th class="pb-4 px-4">Acción</th>
                        <th class="pb-4 px-4">Objetivo</th>
                        <th class="pb-4 px-4 text-right">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                    @forelse($logs as $log)
                    <tr class="text-sm">
                        <td class="py-4 px-4 text-slate-500 font-mono text-xs">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ $log->user->name }}</span>
                        </td>
                        <td class="py-4 px-4">
                            @php
                                $actionColors = [
                                    'comment_deleted' => 'bg-rose-100 text-rose-700',
                                    'post_hidden' => 'bg-amber-100 text-amber-700',
                                    'user_suspended' => 'bg-rose-100 text-rose-700',
                                    'user_restored' => 'bg-emerald-100 text-emerald-700',
                                ];
                                $color = $actionColors[$log->action] ?? 'bg-slate-100 text-slate-700';
                            @endphp
                            <span class="px-2 py-1 rounded text-[10px] font-black uppercase {{ $color }}">
                                {{ str_replace('_', ' ', $log->action) }}
                            </span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-xs text-slate-500 italic">
                                {{ $log->model_type ? basename($log->model_type) : 'Sistema' }} #{{ $log->model_id }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-right text-xs text-slate-400 font-mono">
                            {{ $log->ip_address }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-500">No hay registros de actividad todavía.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
