<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recuperar Contraseña - UniSocial</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 text-white overflow-x-hidden overflow-y-auto min-h-screen flex items-center justify-center py-12">

    <!-- Background Video -->
    <video autoplay loop muted playsinline class="hero-video">
        <source src="{{ asset('videos/video_UPEA_4k.mp4') }}" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>

    <div class="relative z-10 w-full max-w-md px-4 mx-auto animate-fade-in">
        
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-[#1A3C5E] to-[#C4A35A] rounded-2xl shadow-lg hover:rotate-12 transition-all duration-300 mb-4 mx-auto">
                <x-heroicon-s-academic-cap class="w-10 h-10 text-white" />
            </a>
            <h1 class="text-3xl font-bold tracking-tight text-white mb-2">Recuperar Contraseña</h1>
            <p class="text-gray-300">Ingresa tu correo electrónico y te enviaremos un código numérico de verificación de 6 dígitos.</p>
        </div>

        <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-8 rounded-3xl shadow-2xl">
            
            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 text-green-400 text-sm font-medium p-3 bg-green-500/20 rounded-lg border border-green-500/30">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-200 mb-2">Correo Electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-heroicon-o-envelope class="w-5 h-5 text-gray-400" />
                        </div>
                        <input id="email" class="w-full pl-10 pr-4 py-3 bg-slate-800/50 border border-gray-600 rounded-xl focus:ring-2 focus:ring-[#C4A35A] focus:border-transparent text-white transition-all placeholder-gray-400" 
                               type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="tu@correo.com">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-gradient-to-r from-[#1A3C5E] to-[#2A6B9E] hover:from-[#C4A35A] hover:to-[#D4B06A] text-white font-bold rounded-xl shadow-lg transition-all duration-300 hover:-translate-y-1">
                        <x-heroicon-o-paper-airplane class="w-5 h-5" /> Enviar Código
                    </button>
                </div>
                
                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition-colors">
                        ← Volver a Iniciar Sesión
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
