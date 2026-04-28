<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen p-4 bg-gradient-to-br from-background to-surface dark:from-dark-background dark:to-dark-surface">
        <div class="w-full max-w-md p-8 bg-white border border-gray-100 shadow-2xl dark:bg-dark-surface rounded-2xl animate-fade-in dark:border-gray-800">
            <!-- Logo o ícono -->
            <div class="mb-8 text-center">
                <div class="flex items-center justify-center w-20 h-20 mx-auto shadow-lg bg-gradient-to-br from-primary to-secondary rounded-2xl animate-float">
                    <span class="text-4xl">🦅</span>
                </div>
                <h1 class="mt-4 text-2xl font-bold text-primary dark:text-dark-primary">UniSocial</h1>
                <p class="mt-1 text-text-secondary dark:text-dark-text-secondary">Red social universitaria</p>
            </div>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Email -->
                <div class="mb-5">
                    <label class="block mb-2 font-semibold text-text-primary dark:text-dark-text-primary">Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-3 transition-all duration-200 border border-gray-200 rounded-xl dark:border-gray-700 bg-gray-50 dark:bg-dark-background text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-secondary focus:border-transparent">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
                </div>
                
                <!-- Contraseña -->
                <div class="mb-5">
                    <label class="block mb-2 font-semibold text-text-primary dark:text-dark-text-primary">Contraseña</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 transition-all duration-200 border border-gray-200 rounded-xl dark:border-gray-700 bg-gray-50 dark:bg-dark-background text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-secondary focus:border-transparent">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
                </div>
                
                <!-- Recordar y Olvidó contraseña -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 transition border-gray-300 rounded text-secondary focus:ring-secondary focus:ring-2">
                        <span class="ml-2 text-sm text-text-secondary dark:text-dark-text-secondary">Recordarme</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm font-medium transition duration-200 text-secondary hover:text-primary">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
                
                <!-- Botón de inicio de sesión -->
                <button type="submit" class="w-full px-4 py-3 font-bold text-white transition-all duration-300 transform shadow-md bg-gradient-to-r from-primary to-secondary hover:from-secondary hover:to-primary rounded-xl hover:scale-105">
                    Iniciar sesión
                </button>
                
                <!-- Enlace a registro -->
                <p class="mt-6 text-center text-text-secondary dark:text-dark-text-secondary">
                    ¿No tienes cuenta?
                    <a href="{{ route('register') }}" class="ml-1 font-semibold transition duration-200 text-secondary hover:text-primary">
                        Regístrate
                    </a>
                </p>
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