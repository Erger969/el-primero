@extends('layouts.admin')

@section('header_title', 'Gestión de Carreras')

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
    <div class="p-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <p class="text-sm text-slate-500">Administra las carreras disponibles en la plataforma</p>
            </div>
            <a href="{{ route('admin.careers.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-blue-900/20 transition-all flex items-center gap-2">
                <x-heroicon-o-plus class="w-5 h-5" />
                Nueva Carrera
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                        <th class="pb-4 px-4">ID</th>
                        <th class="pb-4 px-4">Carrera</th>
                        <th class="pb-4 px-4">Facultad</th>
                        <th class="pb-4 px-4 text-center">Usuarios</th>
                        <th class="pb-4 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                    @foreach($careers as $career)
                    <tr class="group hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                        <td class="py-5 px-4 text-sm font-mono text-slate-400">#{{ $career->id }}</td>
                        <td class="py-5 px-4 text-sm font-bold text-slate-800 dark:text-white">
                            {{ $career->nombre }}
                        </td>
                        <td class="py-5 px-4">
                            <span class="text-xs bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 px-2.5 py-1 rounded-lg">
                                {{ $career->facultad ?? 'No especificada' }}
                            </span>
                        </td>
                        <td class="py-5 px-4 text-center">
                            <span class="text-sm font-black text-blue-600 dark:text-blue-400">
                                {{ $career->users_count ?? $career->users()->count() }}
                            </span>
                        </td>
                        <td class="py-5 px-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.careers.edit', $career->id) }}" 
                                   class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all">
                                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                                </a>
                                <form action="{{ route('admin.careers.destroy', $career->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('¿Eliminar esta carrera?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-all">
                                        <x-heroicon-o-trash class="w-5 h-5" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $careers->links() }}
        </div>
    </div>
</div>
@endsection