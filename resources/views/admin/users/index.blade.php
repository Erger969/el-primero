@extends('layouts.admin')

@section('header_title', 'Gestión de Usuarios')

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
    <div class="p-8">
        <!-- Filtros y Búsqueda -->
        <div class="mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Buscar</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                        </span>
                        <input type="text" name="search" placeholder="Nombre, apellido o email..." 
                               value="{{ request('search') }}"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-blue-500 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Rol</label>
                    <select name="role" class="w-full py-2.5 bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-blue-500 transition-all cursor-pointer">
                        <option value="">Todos los roles</option>
                        @foreach($roles as $key => $role)
                            <option value="{{ $key }}" {{ request('role') == $key ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Carrera</label>
                    <select name="career" class="w-full py-2.5 bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-blue-500 transition-all cursor-pointer">
                        <option value="">Todas las carreras</option>
                        @foreach($careers as $career)
                            <option value="{{ $career->id }}" {{ request('career') == $career->id ? 'selected' : '' }}>
                                {{ $career->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                        <x-heroicon-o-funnel class="w-5 h-5" />
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de usuarios -->
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                        <th class="pb-4 px-4">Usuario</th>
                        <th class="pb-4 px-4">Contacto</th>
                        <th class="pb-4 px-4">Carrera</th>
                        <th class="pb-4 px-4 text-center">Rol</th>
                        <th class="pb-4 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                    @foreach($users as $user)
                    <tr class="group hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                        <td class="py-5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-600 dark:text-slate-300">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $user->name }} {{ $user->lastname }}</p>
                                    <p class="text-[10px] text-slate-500">ID: #{{ $user->id }} • Registrado el {{ $user->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-4 text-sm">
                            <a href="mailto:{{ $user->email }}" class="text-blue-500 hover:underline flex items-center gap-1">
                                <x-heroicon-o-envelope class="w-4 h-4" />
                                {{ $user->email }}
                            </a>
                        </td>
                        <td class="py-5 px-4">
                            <span class="text-xs font-medium text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-lg">
                                {{ $user->career->nombre ?? 'Sin carrera' }}
                            </span>
                        </td>
                        <td class="py-5 px-4 text-center">
                            @php
                                $badgeClasses = [
                                    3 => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400 border-rose-200 dark:border-rose-800',
                                    2 => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 border-purple-200 dark:border-purple-800',
                                    4 => 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-400 border-slate-300 dark:border-slate-600',
                                    1 => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
                                ];
                                $currentBadge = $badgeClasses[$user->role_id] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase border {{ $currentBadge }}">
                                {{ $roles[$user->role_id] ?? 'Desconocido' }}
                            </span>
                        </td>
                        <td class="py-5 px-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                   class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all"
                                   title="Editar usuario">
                                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                                </a>
                                
                                @if($user->role_id == 4)
                                    <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="p-2 text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-all" title="Restaurar">
                                            <x-heroicon-o-arrow-path class="w-5 h-5" />
                                        </button>
                                    </form>
                                @elseif($user->role_id != 3)
                                    <div x-data="{ open: false }" class="relative inline-block text-left">
                                        <button @click="open = !open" class="p-2 text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-all" title="Suspender">
                                            <x-heroicon-o-no-symbol class="w-5 h-5" />
                                        </button>
                                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 z-50 p-3 animate-fade-in">
                                            <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-2">Duración</p>
                                                <div class="space-y-1 mb-3">
                                                    @foreach(['1' => '1 día', '3' => '3 días', '7' => '7 días', '30' => '30 días', 'permanent' => 'Permanente'] as $val => $text)
                                                        <label class="flex items-center gap-2 text-xs p-1.5 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer">
                                                            <input type="radio" name="duration" value="{{ $val }}" {{ $val == '1' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500 border-slate-300">
                                                            {{ $text }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                                <button type="submit" class="w-full py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg transition-colors">
                                                    Suspender
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($user->role_id != 3)
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este usuario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-all" title="Eliminar">
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection