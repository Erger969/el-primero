<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UniSocial - Conectando universitarios</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #1A3C5E;
            --secondary: #C4A35A;
            --secondary-light: #D4B06A;
        }

        body {
            font-family: 'Outfit', sans-serif;
        }

        .hero-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
        }
        
        .hero-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(10, 22, 32, 0.9) 0%, rgba(26, 60, 94, 0.7) 100%);
            z-index: 1;
        }
        
        .glass-nav {
            backdrop-filter: blur(16px);
            background-color: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .dark .glass-nav {
            background-color: rgba(10, 22, 32, 0.7);
        }
        
        .nav-btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #2A6B9E 100%);
            box-shadow: 0 4px 15px rgba(26, 60, 94, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-light) 100%);
            box-shadow: 0 4px 15px rgba(196, 163, 90, 0.3);
        }

        .post-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        
        .dark .post-card {
            border: 1px solid rgba(51, 65, 85, 0.5);
            background-color: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(8px);
        }
        
        .post-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            border-color: var(--secondary);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in { animation: fadeIn 0.8s ease-out forwards; }
        
        .content-container {
            position: relative;
            z-index: 20;
            padding: 4rem 1rem;
        }

        [x-cloak] { display: none !important; }

        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; }

    </style>
</head>
<body class="overflow-x-hidden transition-colors duration-300"
      x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true',
          activePanel: 1,
          openComments: {},
          next() { if (this.activePanel < 3) this.activePanel++; },
          prev() { if (this.activePanel > 1) this.activePanel--; }
      }" 
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" 
      :class="{ 'light': !darkMode, 'dark': darkMode }">
    
    <!-- Video de fondo fijo -->
    <video autoplay loop muted playsinline class="hero-video" style="object-fit: cover; object-position: center 30%;">
        <source src="{{ asset('videos/video_UPEA_4k.mp4') }}" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>
    
    <!-- NAVBAR STICKY -->
    <nav class="sticky top-0 z-50 glass-nav transition-all duration-500">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <a href="{{ route('home') }}" class="flex items-center gap-3 transition-transform hover:scale-105 group">
                    <div class="flex items-center justify-center w-11 h-11 bg-gradient-to-br from-primary to-secondary rounded-2xl shadow-lg group-hover:rotate-12 transition-all duration-300">
                        <span class="text-xl text-white">🦅</span>
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-white">UniSocial</span>
                </a>
                
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('feed') }}" class="px-5 py-2.5 text-white font-medium rounded-xl btn-primary">
                            📱 Mi Feed
                        </a>
                    @else
                        <button @click="activePanel = 2" 
                                :class="activePanel === 2 ? 'btn-secondary' : 'bg-white/10 hover:bg-white/20'"
                                class="px-5 py-2.5 text-white font-medium rounded-xl transition-all duration-300">
                            Iniciar sesión
                        </button>
                        <button @click="activePanel = 3" 
                                :class="activePanel === 3 ? 'btn-secondary' : 'bg-white/10 hover:bg-white/20'"
                                class="px-5 py-2.5 text-white font-medium rounded-xl transition-all duration-300">
                            Registrarse
                        </button>
                    @endauth
                    
                    <div class="w-px h-6 mx-1 bg-white/20"></div>
                    
                    <button @click="darkMode = !darkMode" class="p-2.5 text-white bg-white/10 hover:bg-white/20 rounded-xl transition-all">
                        <span x-show="!darkMode" class="text-yellow-400 text-xl">🌞</span>
                        <span x-show="darkMode" class="text-gray-300 text-xl">🌙</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    @if(Route::currentRouteName() == 'home')
    <!-- CARRUSEL DE PANELES -->
    <div class="relative z-10 flex items-center justify-center min-h-[700px] px-4">
        <button x-show="activePanel > 1" @click="activePanel--" 
                class="absolute z-30 p-3 transition-all duration-300 rounded-full left-4 md:left-8 bg-white/20 backdrop-blur-sm hover:bg-white/30 hover:scale-110">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        
        <div class="w-full max-w-4xl overflow-hidden">
            <div class="flex transition-transform duration-500 ease-out"
                 :style="'transform: translateX(-' + ((activePanel - 1) * 100) + '%);'">
                
                <!-- PANEL 1: HERO -->
                <div class="flex-shrink-0 w-full px-4">
                    <div class="text-center text-white animate-fade-in">
                        <h1 class="mb-4 text-4xl font-bold md:text-6xl lg:text-7xl leading-tight">
                            Conecta, Comparte y <span class="text-secondary">Crece</span>
                        </h1>
                        <p class="max-w-2xl mx-auto mb-10 text-lg md:text-2xl text-white/80 font-light">
                            La plataforma definitiva para la comunidad universitaria de la <span class="font-bold text-white">UPEA</span>.
                        </p>
                        
                        <div class="w-full max-w-3xl p-2 mx-auto mb-8 bg-white/10 backdrop-blur-md rounded-2xl">
                            <form action="{{ route('feed') }}" method="GET" class="flex flex-col gap-2 md:flex-row">
                                <input type="text" name="search" placeholder="Buscar publicaciones..." 
                                       class="flex-1 px-4 py-3 text-slate-800 bg-white/20 placeholder-white/60 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary">
                                <button type="submit" class="px-6 py-3 font-semibold text-white transition-all duration-300 bg-secondary hover:bg-primary rounded-xl hover:scale-105">
                                    🔍 Buscar
                                </button>
                            </form>
                        </div>
                        
                        <div class="flex flex-wrap justify-center gap-3">
                            <a href="{{ route('feed') }}?type=evento" class="px-4 py-2 transition rounded-full bg-white/10 backdrop-blur-sm hover:bg-secondary">📅 Eventos</a>
                            <a href="{{ route('feed') }}?type=noticia" class="px-4 py-2 transition rounded-full bg-white/10 backdrop-blur-sm hover:bg-secondary">📰 Noticias</a>
                            <a href="{{ route('feed') }}?type=curso" class="px-4 py-2 transition rounded-full bg-white/10 backdrop-blur-sm hover:bg-secondary">📚 Cursos</a>
                            <a href="{{ route('feed') }}?type=aviso" class="px-4 py-2 transition rounded-full bg-white/10 backdrop-blur-sm hover:bg-secondary">⚠️ Avisos</a>
                            <a href="{{ route('feed') }}?sort=most_commented" class="px-4 py-2 transition rounded-full bg-white/10 backdrop-blur-sm hover:bg-secondary">🔥 Tendencias</a>
                        </div>
                    </div>
                </div>
                
                <!-- PANEL 2: LOGIN -->
                <div class="flex-shrink-0 w-full px-4">
                    <div class="max-w-md p-8 mx-auto bg-white shadow-2xl dark:bg-dark-surface rounded-2xl animate-slide-right">
                        <div class="mb-6 text-center">
                            <div class="flex items-center justify-center w-16 h-16 mx-auto shadow-lg bg-gradient-to-br from-primary to-secondary rounded-2xl">
                                <span class="text-3xl">🔐</span>
                            </div>
                            <h2 class="mt-3 text-2xl font-bold text-text-primary dark:text-dark-text-primary">Iniciar sesión</h2>
                        </div>
                        
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-4">
                                <label class="block mb-2 font-semibold text-text-primary dark:text-dark-text-primary">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       class="w-full px-4 py-3 transition border border-gray-200 rounded-xl dark:border-gray-700 bg-gray-50 dark:bg-dark-background focus:ring-2 focus:ring-secondary">
                            </div>
                            
                            <div class="mb-4">
                                <label class="block mb-2 font-semibold text-text-primary dark:text-dark-text-primary">Contraseña</label>
                                <input type="password" name="password" required
                                       class="w-full px-4 py-3 transition border border-gray-200 rounded-xl dark:border-gray-700 bg-gray-50 dark:bg-dark-background focus:ring-2 focus:ring-secondary">
                            </div>
                            
                            <div class="flex items-center justify-between mb-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="remember" class="rounded text-secondary focus:ring-secondary">
                                    <span class="ml-2 text-sm text-text-secondary">Recordarme</span>
                                </label>
                                <a href="{{ route('password.request') }}" class="text-sm transition text-secondary hover:text-primary">¿Olvidaste?</a>
                            </div>
                            
                            <button type="submit" class="w-full py-3 font-bold text-white transition-all duration-300 transform bg-gradient-to-r from-primary to-secondary hover:from-secondary hover:to-primary rounded-xl hover:scale-105">
                                Ingresar
                            </button>
                        </form>
                        
                        <p class="mt-6 text-center text-text-secondary">
                            ¿No tienes cuenta?
                            <button @click="activePanel = 3" class="font-semibold transition text-secondary hover:text-primary">
                                Regístrate
                            </button>
                        </p>
                    </div>
                </div>
                
                <!-- PANEL 3: REGISTER -->
                <div class="flex-shrink-0 w-full px-4">
                    <div class="max-w-md p-8 mx-auto bg-white shadow-2xl dark:bg-dark-surface rounded-2xl animate-slide-right">
                        <div class="mb-6 text-center">
                            <div class="flex items-center justify-center w-16 h-16 mx-auto shadow-lg bg-gradient-to-br from-primary to-secondary rounded-2xl">
                                <span class="text-3xl">✨</span>
                            </div>
                            <h2 class="mt-3 text-2xl font-bold text-text-primary dark:text-dark-text-primary">Crear cuenta</h2>
                        </div>
                        
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block mb-1 text-sm font-semibold text-text-primary dark:text-dark-text-primary">Nombre</label>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                           class="w-full px-3 py-2 transition border border-gray-200 rounded-xl dark:border-gray-700 bg-gray-50 dark:bg-dark-background focus:ring-2 focus:ring-secondary">
                                </div>
                                <div>
                                    <label class="block mb-1 text-sm font-semibold text-text-primary dark:text-dark-text-primary">Apellidos</label>
                                    <input type="text" name="lastname" value="{{ old('lastname') }}" required
                                           class="w-full px-3 py-2 transition border border-gray-200 rounded-xl dark:border-gray-700 bg-gray-50 dark:bg-dark-background focus:ring-2 focus:ring-secondary">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="block mb-1 text-sm font-semibold text-text-primary dark:text-dark-text-primary">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       class="w-full px-3 py-2 transition border border-gray-200 rounded-xl dark:border-gray-700 bg-gray-50 dark:bg-dark-background focus:ring-2 focus:ring-secondary">
                            </div>
                            
                            <div class="mb-3">
                                <label class="block mb-1 text-sm font-semibold text-text-primary dark:text-dark-text-primary">Carrera</label>
                                <select name="career_id" required class="w-full px-3 py-2 transition border border-gray-200 rounded-xl dark:border-gray-700 bg-gray-50 dark:bg-dark-background focus:ring-2 focus:ring-secondary">
                                    <option value="">Selecciona</option>
                                    @foreach(App\Models\Career::all() as $career)
                                        <option value="{{ $career->id }}">{{ $career->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block mb-1 text-sm font-semibold text-text-primary dark:text-dark-text-primary">Contraseña</label>
                                    <input type="password" name="password" required
                                           class="w-full px-3 py-2 transition border border-gray-200 rounded-xl dark:border-gray-700 bg-gray-50 dark:bg-dark-background focus:ring-2 focus:ring-secondary">
                                </div>
                                <div>
                                    <label class="block mb-1 text-sm font-semibold text-text-primary dark:text-dark-text-primary">Confirmar</label>
                                    <input type="password" name="password_confirmation" required
                                           class="w-full px-3 py-2 transition border border-gray-200 rounded-xl dark:border-gray-700 bg-gray-50 dark:bg-dark-background focus:ring-2 focus:ring-secondary">
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full py-3 mt-4 font-bold text-white transition-all duration-300 transform bg-gradient-to-r from-primary to-secondary hover:from-secondary hover:to-primary rounded-xl hover:scale-105">
                                Registrarse
                            </button>
                        </form>
                        
                        <p class="mt-6 text-center text-text-secondary">
                            ¿Ya tienes cuenta?
                            <button @click="activePanel = 2" class="font-semibold transition text-secondary hover:text-primary">
                                Inicia sesión
                            </button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <button x-show="activePanel < 3" @click="activePanel++" 
                class="absolute z-30 p-3 transition-all duration-300 rounded-full right-4 md:right-8 bg-white/20 backdrop-blur-sm hover:bg-white/30 hover:scale-110">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>
    
    <!-- INDICADORES DE PANEL -->
    <div class="relative z-10 flex justify-center gap-2 mt-8 mb-4">
        <button @click="activePanel = 1" class="transition-all duration-300">
            <div class="w-2 h-2 rounded-full" :class="activePanel === 1 ? 'w-6 bg-secondary' : 'bg-white/50'"></div>
        </button>
        <button @click="activePanel = 2" class="transition-all duration-300">
            <div class="w-2 h-2 rounded-full" :class="activePanel === 2 ? 'w-6 bg-secondary' : 'bg-white/50'"></div>
        </button>
        <button @click="activePanel = 3" class="transition-all duration-300">
            <div class="w-2 h-2 rounded-full" :class="activePanel === 3 ? 'w-6 bg-secondary' : 'bg-white/50'"></div>
        </button>
    </div>
    @endif
    
    <!-- CONTENEDOR PRINCIPAL CON Z-INDEX ELEVADO -->
    <div class="relative z-20 bg-background dark:bg-dark-background content-container max-w-7xl mx-auto rounded-t-[3rem] -mt-20 shadow-[0_-20px_50px_-12px_rgba(0,0,0,0.5)]">
        
        <!-- SECCIÓN: ¿QUÉ ES UNISOCIAL? -->
        <section class="mb-20">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="p-8 rounded-3xl bg-surface/50 dark:bg-dark-surface/30 backdrop-blur-sm border border-white/10 hover:border-secondary/50 transition-all duration-300">
                    <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center bg-primary/10 rounded-2xl text-3xl">🤝</div>
                    <h3 class="text-xl font-bold mb-3 text-text-primary dark:text-dark-text-primary">Conecta</h3>
                    <p class="text-text-secondary dark:text-dark-text-secondary text-sm leading-relaxed">
                        Encuentra a compañeros de tu carrera y de toda la universidad. Construye tu red profesional desde hoy.
                    </p>
                </div>
                <div class="p-8 rounded-3xl bg-surface/50 dark:bg-dark-surface/30 backdrop-blur-sm border border-white/10 hover:border-secondary/50 transition-all duration-300">
                    <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center bg-secondary/10 rounded-2xl text-3xl">📢</div>
                    <h3 class="text-xl font-bold mb-3 text-text-primary dark:text-dark-text-primary">Comparte</h3>
                    <p class="text-text-secondary dark:text-dark-text-secondary text-sm leading-relaxed">
                        Publica noticias, eventos, apuntes o avisos importantes. Mantén informada a tu comunidad estudiantil.
                    </p>
                </div>
                <div class="p-8 rounded-3xl bg-surface/50 dark:bg-dark-surface/30 backdrop-blur-sm border border-white/10 hover:border-secondary/50 transition-all duration-300">
                    <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center bg-green-500/10 rounded-2xl text-3xl">🚀</div>
                    <h3 class="text-xl font-bold mb-3 text-text-primary dark:text-dark-text-primary">Crece</h3>
                    <p class="text-text-secondary dark:text-dark-text-secondary text-sm leading-relaxed">
                        Participa en debates, asiste a cursos y mantente al día con las tendencias de tu facultad.
                    </p>
                </div>
            </div>
        </section>
        <!-- TENDENCIAS DE LA SEMANA (3x2) -->
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-text-primary dark:text-dark-text-primary">
                    🔥 <span class="text-secondary">Tendencias</span> de la semana
                </h2>
                <span class="text-sm text-text-secondary dark:text-dark-text-secondary">Lo más comentado y reaccionado</span>
            </div>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                @forelse($trendingPosts as $post)
                    <a href="{{ route('posts.show', $post) }}" class="group block overflow-hidden transition-all duration-500 shadow-lg post-card bg-surface dark:bg-dark-surface rounded-2xl">
                        <div class="relative h-48 overflow-hidden">
                            @if($post->images && count(json_decode($post->images, true)) > 0)
                                @php $images = json_decode($post->images, true); @endphp
                                <img src="{{ $images[0] }}" alt="{{ $post->title }}" class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="flex items-center justify-center w-full h-full bg-gradient-to-br from-primary/30 to-secondary/30 group-hover:scale-110 transition-transform duration-700">
                                    <span class="text-5xl">🦅</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                <span class="text-white text-xs font-semibold px-2 py-1 bg-secondary rounded-lg">Ver tendencia</span>
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="mb-2 font-bold text-lg text-text-primary dark:text-dark-text-primary line-clamp-2 leading-snug group-hover:text-secondary transition-colors">{{ $post->title }}</h3>
                            <div class="flex items-center justify-between mt-4 text-xs font-medium text-text-secondary dark:text-dark-text-secondary">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center text-[10px] text-primary">
                                        {{ substr($post->user->name, 0, 1) }}
                                    </div>
                                    <span>{{ $post->user->name }}</span>
                                </div>
                                <div class="flex gap-3 bg-gray-50 dark:bg-gray-800/50 px-2 py-1 rounded-lg">
                                    <span class="flex items-center gap-1">💬 {{ $post->comments_count }}</span>
                                    <span class="flex items-center gap-1">❤️ {{ $post->reactions_count }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-3 py-8 text-center text-text-secondary">
                        No hay tendencias aún. ¡Sé el primero en publicar!
                    </div>
                @endforelse
            </div>
        </section>
        
        <!-- PUBLICACIONES RECIENTES -->
        <section>
            <h2 class="mb-6 text-2xl font-bold text-text-primary dark:text-dark-text-primary">
                📰 Últimas publicaciones
            </h2>
            
            @forelse($posts as $post)
                <div class="mb-6 shadow-md post-card bg-surface dark:bg-dark-surface rounded-xl">
                    <div class="p-6">
                        {{-- Autor --}}
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex items-center justify-center w-10 h-10 font-bold text-white text-sm rounded-full bg-gradient-to-br from-primary to-secondary flex-shrink-0">
                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-sm text-text-primary dark:text-dark-text-primary">{{ $post->user->name }} {{ $post->user->lastname }}</p>
                                <p class="text-xs text-text-secondary dark:text-dark-text-secondary">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        {{-- Título y contenido --}}
                        <a href="{{ route('posts.show', $post) }}">
                            <h2 class="mb-2 text-xl font-bold text-text-primary dark:text-dark-text-primary hover:text-primary transition-colors">{{ $post->title }}</h2>
                        </a>
                        <p class="mb-4 text-text-secondary dark:text-dark-text-secondary leading-relaxed">{{ Str::limit($post->content, 150) }}</p>

                        {{-- Imágenes --}}
                        @if($post->images)
                            @php $imgs = json_decode($post->images, true); @endphp
                            @if(is_array($imgs) && count($imgs) > 0)
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    @foreach(array_slice($imgs, 0, 2) as $img)
                                        <div class="overflow-hidden rounded-2xl h-60 border border-gray-100 dark:border-gray-800">
                                            <img src="{{ $img }}" alt="Imagen" class="object-cover w-full h-full">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endif

                        {{-- Reacciones --}}
                        @php
                            $reactionTypes = [
                                'ya'  => ['emoji' => '😊', 'label' => 'Ya',  'color' => 'bg-green-500'],
                                'ahh' => ['emoji' => '😮', 'label' => 'Ahh', 'color' => 'bg-yellow-500'],
                                'ehh' => ['emoji' => '🤔', 'label' => 'Ehh', 'color' => 'bg-purple-500'],
                                'ohh' => ['emoji' => '😲', 'label' => 'Ohh', 'color' => 'bg-red-500'],
                                'uhh' => ['emoji' => '😅', 'label' => 'Uhh', 'color' => 'bg-blue-500'],
                            ];
                            $currentReaction = $userReactions[$post->id]->type ?? null;
                        @endphp
                        <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-100 dark:border-gray-800">
                            @foreach($reactionTypes as $key => $reaction)
                                <button class="reaction-btn px-3 py-1 rounded-full text-sm font-medium transition-all duration-200
                                    {{ $currentReaction === $key ? $reaction['color'].' text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}"
                                    data-post-id="{{ $post->id }}" data-type="{{ $key }}">
                                    {{ $reaction['emoji'] }} {{ $reaction['label'] }}
                                    <span class="count-{{ $key }} ml-1" data-post-id="{{ $post->id }}">({{ $post->reactions->where('type', $key)->count() }})</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- ═══════════ SECCIÓN DE COMENTARIOS (HTML nativo, sin JS) ═══════════ --}}
                    <details class="border-t border-gray-100 dark:border-gray-800 group">
                        <summary class="flex items-center gap-2 px-6 py-3 cursor-pointer select-none
                                        text-sm font-medium text-text-secondary dark:text-dark-text-secondary
                                        hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors rounded-b-xl
                                        list-none">
                            <span class="text-base">💬</span>
                            <span>{{ $post->comments->count() }} comentario{{ $post->comments->count() !== 1 ? 's' : '' }}</span>
                            {{-- Flecha giratoria pura CSS --}}
                            <svg class="ml-auto w-4 h-4 transition-transform duration-200 group-open:rotate-180"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>

                        <div class="px-6 pb-6 pt-4">
                            {{-- Lista de comentarios existentes --}}
                            @if($post->comments->count() > 0)
                                <div class="space-y-3 mb-5 max-h-80 overflow-y-auto pr-1 custom-scrollbar">
                                    @foreach($post->comments as $comment)
                                        <div class="flex gap-3">
                                            <div class="flex-shrink-0 flex items-center justify-center w-8 h-8
                                                        text-xs font-bold text-white rounded-full
                                                        bg-gradient-to-br from-primary to-secondary">
                                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                            </div>
                                            <div class="flex-1 bg-gray-50 dark:bg-gray-800/60 rounded-2xl rounded-tl-none px-4 py-2.5
                                                        border border-gray-100 dark:border-gray-700">
                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="text-xs font-bold text-text-primary dark:text-dark-text-primary">
                                                        {{ $comment->user->name }} {{ $comment->user->lastname }}
                                                    </span>
                                                    <span class="text-[10px] text-text-secondary dark:text-dark-text-secondary">
                                                        {{ $comment->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-text-secondary dark:text-dark-text-secondary">{{ $comment->content }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-center text-sm text-text-secondary dark:text-dark-text-secondary mb-4 py-2">
                                    Aún no hay comentarios. ¡Sé el primero! 👇
                                </p>
                            @endif

                            {{-- Formulario para comentar (solo autenticados) --}}
                            @auth
                                <form action="{{ route('comments.store', $post) }}" method="POST">
                                    @csrf
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 flex items-center justify-center w-8 h-8
                                                    text-xs font-bold text-white rounded-full
                                                    bg-gradient-to-br from-primary to-secondary">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 flex items-center gap-2 bg-gray-100 dark:bg-gray-800 rounded-full px-4 py-2">
                                            <input type="text" name="content" required
                                                   placeholder="Escribe un comentario..."
                                                   class="flex-1 bg-transparent text-sm text-text-primary dark:text-dark-text-primary outline-none placeholder-gray-400">
                                            <button type="submit"
                                                    class="text-secondary hover:text-primary transition-colors flex-shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <div class="text-center py-2">
                                    <button onclick="window.scrollTo({top:0,behavior:'smooth'})"
                                            class="text-sm text-secondary hover:text-primary font-semibold transition-colors">
                                        🔐 Inicia sesión para comentar
                                    </button>
                                </div>
                            @endauth
                        </div>
                    </details>
                </div>
            @empty
                <div class="py-12 text-center bg-surface dark:bg-dark-surface rounded-xl">
                    <p class="text-text-secondary">No hay publicaciones aún. ¡Sé el primero en publicar!</p>
                </div>
            @endforelse

            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        </section>

    </div>
    <!-- FIN CONTENEDOR PRINCIPAL -->
    
    <!-- FOOTER -->
    <footer class="relative z-10 py-12 bg-primary/95 dark:bg-dark-primary/90 border-t border-white/5 backdrop-blur-md">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-8 h-8 bg-white/10 rounded-lg">
                        <span class="text-white text-sm">🦅</span>
                    </div>
                    <span class="text-lg font-bold text-white tracking-tight">UniSocial</span>
                </div>
                
                <div class="flex gap-6">
                    <a href="#" class="text-white/60 hover:text-secondary transition-colors text-sm">Privacidad</a>
                    <a href="#" class="text-white/60 hover:text-secondary transition-colors text-sm">Términos</a>
                    <a href="#" class="text-white/60 hover:text-secondary transition-colors text-sm">Ayuda</a>
                </div>
                
                <p class="text-sm text-white/40 font-light">
                    © {{ date('Y') }} UniSocial UPEA. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>
    
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        // La lógica de brillo ahora se maneja automáticamente mediante las clases reactivas de Alpine.js
        // eliminando la necesidad de buscar elementos en el DOM manualmente.
        function activarBrilloLoginRegister() {
            // Esta función se mantiene por compatibilidad si se llama desde otros lugares, 
            // pero el efecto principal ahora es reactivo.
        }
        
        document.querySelectorAll('.reaction-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                @guest
                    // Si no está logueado, mostrar el efecto en los botones de login
                    window.scrollTo({top: 0, behavior: 'smooth'});
                    const loginBtn = document.querySelector('button[activePanel="2"]') || document.querySelector('.btn-secondary') || document.querySelector('button:contains("Iniciar sesión")');
                    // Simplemente cambiamos al panel de login
                    if (window.Alpine) {
                        window.Alpine.store('activePanel', 2); // Si usáramos store, pero no lo hacemos
                        // Como activePanel está en el root, podemos intentar disparar un evento
                        document.dispatchEvent(new CustomEvent('switch-panel', { detail: 2 }));
                    }
                    // En su lugar, el botón ya tiene un @click en el HTML si lo ponemos bien
                    return;
                @endguest

                const postId = this.dataset.postId;
                const type = this.dataset.type;
                
                try {
                    const response = await fetch(`{{ url('/posts') }}/${postId}/react`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ type: type })
                    });
                    
                    if (!response.ok) throw new Error('Network response was not ok');
                    
                    const data = await response.json();
                    
                    if (data && data.counts) {
                        for (const [reactionType, count] of Object.entries(data.counts)) {
                            const counter = document.querySelector(`.count-${reactionType}[data-post-id="${postId}"]`);
                            if (counter) counter.textContent = `(${count})`;
                        }
                        
                        const container = this.parentElement;
                        container.querySelectorAll('.reaction-btn').forEach(btn => {
                            btn.classList.remove('bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-red-500', 'bg-blue-500', 'text-white');
                            btn.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-600', 'dark:text-gray-300');
                        });
                        
                        const colors = {
                            'ya': 'bg-green-500',
                            'ahh': 'bg-yellow-500',
                            'ehh': 'bg-purple-500',
                            'ohh': 'bg-red-500',
                            'uhh': 'bg-blue-500'
                        };
                        this.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-gray-600', 'dark:text-gray-300');
                        this.classList.add(colors[type], 'text-white');
                    }
                    
                } catch (error) {
                    console.error('Error:', error);
                }
            });
        });
        
        // El toggle de comentarios ahora se maneja mediante Alpine.js directamente en el HTML
        // para una respuesta más rápida y limpia.
    </script>
</body>
</html>