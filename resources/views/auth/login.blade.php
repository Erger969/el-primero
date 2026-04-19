<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-background to-surface dark:from-dark-background dark:to-dark-surface p-4">
        <div class="card w-full max-w-md p-8 animate-fade-in">
            <!-- Logo o ícono -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-br from-primary to-secondary rounded-2xl mx-auto flex items-center justify-center animate-float">
                    <span class="text-4xl">🦅</span>
                </div>
                <h1 class="text-2xl font-bold text-primary dark:text-dark-primary mt-4">UniSocial</h1>
                <p class="text-text-secondary dark:text-dark-text-secondary">Red social universitaria</p>
            </div>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-text-primary dark:text-dark-text-primary mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="input">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                
                <div class="mb-4">
                    <label class="block text-text-primary dark:text-dark-text-primary mb-2">Contraseña</label>
                    <input type="password" name="password" required
                           class="input">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-secondary focus:ring-secondary">
                        <span class="ml-2 text-sm text-text-secondary dark:text-dark-text-secondary">Recordarme</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-secondary hover:text-primary transition">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
                
                <button type="submit" class="btn-primary w-full">
                    Iniciar sesión
                </button>
                
                <p class="text-center text-text-secondary dark:text-dark-text-secondary mt-6">
                    ¿No tienes cuenta?
                    <a href="{{ route('register') }}" class="text-secondary hover:text-primary font-semibold transition">
                        Regístrate
                    </a>
                </p>
            </form>
        </div>
    </div>
</x-guest-layout>