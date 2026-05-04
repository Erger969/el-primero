@extends('layouts.admin')

@section('header_title', 'Editar Carrera')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden animate-fade-in">
        <div class="p-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">
                    <x-heroicon-o-academic-cap class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">{{ $career->nombre }}</h3>
                    <p class="text-xs text-slate-500">ID Carrera: #{{ $career->id }}</p>
                </div>
            </div>

            <form action="{{ route('admin.careers.update', $career->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="nombre" class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nombre de la Carrera</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $career->nombre) }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-blue-500 transition-all">
                    @error('nombre') <p class="text-rose-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="facultad" class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Facultad / Unidad Académica</label>
                    <input type="text" name="facultad" id="facultad" value="{{ old('facultad', $career->facultad) }}"
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-blue-500 transition-all">
                    @error('facultad') <p class="text-rose-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-50 dark:border-slate-700/50">
                    <a href="{{ route('admin.careers.index') }}" class="px-6 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-700 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                        <x-heroicon-o-check class="w-5 h-5" />
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection