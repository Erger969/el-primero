<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true',
          activePanel: 1,
          next() { if (this.activePanel < 3) this.activePanel++; },
          prev() { if (this.activePanel > 1) this.activePanel--; }
      }" 
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" 
      :class="darkMode ? 'dark' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UniSocial - Conectando universitarios</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
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
            background: linear-gradient(135deg, rgba(26, 60, 94, 0.85) 0%, rgba(196, 163, 90, 0.75) 100%);
            z-index: 1;
        }
        
        .sticky-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .dark .sticky-nav {
            background-color: rgba(10, 22, 32, 0.8);
        }
        
        .light .nav-btn {
            transition: all 0.3s ease;
            color: black !important;
        }

        .dark .nav-btn {
            transition: all 0.3s ease;
            color: white !important;
        }
        
        .light .nav-btn:hover {
            background-color: #1A3C5E !important;
            color: #C4A35A !important;
        }
        
        .dark .nav-btn:hover {
            background-color: #D4B06A !important;
            color: #2A6B9E !important;
        }
        
        .post-card {
            transition: all 0.3s ease;
            border-radius: 0.75rem;
        }
        
        .post-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            border-radius: 1rem;
        }
        
        .reaction-btn {
            transition: all 0.2s ease;
        }
        
        .reaction-btn:hover {
            transform: scale(1.05);
        }
        
        .reaction-btn:active {
            transform: scale(0.95);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .animate-fade-in { animation: fadeIn 0.6s ease-out; }
        .animate-slide-right { animation: slideInRight 0.5s ease-out; }
    </style>
</head>
<body class="overflow-x-hidden transition-colors duration-300"
      :class="{ 'light': !darkMode, 'dark': darkMode }">
    
    <!-- Video de fondo fijo -->
    <video autoplay loop muted playsinline class="hero-video" style="object-fit: cover; object-position: center 30%;">
        <source src="{{ asset('videos/video_UPEA_4k.mp4') }}" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>
    
    <!-- NAVBAR STICKY -->
    <nav class="relative z-20 border-b sticky-nav border-white/20">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2 transition hover:opacity-80">
                    <div class="flex items-center justify-center w-9 h-9 bg-gradient-to-br from-primary to-secondary rounded-xl">
                        <span class="text-lg text-white">🦅</span>
                    </div>
                    <span class="text-xl font-bold text-slate-800 dark:text-white">UniSocial</span>
                </a>
                
                <div class="flex items-center gap-1">
                    @auth
                        <a href="{{ route('feed') }}" class="px-4 py-2 ml-2 text-white transition-all duration-300 rounded-lg nav-btn bg-white/20 backdrop-blur-sm">
                            📱 Feed
                        </a>
                    @else
                        <button @click="activePanel = 2" class="px-4 py-2 text-white transition-all duration-300 rounded-lg nav-btn bg-white/20 backdrop-blur-sm">
                            Iniciar sesión
                        </button>
                        <button @click="activePanel = 3" class="px-4 py-2 text-white transition-all duration-300 rounded-lg nav-btn bg-white/20 backdrop-blur-sm">
                            Registrarse
                        </button>
                    @endauth
                    
                    <button @click="darkMode = !darkMode" class="p-2 ml-1 transition-all duration-300 rounded-lg nav-btn bg-white/20 backdrop-blur-sm">
                        <span x-show="!darkMode" class="text-yellow-400">🌞</span>
                        <span x-show="darkMode" class="text-gray-300">🌙</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    @if(Route::currentRouteName() == 'home')
    <!-- CARRUSEL DE PANELES -->
    <div class="relative z-10 flex items-center justify-center min-h-[500px] px-4">
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
                        <h1 class="mb-4 text-4xl font-bold md:text-6xl lg:text-7xl">
                            Conecta, Comparte y <span class="text-secondary">Crece</span>
                        </h1>
                        <p class="max-w-2xl mx-auto mb-8 text-lg md:text-xl text-white/90">
                            La red social exclusiva para la comunidad universitaria.
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
                    <a href="{{ route('posts.show', $post) }}" class="block overflow-hidden transition-all duration-300 shadow-md post-card bg-surface dark:bg-dark-surface rounded-xl hover:shadow-xl">
                        @if($post->images && count(json_decode($post->images, true)) > 0)
                            @php $images = json_decode($post->images, true); @endphp
                            <img src="{{ $images[0] }}" alt="{{ $post->title }}" class="object-cover w-full h-40">
                        @else
                            <div class="flex items-center justify-center w-full h-40 bg-gradient-to-br from-primary/20 to-secondary/20">
                                <span class="text-3xl">🦅</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="mb-1 font-bold text-text-primary dark:text-dark-text-primary line-clamp-2">{{ $post->title }}</h3>
                            <div class="flex items-center justify-between mt-2 text-sm text-text-secondary dark:text-dark-text-secondary">
                                <span>👤 {{ $post->user->name }}</span>
                                <div class="flex gap-3">
                                    <span>💬 {{ $post->comments_count }}</span>
                                    <span>❤️ {{ $post->reactions_count }}</span>
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
                <div class="mb-6 overflow-hidden transition-all duration-300 shadow-md post-card bg-surface dark:bg-dark-surface rounded-xl hover:shadow-xl">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 font-bold text-white rounded-full bg-gradient-to-br from-primary to-secondary">
                                    {{ substr($post->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-text-primary dark:text-dark-text-primary">{{ $post->user->name }} {{ $post->user->lastname }}</h3>
                                    <p class="text-xs text-text-secondary dark:text-dark-text-secondary">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{ route('posts.show', $post) }}">
                            <h2 class="mb-2 text-xl font-bold transition text-text-primary dark:text-dark-text-primary hover:text-primary">{{ $post->title }}</h2>
                        </a>
                        <p class="mb-3 text-text-secondary dark:text-dark-text-secondary">{{ Str::limit($post->content, 150) }}</p>
                        
                        @if($post->images)
                            @php $images = json_decode($post->images, true); @endphp
                            @if(is_array($images) && count($images) > 0)
                                <div class="grid grid-cols-2 gap-2 mb-4">
                                    @foreach(array_slice($images, 0, 2) as $image)
                                        <img src="{{ $image }}" alt="Imagen" class="object-cover w-full rounded-lg h-36">
                                    @endforeach
                                </div>
                            @endif
                        @endif
                        
                        <div class="flex flex-wrap gap-2 pt-3 mb-4 border-t">
                            @php
                                $reactionTypes = [
                                    'ya' => ['emoji' => '😊', 'label' => 'Ya', 'color' => 'bg-green-500'],
                                    'ahh' => ['emoji' => '😮', 'label' => 'Ahh', 'color' => 'bg-yellow-500'],
                                    'ehh' => ['emoji' => '🤔', 'label' => 'Ehh', 'color' => 'bg-purple-500'],
                                    'ohh' => ['emoji' => '😲', 'label' => 'Ohh', 'color' => 'bg-red-500'],
                                    'uhh' => ['emoji' => '😅', 'label' => 'Uhh', 'color' => 'bg-blue-500']
                                ];
                                $currentReaction = $userReactions[$post->id]->type ?? null;
                            @endphp
                            @foreach($reactionTypes as $key => $reaction)
                                <button class="reaction-btn px-3 py-1 rounded-full text-sm font-medium transition-all duration-200
                                    {{ $currentReaction === $key ? $reaction['color'] . ' text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}"
                                    data-post-id="{{ $post->id }}" data-type="{{ $key }}">
                                    {{ $reaction['emoji'] }} {{ $reaction['label'] }}
                                    <span class="count-{{ $key }} ml-1">({{ $post->reactions->where('type', $key)->count() }})</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
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
    </main>
    
    <!-- FOOTER -->
    <footer class="relative z-10 py-6 mt-12 bg-primary dark:bg-dark-primary">
        <div class="px-4 mx-auto text-center max-w-7xl">
            <p class="text-sm text-white/70">© {{ date('Y') }} UniSocial - Conectando a la comunidad universitaria</p>
        </div>
    </footer>
    
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        document.querySelectorAll('.reaction-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const postId = this.dataset.postId;
                const type = this.dataset.type;
                
                try {
                    const response = await fetch(`/posts/${postId}/react`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ type: type })
                    });
                    
                    const data = await response.json();
                    
                    for (const [reactionType, count] of Object.entries(data.counts)) {
                        const counter = document.querySelector(`.count-${reactionType}[data-post-id="${postId}"]`);
                        if (counter) counter.textContent = `(${count})`;
                    }
                    
                    const container = this.parentElement;
                    container.querySelectorAll('.reaction-btn').forEach(btn => {
                        btn.classList.remove('bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-red-500', 'bg-blue-500', 'text-white');
                        btn.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-600', 'dark:text-gray-300');
                    });
                    
                    this.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-gray-600', 'dark:text-gray-300');
                    
                    const colors = {
                        'ya': 'bg-green-500',
                        'ahh': 'bg-yellow-500',
                        'ehh': 'bg-purple-500',
                        'ohh': 'bg-red-500',
                        'uhh': 'bg-blue-500'
                    };
                    this.classList.add(colors[type], 'text-white');
                    
                } catch (error) {
                    console.error('Error:', error);
                }
            });
        });
    </script>
</body>
</html>