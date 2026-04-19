<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" 
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" 
      :class="darkMode ? 'dark' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UniSocial - Conectando universitarios</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Video hero */
        .hero-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(26, 60, 94, 0.85) 0%, rgba(196, 163, 90, 0.75) 100%);
            z-index: 1;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        /* Navbar fijo (sticky) con blur */
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
        
        /* Dropdown menus */
        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
        }
        
        
        .dropdown-trigger:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        /* Efecto hover para tarjetas */
        .post-card {
            transition: all 0.3s ease;
        }
        
        .post-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }
        
        /* Botones de reacción */
        .reaction-btn {
            transition: all 0.2s ease;
        }
        
        .reaction-btn:hover {
            transform: scale(1.05);
        }
        
        .reaction-btn:active {
            transform: scale(0.95);
        }
        
        /* Botones de navbar */
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
        
        /* Animaciones */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in { animation: fadeIn 0.8s ease-out; }
        .animate-slide-up { animation: slideUp 0.6s ease-out; }
    </style>
</head>
<body class="bg-background dark:bg-dark-background text-text-primary dark:text-dark-text-primary transition-colors duration-300"
      :class="{ 'light': !darkMode, 'dark': darkMode }">
    
    <!-- NAVBAR STICKY (siempre visible) -->
    <nav class="sticky-nav border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                    <div class="w-9 h-9 bg-gradient-to-br from-primary to-secondary rounded-xl flex items-center justify-center">
                        <span class="text-white text-lg">🦅</span>
                    </div>
                    <span class="font-bold text-xl text-white">UniSocial</span>
                </a>
                
                <!-- Botones de acción (dropdowns) -->
                <div class="flex items-center gap-1">
                    <!-- Dropdown Carreras -->
                    <div class="dropdown-trigger relative">
                        <button class="nav-btn px-4 py-2 rounded-lg text-white bg-white/20 backdrop-blur-sm transition-all duration-300">
                            📚 Carreras
                        </button>
                        <div class="dropdown-menu absolute top-full left-0 mt-2 w-64 bg-white dark:bg-dark-surface rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 z-50">
                            <div class="p-2">
                                <div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 border-b dark:border-gray-700">
                                    Todas las carreras
                                </div>
                                @foreach(\App\Models\Career::all() as $career)
                                    <a href="{{ route('feed') }}?career_id={{ $career->id }}" 
                                       class="flex justify-between items-center px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                                        <span>{{ $career->nombre }}</span>
                                        <span class="text-xs text-gray-400">{{ $career->users()->count() }} estudiantes</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dropdown Fechas -->
                    <div class="dropdown-trigger relative">
                        <button class="nav-btn px-4 py-2 rounded-lg text-white bg-white/20 backdrop-blur-sm transition-all duration-300">
                            📅 Fechas
                        </button>
                        <div class="dropdown-menu absolute top-full left-0 mt-2 w-48 bg-white dark:bg-dark-surface rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 z-50">
                            <div class="p-2">
                                <a href="{{ route('feed') }}" class="block px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                                    Todas las fechas
                                </a>
                                <a href="{{ route('feed') }}?date_filter=today" class="block px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                                    Hoy
                                </a>
                                <a href="{{ route('feed') }}?date_filter=week" class="block px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                                    Última semana
                                </a>
                                <a href="{{ route('feed') }}?date_filter=month" class="block px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                                    Último mes
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dropdown Ordenar -->
                    <div class="dropdown-trigger relative">
                        <button class="nav-btn px-4 py-2 rounded-lg text-white bg-white/20 backdrop-blur-sm transition-all duration-300">
                            🔽 Ordenar
                        </button>
                        <div class="dropdown-menu absolute top-full left-0 mt-2 w-48 bg-white dark:bg-dark-surface rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 z-50">
                            <div class="p-2">
                                <a href="{{ route('feed') }}" class="block px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                                    Más recientes
                                </a>
                                <a href="{{ route('feed') }}?sort=most_commented" class="block px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                                    💬 Más comentados
                                </a>
                                <a href="{{ route('feed') }}?sort=most_reactions" class="block px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                                    ❤️ Más reaccionados
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Login / Register / Dark Mode -->
                    @auth
                        <a href="{{ route('feed') }}" class="nav-btn px-4 py-2 rounded-lg text-white bg-primary hover:bg-secondary transition-all duration-300 ml-2">
                            📱 Feed
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="nav-btn px-4 py-2 rounded-lg text-white bg-white/20 backdrop-blur-sm transition-all duration-300">
                            Iniciar sesión
                        </a>
                        <a href="{{ route('register') }}" class="nav-btn px-4 py-2 rounded-lg text-white bg-secondary hover:bg-primary transition-all duration-300">
                            Registrarse
                        </a>
                    @endauth
                    
                    <!-- Botón modo oscuro -->
                    <button @click="darkMode = !darkMode" class="nav-btn p-2 rounded-lg bg-white/20 backdrop-blur-sm transition-all duration-300 ml-1">
                        <span x-show="!darkMode" class="text-yellow-400">🌞</span>
                        <span x-show="darkMode" class="text-gray-300">🌙</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- HERO SECTION CON VIDEO COMPLETO (con botones y búsqueda) -->
    @if(Route::currentRouteName() == 'home')
    <section class="relative h-[80vh] min-h-[500px] overflow-hidden">
        <video autoplay loop muted playsinline class="hero-video" style="object-fit: cover; object-position: center 30%;">
            <source src="{{ asset('videos/video_UPEA_4k.mp4') }}" type="video/mp4">
        </video>
        <div class="hero-overlay"></div>
        <div class="hero-content absolute inset-0 flex flex-col items-center justify-center text-center px-4">
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-4 animate-fade-in">
                Conecta, Comparte y <span class="text-secondary">Crece</span>
            </h1>
            <p class="text-lg md:text-xl text-white/90 max-w-2xl mb-8 animate-slide-up">
                La red social exclusiva para la comunidad universitaria. Comparte noticias, eventos y conecta con estudiantes de tu carrera.
            </p>
            
            <!-- Barra de búsqueda -->
            <div class="w-full max-w-3xl bg-white/10 backdrop-blur-md rounded-2xl p-2 animate-slide-up">
                <form action="{{ route('feed') }}" method="GET" class="flex flex-col md:flex-row gap-2">
                    <input type="text" name="search" placeholder="Buscar publicaciones, eventos, noticias..." 
                           class="flex-1 bg-white/20 text-white placeholder-white/60 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-secondary">
                    <button type="submit" class="bg-secondary hover:bg-primary text-white px-6 py-3 rounded-xl transition-all duration-300 hover:scale-105 font-semibold">
                        🔍 Buscar
                    </button>
                </form>
            </div>
            
            <!-- Categorías rápidas -->
            <div class="flex flex-wrap justify-center gap-3 mt-8">
                <a href="{{ route('feed') }}?type=evento" class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-white hover:bg-secondary transition">
                    📅 Eventos
                </a>
                <a href="{{ route('feed') }}?type=noticia" class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-white hover:bg-secondary transition">
                    📰 Noticias
                </a>
                <a href="{{ route('feed') }}?type=curso" class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-white hover:bg-secondary transition">
                    📚 Cursos
                </a>
                <a href="{{ route('feed') }}?type=aviso" class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-white hover:bg-secondary transition">
                    ⚠️ Avisos
                </a>
                <a href="{{ route('feed') }}?sort=most_commented" class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-white hover:bg-secondary transition">
                    🔥 Tendencias
                </a>
            </div>
        </div>
    </section>
    @endif
    
    <!-- CONTENIDO PRINCIPAL -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- TENDENCIAS DE LA SEMANA (3x2 = 6 publicaciones) -->
        <section class="mb-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-text-primary dark:text-dark-text-primary">
                    🔥 <span class="text-secondary">Tendencias</span> de la semana
                </h2>
                <span class="text-sm text-text-secondary dark:text-dark-text-secondary">Lo más comentado y reaccionado</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($trendingPosts as $post)
                    <a href="{{ route('posts.show', $post) }}" class="post-card bg-surface dark:bg-dark-surface rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 block">
                        @if($post->images && count(json_decode($post->images, true)) > 0)
                            @php $images = json_decode($post->images, true); @endphp
                            <img src="{{ $images[0] }}" alt="{{ $post->title }}" class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                                <span class="text-3xl">🦅</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="font-bold text-text-primary dark:text-dark-text-primary mb-1 line-clamp-2">{{ $post->title }}</h3>
                            <div class="flex justify-between items-center text-sm text-text-secondary dark:text-dark-text-secondary mt-2">
                                <span>👤 {{ $post->user->name }}</span>
                                <div class="flex gap-3">
                                    <span>💬 {{ $post->comments_count }}</span>
                                    <span>❤️ {{ $post->reactions_count }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-3 text-center py-8 text-text-secondary">
                        No hay tendencias aún. ¡Sé el primero en publicar!
                    </div>
                @endforelse
            </div>
        </section>
        
        <!-- PUBLICACIONES RECIENTES (feed completo) -->
        <section>
            <h2 class="text-2xl font-bold text-text-primary dark:text-dark-text-primary mb-6">
                📰 Últimas publicaciones
            </h2>
            
            @forelse($posts as $post)
                <div class="post-card bg-surface dark:bg-dark-surface rounded-xl shadow-md hover:shadow-xl transition-all duration-300 mb-6 overflow-hidden">
                    <div class="p-5">
                        <!-- Encabezado -->
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr($post->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-text-primary dark:text-dark-text-primary">{{ $post->user->name }} {{ $post->user->lastname }}</h3>
                                    <p class="text-xs text-text-secondary dark:text-dark-text-secondary">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @auth
                                @if(auth()->id() === $post->user_id)
                                    <a href="{{ route('posts.edit', $post) }}" class="text-text-secondary hover:text-primary transition">✏️</a>
                                @endif
                            @endauth
                        </div>
                        
                        <!-- Título y contenido -->
                        <a href="{{ route('posts.show', $post) }}">
                            <h2 class="text-xl font-bold text-text-primary dark:text-dark-text-primary mb-2 hover:text-primary transition">{{ $post->title }}</h2>
                        </a>
                        <p class="text-text-secondary dark:text-dark-text-secondary mb-3">{{ Str::limit($post->content, 150) }}</p>
                        
                        <!-- Imágenes -->
                        @if($post->images)
                            @php $images = json_decode($post->images, true); @endphp
                            @if(is_array($images) && count($images) > 0)
                                <div class="grid grid-cols-2 gap-2 mb-4">
                                    @foreach(array_slice($images, 0, 2) as $image)
                                        <img src="{{ $image }}" alt="Imagen" class="rounded-lg w-full h-32 object-cover">
                                    @endforeach
                                </div>
                            @endif
                        @endif
                        
                        <!-- Reacciones -->
                        <div class="flex gap-2 mb-4 flex-wrap border-t pt-3">
                            @auth
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
                            @else
                                @php $reactionTypes = ['ya' => '😊', 'ahh' => '😮', 'ehh' => '🤔', 'ohh' => '😲', 'uhh' => '😅']; @endphp
                                @foreach($reactionTypes as $key => $emoji)
                                    <div class="px-3 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm">
                                        {{ $emoji }} {{ $key }} ({{ $post->reactions->where('type', $key)->count() }})
                                    </div>
                                @endforeach
                                <div class="text-xs text-text-secondary ml-2">
                                    <a href="{{ route('login') }}" class="text-secondary hover:text-primary">Inicia sesión</a> para reaccionar
                                </div>
                            @endauth
                        </div>
                        
                        <!-- Comentarios simplificados -->
                        <div class="border-t pt-3">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-text-primary dark:text-dark-text-primary">💬 Comentarios ({{ $post->comments->count() }})</h4>
                                <a href="{{ route('posts.show', $post) }}" class="text-xs text-secondary hover:text-primary transition">Ver todos</a>
                            </div>
                            
                            @foreach($post->comments->take(2) as $comment)
                                <div class="mb-2 text-sm bg-gray-50 dark:bg-gray-800/50 p-2 rounded-lg">
                                    <strong class="text-text-primary dark:text-dark-text-primary">{{ $comment->user->name }}</strong>
                                    <p class="text-text-secondary dark:text-dark-text-secondary">{{ Str::limit($comment->content, 80) }}</p>
                                </div>
                            @endforeach

                            @auth
                                <form action="{{ route('comments.store', $post) }}" method="POST" class="mt-2">
                                    @csrf
                                    <div class="flex gap-2">
                                        <input type="text" name="content" placeholder="Escribe un comentario..." 
                                               class="flex-1 bg-background dark:bg-dark-background border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-secondary focus:border-transparent">
                                        <button type="submit" class="bg-secondary hover:bg-primary text-white px-4 py-2 rounded-lg text-sm transition-all duration-300">
                                            ➤
                                        </button>
                                    </div>
                                </form>
                            @else
                                <p class="text-xs text-text-secondary mt-2">
                                    <a href="{{ route('login') }}" class="text-secondary hover:text-primary">Inicia sesión</a> para comentar
                                </p>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-surface dark:bg-dark-surface rounded-xl">
                    <p class="text-text-secondary">No hay publicaciones aún. ¡Sé el primero en publicar!</p>
                    @auth
                        <a href="{{ route('posts.create') }}" class="inline-block mt-4 bg-secondary hover:bg-primary text-white px-6 py-2 rounded-lg transition">
                            + Crear publicación
                        </a>
                    @endauth
                </div>
            @endforelse
            
            <!-- Paginación -->
            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        </section>
    </main>
    
    <!-- FOOTER -->
    <footer class="bg-primary dark:bg-dark-primary py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-white/70 text-sm">© {{ date('Y') }} UniSocial - Conectando a la comunidad universitaria</p>
        </div>
    </footer>
    
    <script>
        // Sincronizar darkMode
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        // Reacciones AJAX
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