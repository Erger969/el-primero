@extends('layouts.admin')

@section('header_title', 'Historial de Recuperación de Contraseñas')

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
    <div class="p-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <x-heroicon-o-key class="w-6 h-6 text-emerald-500" />
                    Solicitudes de Recuperación
                </h3>
                <p class="text-sm text-slate-500">Historial de códigos generados para restablecer contraseñas.</p>
            </div>
            
            <form method="GET" class="w-full md:w-auto">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                    </span>
                    <input type="text" name="search" placeholder="Buscar por email..." 
                           value="{{ request('search') }}"
                           class="w-full md:w-72 pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-emerald-500 transition-all">
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                        <th class="pb-4 px-4">Email</th>
                        <th class="pb-4 px-4">Código Generado</th>
                        <th class="pb-4 px-4 text-center">Estado</th>
                        <th class="pb-4 px-4">Fecha de Solicitud</th>
                        <th class="pb-4 px-4 text-right">Vencimiento</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                    @forelse($logs as $log)
                    <tr class="group text-sm hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                        <td class="py-4 px-4 font-medium text-slate-800 dark:text-slate-200">
                            {{ $log->email }}
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-mono text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded">
                                {{ $log->code }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            @if($log->used)
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                    Utilizado
                                </span>
                            @elseif($log->expires_at->isPast())
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400">
                                    Expirado
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                    Pendiente
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-slate-500">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="py-4 px-4 text-right text-xs">
                            @if($log->expires_at->isFuture())
                                <span class="text-amber-500">{{ $log->expires_at->diffForHumans() }}</span>
                            @else
                                <span class="text-slate-400">Venció el {{ $log->expires_at->format('d/m/Y H:i') }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-500">No hay registros de recuperación de contraseñas.</td>
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
