<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{}" 
      x-init="$store.darkMode = { value: localStorage.getItem('darkMode') === 'true', toggle() { this.value = !this.value; localStorage.setItem('darkMode', this.value); if (this.value) { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); } } }"
      :class="$store.darkMode.value ? 'dark' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UniSocial - Feed de Noticias</title>
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
            border-radius: 0.75rem;
        }
        
        .post-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            border-radius: 1rem;
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
<body class="transition-colors duration-300 bg-background dark:bg-dark-background text-text-primary dark:text-dark-text-primary"
      :class="{ 'light': !$store.darkMode.value, 'dark': $store.darkMode.value }">
    
    <!-- HERO SECTION CON VIDEO -->
    <section class="relative h-[60vh] min-h-[400px] overflow-hidden">
        <video autoplay loop muted playsinline class="hero-video" style="object-fit: cover; object-position: center 30%;">
            <source src="{{ asset('videos/video_UPEA_4k.mp4') }}" type="video/mp4">
        </video>
        <div class="hero-overlay"></div>
        <div class="absolute inset-0 flex flex-col items-center justify-center px-4 text-center hero-content">
            <h1 class="mb-4 text-4xl font-bold text-white md:text-5xl lg:text-6xl animate-fade-in">
                Bienvenido, <span class="text-secondary">{{ Auth::user()->name }}</span>
            </h1>
            <p class="max-w-2xl mb-8 text-lg text-white/90 animate-slide-up">
                Explora las últimas publicaciones, reacciona y conecta con tu comunidad universitaria.
            </p>
            
            <!-- Barra de búsqueda rápida -->
            <div class="w-full max-w-2xl p-2 bg-white/10 backdrop-blur-md rounded-2xl animate-slide-up">
                <form action="{{ route('feed') }}" method="GET" class="flex flex-col gap-2 md:flex-row">
                    <input type="text" name="search" placeholder="Buscar publicaciones..." 
                           class="flex-1 px-4 py-3 text-slate-800 bg-white/20 placeholder-white/60 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary">
                    <button type="submit" class="px-6 py-3 font-semibold text-white transition-all duration-300 bg-secondary hover:bg-primary rounded-xl hover:scale-105">
                        🔍 Buscar
                    </button>
                </form>
            </div>
        </div>
    </section>
    
    <!-- NAVBAR STICKY (siempre visible) -->
    <nav class="border-b sticky-nav border-white/20">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2 transition hover:opacity-80">
                    <div class="flex items-center justify-center w-9 h-9 bg-gradient-to-br from-primary to-secondary rounded-xl">
                        <span class="text-lg text-white">🦅</span>
                    </div>
                    <span class="text-xl font-bold text-slate-800 dark:text-white">UniSocial</span>
                </a>
                
                <!-- Botones de acción (dropdowns) -->
                <div class="flex items-center gap-1">
                    <!-- Dropdown Carreras -->
                    <div class="relative dropdown-trigger">
                        <button class="px-4 py-2 text-white transition-all duration-300 rounded-lg nav-btn bg-white/20 backdrop-blur-sm">
                            📚 Carreras
                        </button>
                        <div class="absolute left-0 z-50 w-64 mt-2 bg-white border border-gray-200 shadow-xl dark:bg-dark-surface rounded-xl dropdown-menu top-full dark:border-gray-700">
                            <div class="p-2">
                                <div class="px-3 py-2 text-xs font-semibold text-gray-500 border-b dark:text-gray-400 dark:border-gray-700">
                                    Todas las carreras
                                </div>
                                @foreach(\App\Models\Career::all() as $career)
                                    <a href="{{ route('feed') }}?career_id={{ $career->id }}" 
                                       class="flex items-center justify-between px-3 py-2 text-sm transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <span>{{ $career->nombre }}</span>
                                        <span class="text-xs text-gray-400">{{ $career->users()->count() }} estudiantes</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dropdown Fechas -->
                    <div class="relative dropdown-trigger">
                        <button class="px-4 py-2 text-white transition-all duration-300 rounded-lg nav-btn bg-white/20 backdrop-blur-sm">
                            📅 Fechas
                        </button>
                        <div class="absolute left-0 z-50 w-48 mt-2 bg-white border border-gray-200 shadow-xl dropdown-menu top-full dark:bg-dark-surface rounded-xl dark:border-gray-700">
                            <div class="p-2">
                                <a href="{{ route('feed') }}" class="block px-3 py-2 text-sm transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Todas las fechas
                                </a>
                                <a href="{{ route('feed') }}?date_filter=today" class="block px-3 py-2 text-sm transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Hoy
                                </a>
                                <a href="{{ route('feed') }}?date_filter=week" class="block px-3 py-2 text-sm transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Última semana
                                </a>
                                <a href="{{ route('feed') }}?date_filter=month" class="block px-3 py-2 text-sm transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Último mes
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dropdown Ordenar -->
                    <div class="relative dropdown-trigger">
                        <button class="px-4 py-2 text-white transition-all duration-300 rounded-lg nav-btn bg-white/20 backdrop-blur-sm">
                            🔽 Ordenar
                        </button>
                        <div class="absolute left-0 z-50 w-48 mt-2 bg-white border border-gray-200 shadow-xl dropdown-menu top-full dark:bg-dark-surface rounded-xl dark:border-gray-700">
                            <div class="p-2">
                                <a href="{{ route('feed') }}" class="block px-3 py-2 text-sm transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Más recientes
                                </a>
                                <a href="{{ route('feed') }}?sort=most_commented" class="block px-3 py-2 text-sm transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                    💬 Más comentados
                                </a>
                                <a href="{{ route('feed') }}?sort=most_reactions" class="block px-3 py-2 text-sm transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                    ❤️ Más reaccionados
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Nombre de usuario y rol -->
                    <div class="relative ml-2 dropdown-trigger">
                        <button class="flex items-center gap-2 px-3 py-2 text-white transition-all duration-300 rounded-lg nav-btn bg-white/20 backdrop-blur-sm">
                            <div class="flex items-center justify-center w-6 h-6 text-xs font-bold text-white rounded-full bg-gradient-to-br from-primary to-secondary">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="text-sm">{{ Auth::user()->name }}</span>
                            <span class="text-xs text-white/60">
                                @if(Auth::user()->role_id == 3) 👑 Admin
                                @elseif(Auth::user()->role_id == 2) ⭐ Master
                                @else 🎓 Universitario
                                @endif
                            </span>
                        </button>
                        <div class="absolute right-0 z-50 w-48 mt-2 bg-white border border-gray-200 shadow-xl dropdown-menu top-full dark:bg-dark-surface rounded-xl dark:border-gray-700">
                            <div class="p-2">
                                <a href="{{ route('profile.show', Auth::id()) }}" class="flex items-center gap-2 px-3 py-2 text-sm transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                    👤 Mi Perfil
                                </a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 text-sm transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                    ✏️ Editar Perfil
                                </a>
                                @if(Auth::user()->role_id == 3)
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-sm transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                        📊 Dashboard Admin
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full gap-2 px-3 py-2 text-sm text-left text-red-600 transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                        🚪 Cerrar sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botón modo oscuro (CORREGIDO) -->
                    <button @click="$store.darkMode.toggle()" class="p-2 ml-1 transition-all duration-300 rounded-lg nav-btn bg-white/20 backdrop-blur-sm">
                        <span x-show="!$store.darkMode.value" class="text-yellow-400">🌞</span>
                        <span x-show="$store.darkMode.value" class="text-gray-300">🌙</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- CONTENIDO PRINCIPAL -->
    <main class="max-w-4xl px-4 py-8 mx-auto sm:px-6 lg:px-8">
        
        <!-- Filtros -->
        <div class="mb-6 overflow-hidden transition-all duration-300 shadow-md bg-surface dark:bg-dark-surface rounded-xl">
            <div class="p-5">
                <h3 class="mb-3 font-bold text-text-primary dark:text-dark-text-primary">🔍 Filtrar publicaciones</h3>
                <form method="GET" class="grid grid-cols-1 gap-3 md:grid-cols-4">
                    <select name="career_id" class="px-3 py-2 text-sm border border-gray-200 rounded-lg bg-background dark:bg-dark-background dark:border-gray-700 text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-secondary">
                        <option value="">Todas las carreras</option>
                        @foreach($careers as $career)
                            <option value="{{ $career->id }}" {{ request('career_id') == $career->id ? 'selected' : '' }}>
                                {{ $career->nombre }}
                            </option>
                        @endforeach
                    </select>
                    
                    <select name="date_filter" class="px-3 py-2 text-sm border border-gray-200 rounded-lg bg-background dark:bg-dark-background dark:border-gray-700 text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-secondary">
                        <option value="">Todas las fechas</option>
                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Hoy</option>
                        <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>Última semana</option>
                        <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>Último mes</option>
                    </select>
                    
                    <select name="sort" class="px-3 py-2 text-sm border border-gray-200 rounded-lg bg-background dark:bg-dark-background dark:border-gray-700 text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-secondary">
                        <option value="">Más recientes</option>
                        <option value="most_commented" {{ request('sort') == 'most_commented' ? 'selected' : '' }}>💬 Más comentados</option>
                        <option value="most_reactions" {{ request('sort') == 'most_reactions' ? 'selected' : '' }}>❤️ Más reaccionados</option>
                    </select>
                    
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white transition-all duration-300 rounded-lg bg-secondary hover:bg-primary">
                        🔍 Aplicar
                    </button>
                </form>
                
                @if(request()->anyFilled(['career_id', 'date_filter', 'sort']))
                    <div class="mt-3">
                        <a href="{{ route('feed') }}" class="text-sm transition text-text-secondary hover:text-secondary">
                            ✖️ Limpiar filtros
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Botón nueva publicación -->
        <div class="mb-6">
            <a href="{{ route('posts.create') }}" class="inline-flex items-center gap-2 px-5 py-2 font-semibold text-white transition-all duration-300 rounded-lg bg-secondary hover:bg-primary hover:scale-105">
                ✨ + Nueva publicación
            </a>
        </div>
        
        <!-- Publicaciones -->
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
                                <div class="flex items-center gap-2 text-xs text-text-secondary dark:text-dark-text-secondary">
                                    <span>{{ $post->created_at->diffForHumans() }}</span>
                                    @if($post->user->career)
                                        <span>•</span>
                                        <span>{{ $post->user->career->nombre }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if(auth()->id() === $post->user_id)
                            <a href="{{ route('posts.edit', $post) }}" class="transition text-text-secondary hover:text-primary">✏️ Editar</a>
                        @endif
                    </div>
                    
                    <a href="{{ route('posts.show', $post) }}">
                        <h2 class="mb-2 text-xl font-bold transition text-text-primary dark:text-dark-text-primary hover:text-primary">{{ $post->title }}</h2>
                    </a>
                    <p class="mb-3 text-text-secondary dark:text-dark-text-secondary">{{ Str::limit($post->content, 200) }}</p>
                    
                    @if($post->images)
                        @php $images = json_decode($post->images, true); @endphp
                        @if(is_array($images) && count($images) > 0)
                            <div class="grid grid-cols-2 gap-2 mb-4">
                                @foreach(array_slice($images, 0, 2) as $image)
                                    <img src="{{ $image }}" alt="Imagen" class="object-cover w-full rounded-lg h-36">
                                @endforeach
                            </div>
                            @if(count($images) > 2)
                                <p class="mt-1 text-xs text-text-secondary">+{{ count($images) - 2 }} imágenes más</p>
                            @endif
                        @endif
                    @endif
                    
                    <!-- Reacciones -->
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
                    
                    <!-- Comentarios -->
                    <div class="pt-3 border-t">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-semibold text-text-primary dark:text-dark-text-primary">💬 Comentarios ({{ $post->comments->count() }})</h4>
                            <a href="{{ route('posts.show', $post) }}" class="text-xs transition text-secondary hover:text-primary">Ver todos</a>
                        </div>
                        
                        @foreach($post->comments->take(2) as $comment)
                            <div class="p-2 mb-2 text-sm rounded-lg bg-gray-50 dark:bg-gray-800/50">
                                <strong class="text-text-primary dark:text-dark-text-primary">{{ $comment->user->name }}</strong>
                                <p class="text-text-secondary dark:text-dark-text-secondary">{{ Str::limit($comment->content, 80) }}</p>
                            </div>
                        @endforeach

                        <form action="{{ route('comments.store', $post) }}" method="POST" class="mt-2">
                            @csrf
                            <div class="flex gap-2">
                                <input type="text" name="content" placeholder="Escribe un comentario..." 
                                       class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-lg bg-background dark:bg-dark-background dark:border-gray-700 text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-secondary focus:border-transparent">
                                <button type="submit" class="px-4 py-2 text-sm text-white transition-all duration-300 rounded-lg bg-secondary hover:bg-primary">
                                    ➤
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-12 text-center bg-surface dark:bg-dark-surface rounded-xl">
                <p class="mb-4 text-text-secondary">No hay publicaciones aún. ¡Sé el primero en publicar!</p>
                <a href="{{ route('posts.create') }}" class="inline-block px-6 py-2 text-white transition rounded-lg bg-secondary hover:bg-primary">
                    + Crear publicación
                </a>
            </div>
        @endforelse
        
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    </main>
    
    <footer class="py-6 mt-12 bg-primary dark:bg-dark-primary">
        <div class="px-4 mx-auto text-center max-w-7xl">
            <p class="text-sm text-white/70">© {{ date('Y') }} UniSocial - Conectando a la comunidad universitaria</p>
        </div>
    </footer>
    
    <!-- Modal para reportar -->
    <div id="reportModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-gray-600 bg-opacity-50">
        <div class="w-full max-w-md p-6 bg-white rounded-lg dark:bg-dark-surface">
            <h3 class="mb-4 text-lg font-bold text-text-primary dark:text-dark-text-primary">Reportar publicación</h3>
            <form id="reportForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block mb-2 font-bold text-gray-700 dark:text-gray-300">Motivo del reporte</label>
                    <select name="category" required class="w-full border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-dark-background">
                        <option value="spam">Spam o contenido engañoso</option>
                        <option value="acoso">Acoso o intimidación</option>
                        <option value="ofensivo">Contenido ofensivo</option>
                        <option value="desinformacion">Desinformación</option>
                        <option value="otro">Otro motivo</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block mb-2 font-bold text-gray-700 dark:text-gray-300">Descripción adicional (opcional)</label>
                    <textarea name="reason" rows="3" class="w-full border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-dark-background"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeReportModal()" class="px-4 py-2 text-white bg-gray-500 rounded hover:bg-gray-700">Cancelar</button>
                    <button type="submit" class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-700">Enviar reporte</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        let currentPostId = null;
        
        function openReportModal(postId) {
            currentPostId = postId;
            const form = document.getElementById('reportForm');
            form.action = `/posts/${postId}/report`;
            document.getElementById('reportModal').classList.remove('hidden');
            document.getElementById('reportModal').classList.add('flex');
        }
        
        function closeReportModal() {
            document.getElementById('reportModal').classList.add('hidden');
            document.getElementById('reportModal').classList.remove('flex');
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