@extends('layouts.admin')

@section('header_title', 'Solicitudes de Master')

@section('content')
<div class="space-y-8 animate-fade-in" x-data="{ rejectModalOpen: false, requestId: null }">
    
    <!-- Solicitudes Pendientes -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="p-8">
            <div class="mb-8">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <x-heroicon-o-sparkles class="w-6 h-6 text-amber-500" />
                    Solicitudes Pendientes
                </h3>
                <p class="text-sm text-slate-500">Usuarios que aspiran al rango Master para moderar contenido.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                            <th class="pb-4 px-4">Usuario</th>
                            <th class="pb-4 px-4">Carrera</th>
                            <th class="pb-4 px-4 text-center">Publicaciones</th>
                            <th class="pb-4 px-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                        @forelse($requests as $request)
                        <tr class="group hover:bg-amber-50/30 dark:hover:bg-amber-900/10 transition-colors">
                            <td class="py-5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center font-bold text-amber-600">
                                        {{ substr($request->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $request->user->name }} {{ $request->user->lastname }}</p>
                                        <p class="text-[10px] text-slate-500">{{ $request->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5 px-4">
                                <span class="text-sm text-slate-600 dark:text-slate-300">{{ $request->user->career->nombre ?? '—' }}</span>
                                <p class="text-[10px] text-slate-500">{{ $request->created_at->format('d/m/Y H:i') }}</p>
                            </td>
                            <td class="py-5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400">
                                    {{ $request->user->posts()->count() }} posts
                                </span>
                            </td>
                            <td class="py-5 px-4">
                                <div class="flex justify-end gap-2">
                                    <form action="{{ route('admin.master-requests.approve', $request->id) }}" method="POST" onsubmit="return confirm('¿Aprobar como Master?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase rounded-xl shadow-sm transition-all flex items-center gap-2">
                                            <x-heroicon-o-check-badge class="w-4 h-4" />
                                            Aprobar
                                        </button>
                                    </form>
                                    
                                    <button type="button" @click="requestId = {{ $request->id }}; rejectModalOpen = true" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-[10px] font-black uppercase rounded-xl shadow-sm transition-all flex items-center gap-2">
                                        <x-heroicon-o-x-mark class="w-4 h-4" />
                                        Rechazar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-500 font-medium italic">No hay solicitudes pendientes.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $requests->links() }}
            </div>
        </div>
    </div>

    <!-- Historial -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="p-8">
            <div class="mb-8">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <x-heroicon-o-clock class="w-6 h-6 text-slate-400" />
                    Historial de Decisiones
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                            <th class="pb-4 px-4">Usuario</th>
                            <th class="pb-4 px-4 text-center">Estado</th>
                            <th class="pb-4 px-4">Procesado por</th>
                            <th class="pb-4 px-4 text-right">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                        @forelse($history as $item)
                        <tr class="group text-sm">
                            <td class="py-4 px-4 font-bold text-slate-700 dark:text-slate-300">
                                {{ $item->user->name }} {{ $item->user->lastname }}
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($item->status == 'approved')
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Aprobado</span>
                                @else
                                    <div class="flex flex-col items-center">
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400">Rechazado</span>
                                        @if($item->rejection_reason)
                                            <p class="text-[9px] text-slate-400 mt-1 max-w-[150px] truncate" title="{{ $item->rejection_reason }}">"{{ $item->rejection_reason }}"</p>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-slate-500">
                                {{ $item->reviewer->name ?? 'Sistema' }}
                            </td>
                            <td class="py-4 px-4 text-right text-xs text-slate-400">
                                {{ $item->updated_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 italic">No hay historial procesado.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $history->appends(['history_page' => $history->currentPage()])->links() }}
            </div>
        </div>
    </div>

    <!-- Modal para rechazar (Alpine.js) -->
    <template x-if="rejectModalOpen">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-700 w-full max-w-md overflow-hidden animate-zoom-in" @click.away="rejectModalOpen = false">
                <div class="p-8">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Rechazar Solicitud</h3>
                    <p class="text-sm text-slate-500 mb-6">Por favor, indica el motivo del rechazo para informar al usuario.</p>
                    
                    <form :action="`/admin/master-requests/${requestId}/reject`" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Motivo (opcional)</label>
                            <textarea name="rejection_reason" rows="3" placeholder="Ej: No cumple con los criterios de participación..." 
                                      class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-2xl focus:ring-2 focus:ring-rose-500 transition-all resize-none"></textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="rejectModalOpen = false" class="flex-1 px-6 py-3 text-sm font-bold text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-2xl transition-all">
                                Cancelar
                            </button>
                            <button type="submit" class="flex-1 px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-2xl shadow-lg shadow-rose-500/20 transition-all transform hover:-translate-y-0.5">
                                Confirmar Rechazo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection