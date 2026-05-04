@extends('layouts.admin')

@section('header_title', 'Control de Suspensiones')

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
    <div class="p-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Usuarios Sancionados</h3>
                <p class="text-sm text-slate-500">Listado de cuentas en modo lectura con fecha de expiración.</p>
            </div>
            
            <form method="GET" class="w-full md:w-auto">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                    </span>
                    <input type="text" name="search" placeholder="Buscar por nombre..." 
                           value="{{ request('search') }}"
                           class="w-full md:w-72 pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-rose-500 transition-all">
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                        <th class="pb-4 px-4">Usuario</th>
                        <th class="pb-4 px-4">Sanción</th>
                        <th class="pb-4 px-4 text-center">Publicaciones</th>
                        <th class="pb-4 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                    @forelse($users as $user)
                    <tr class="group hover:bg-rose-50/30 dark:hover:bg-rose-900/10 transition-colors">
                        <td class="py-5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-400">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $user->name }} {{ $user->lastname }}</p>
                                    <p class="text-[10px] text-slate-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-4">
                            @if($user->suspended_until)
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-rose-600 dark:text-rose-400">Temporal</span>
                                    <span class="text-[10px] text-slate-500 flex items-center gap-1">
                                        <x-heroicon-o-clock class="w-3 h-3" />
                                        Expira: {{ $user->suspended_until->format('d/m/Y H:i') }}
                                    </span>
                                    <span class="text-[9px] text-slate-400 italic">({{ $user->suspended_until->diffForHumans() }})</span>
                                </div>
                            @else
                                <span class="text-xs font-black text-rose-700 dark:text-rose-500 uppercase tracking-wider">Permanente</span>
                            @endif
                        </td>
                        <td class="py-5 px-4 text-center">
                            <a href="{{ route('admin.posts.index', ['user_id' => $user->id]) }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-500 hover:underline">
                                {{ $user->posts->count() }} posts
                                <x-heroicon-o-arrow-top-right-on-square class="w-3 h-3" />
                            </a>
                        </td>
                        <td class="py-5 px-4">
                            <div class="flex justify-end gap-2">
                                <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" onsubmit="return confirm('¿Restaurar acceso total a este usuario?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase rounded-lg shadow-sm transition-all flex items-center gap-2">
                                        <x-heroicon-o-arrow-path class="w-4 h-4" />
                                        Levantar Sanción
                                    </button>
                                </form>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all">
                                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-slate-900 flex items-center justify-center text-slate-200">
                                    <x-heroicon-o-check-badge class="w-10 h-10" />
                                </div>
                                <p class="text-slate-500 font-medium">No hay usuarios suspendidos actualmente.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
