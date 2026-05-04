@extends('layouts.admin')

@section('header_title', 'Editar Perfil de Usuario')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden animate-fade-in">
        <div class="p-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-16 h-16 rounded-2xl bg-blue-500 flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-blue-500/20">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">{{ $user->name }} {{ $user->lastname }}</h3>
                    <p class="text-sm text-slate-500">Editando información de cuenta #{{ $user->id }}</p>
                </div>
            </div>

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nombre</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-blue-500 transition-all">
                        @error('name') <p class="text-rose-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="lastname" class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Apellidos</label>
                        <input type="text" name="lastname" id="lastname" value="{{ old('lastname', $user->lastname) }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-blue-500 transition-all">
                        @error('lastname') <p class="text-rose-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Correo Electrónico</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-blue-500 transition-all">
                    @error('email') <p class="text-rose-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="career_id" class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Carrera</label>
                        <select name="career_id" id="career_id" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-blue-500 transition-all cursor-pointer">
                            <option value="">Sin carrera</option>
                            @foreach($careers as $career)
                                <option value="{{ $career->id }}" {{ old('career_id', $user->career_id) == $career->id ? 'selected' : '' }}>
                                    {{ $career->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="role_id" class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Rol en la plataforma</label>
                        <select name="role_id" id="role_id" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-blue-500 transition-all cursor-pointer">
                            @foreach($roles as $key => $role)
                                <option value="{{ $key }}" {{ old('role_id', $user->role_id) == $key ? 'selected' : '' }}>
                                    {{ $role }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id') <p class="text-rose-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-slate-900/50 p-6 rounded-2xl border border-slate-100 dark:border-slate-800">
                    <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                        <x-heroicon-o-key class="w-4 h-4 text-amber-500" />
                        Seguridad (Opcional)
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nueva Contraseña</label>
                            <input type="password" name="password" id="password" placeholder="Dejar vacío para no cambiar"
                                   class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all">
                            @error('password') <p class="text-rose-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4">
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-700 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition-all transform hover:-translate-y-0.5">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection