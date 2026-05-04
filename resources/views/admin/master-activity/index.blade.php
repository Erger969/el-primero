@extends('layouts.admin')

@section('header_title', 'Actividad de Masters')

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden animate-fade-in">
    <div class="p-8">
        <div class="mb-8">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <x-heroicon-o-shield-check class="w-6 h-6 text-indigo-500" />
                Monitor de Moderación
            </h3>
            <p class="text-sm text-slate-500">Supervisa las acciones realizadas por los usuarios con rango Master.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                        <th class="pb-4 px-4">Master</th>
                        <th class="pb-4 px-4">Publicación Afectada</th>
                        <th class="pb-4 px-4 text-center">Acción</th>
                        <th class="pb-4 px-4">Fecha</th>
                        <th class="pb-4 px-4 text-right">Control</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                    @forelse($activities as $activity)
                    <tr class="group hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors">
                        <td class="py-5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center font-bold text-indigo-600">
                                    {{ strtoupper(substr($activity->master->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $activity->master->name }} {{ $activity->master->lastname }}</p>
                                    <p class="text-[10px] text-slate-500">{{ $activity->master->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-4">
                            <a href="{{ route('posts.show', $activity->post) }}" target="_blank" class="text-sm font-bold text-blue-500 hover:underline flex items-center gap-1">
                                {{ Str::limit($activity->post->title, 35) }}
                                <x-heroicon-o-arrow-top-right-on-square class="w-3 h-3" />
                            </a>
                        </td>
                        <td class="py-5 px-4 text-center">
                            @if($activity->action == 'hide')
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 flex items-center justify-center gap-1">
                                    <x-heroicon-s-lock-closed class="w-3 h-3" />
                                    Ocultó
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 flex items-center justify-center gap-1">
                                    <x-heroicon-s-eye class="w-3 h-3" />
                                    Mostró
                                </span>
                            @endif
                        </td>
                        <td class="py-5 px-4">
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-medium">{{ $activity->created_at->format('d/m/Y') }}</p>
                            <p class="text-[10px] text-slate-400">{{ $activity->created_at->format('H:i') }}</p>
                        </td>
                        <td class="py-5 px-4">
                            <div class="flex justify-end">
                                <form action="{{ route('admin.master-activity.revoke', $activity->master_id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('¿Revocar permisos de Master? Volverá a ser Universitario.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white text-[10px] font-black uppercase rounded-xl transition-all flex items-center gap-2">
                                        <x-heroicon-o-no-symbol class="w-4 h-4" />
                                        Revocar Master
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-slate-200">
                                    <x-heroicon-o-document-magnifying-glass class="w-10 h-10" />
                                </div>
                                <p class="text-slate-500 font-medium italic">No hay actividades de moderación registradas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection