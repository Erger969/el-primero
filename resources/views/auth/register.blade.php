<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen p-4 bg-gradient-to-br from-background to-surface dark:from-dark-background dark:to-dark-surface">
        <div class="w-full max-w-md p-8 bg-white border border-gray-100 shadow-2xl dark:bg-dark-surface rounded-2xl animate-fade-in dark:border-gray-800">
            <!-- Logo o ícono -->
            <div class="mb-6 text-center">
                <div class="flex items-center justify-center w-16 h-16 mx-auto shadow-lg bg-gradient-to-br from-primary to-secondary rounded-2xl animate-float">
                    <span class="text-3xl">🦅</span>
                </div>
                <h1 class="mt-3 text-2xl font-bold text-primary dark:text-dark-primary">Crear cuenta</h1>
                <p class="text-sm text-text-secondary dark:text-dark-text-secondary">Únete a la comunidad universitaria</p>
            </div>
            
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Nombre -->
                <div class="mb-4">
                    <label class="block mb-2 font-semibold text-text-primary dark:text-dark-text-primary">Nombre</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full px-4 py-3 transition-all duration-200 border border-gray-200 rounded-xl dark:border-gray-700 bg-gray-50 dark:bg-dark-background text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-secondary focus:border-transparent">
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Apellidos -->
                <div class="mb-4">
                    <label class="block mb-2 font-semibold text-text-primary dark:text-dark-text-primary">Apellidos</label>
                    <input type="text" name="lastname" value="{{ old('lastname') }}" required
                           class="w-full px-4 py-3 transition-all duration-200 border border-gray-200 rounded-xl dark:border-gray-700 bg-gray-50 dark:bg-dark-background text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-secondary focus:border-transparent">
                    <x-input-error :messages="$errors->get('lastname')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block mb-2 font-semibold text-text-primary dark:text-dark-text-primary">Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 transition-all duration-200 border border-gray-200 rounded-xl dark:border-gray-700 bg-gray-50 dark:bg-dark-background text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-secondary focus:border-transparent">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Carrera -->
                <div class="mb-4">
                    <label class="block mb-2 font-semibold text-text-primary dark:text-dark-text-primary">Carrera</label>
                    <select name="career_id" required
                            class="w-full px-4 py-3 transition-all duration-200 border border-gray-200 rounded-xl dark:border-gray-700 bg-gray-50 dark:bg-dark-background text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-secondary focus:border-transparent">
                        <option value="">Selecciona una carrera</option>
                        @foreach(App\Models\Career::all() as $career)
                            <option value="{{ $career->id }}" {{ old('career_id') == $career->id ? 'selected' : '' }}>
                                {{ $career->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('career_id')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Contraseña -->
                <div class="mb-4">
                    <label class="block mb-2 font-semibold text-text-primary dark:text-dark-text-primary">Contraseña</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 transition-all duration-200 border border-gray-200 rounded-xl dark:border-gray-700 bg-gray-50 dark:bg-dark-background text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-secondary focus:border-transparent">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Confirmar Contraseña -->
                <div class="mb-6">
                    <label class="block mb-2 font-semibold text-text-primary dark:text-dark-text-primary">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 transition-all duration-200 border border-gray-200 rounded-xl dark:border-gray-700 bg-gray-50 dark:bg-dark-background text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-secondary focus:border-transparent">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Botones -->
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('login') }}" class="w-1/2 py-3 font-medium text-center transition duration-200 border border-gray-200 text-text-secondary dark:text-dark-text-secondary hover:text-primary rounded-xl dark:border-gray-700 hover:border-primary">
                        ← Volver
                    </a>
                    <button type="submit" class="w-1/2 px-4 py-3 font-bold text-white transition-all duration-300 transform shadow-md bg-gradient-to-r from-primary to-secondary hover:from-secondary hover:to-primary rounded-xl hover:scale-105">
                        Registrarse
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</x-guest-layout>