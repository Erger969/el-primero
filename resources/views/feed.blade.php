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
                    <!-- Dropdown Carreras -->
                    <div class="relative hidden md:block dropdown-trigger">
                        <button class="px-4 py-2 text-white font-medium transition-all duration-300 rounded-xl bg-white/10 hover:bg-white/20 backdrop-blur-sm">
                            📚 Carreras
                        </button>
                        <div class="absolute left-0 z-50 w-64 mt-2 bg-white border border-gray-200 shadow-xl dark:bg-dark-surface rounded-xl dropdown-menu top-full dark:border-gray-700">
                            <div class="p-2">
                                <div class="px-3 py-2 text-xs font-semibold text-gray-500 border-b dark:text-gray-400 dark:border-gray-700">
                                    Todas las carreras
                                </div>
                                @foreach(\App\Models\Career::all() as $career)
                                    <a href="{{ route('feed') }}?career_id={{ $career->id }}" 
                                       class="flex items-center justify-between px-3 py-2 text-sm transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-text-primary dark:text-dark-text-primary">
                                        <span>{{ $career->nombre }}</span>
                                        <span class="text-xs text-gray-400">{{ $career->users()->count() }} estudiantes</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown Perfil -->
                    <div class="relative dropdown-trigger">
                        <button class="flex items-center gap-2 px-4 py-2 text-white font-medium transition-all duration-300 rounded-xl bg-white/10 hover:bg-white/20 backdrop-blur-sm">
                            <div class="flex items-center justify-center w-6 h-6 text-xs font-bold text-white rounded-full bg-gradient-to-br from-primary to-secondary">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="hidden sm:inline text-sm">{{ Auth::user()->name }}</span>
                        </button>
                        <div class="absolute right-0 z-50 w-48 mt-2 bg-white border border-gray-200 shadow-xl dropdown-menu top-full dark:bg-dark-surface rounded-xl dark:border-gray-700">
                            <div class="p-2">
                                <a href="{{ route('profile.show', Auth::id()) }}" class="flex items-center gap-2 px-3 py-2 text-sm transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-text-primary dark:text-dark-text-primary">
                                    👤 Mi Perfil
                                </a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 text-sm transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-text-primary dark:text-dark-text-primary">
                                    ✏️ Editar Perfil
                                </a>
                                @if(Auth::user()->role_id == 3)
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-sm transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-text-primary dark:text-dark-text-primary">
                                        📊 Admin Dashboard
                                    </a>
                                @endif
                                <div class="h-px my-1 bg-gray-100 dark:bg-gray-700"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full gap-2 px-3 py-2 text-sm text-left text-red-600 transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                        🚪 Cerrar sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="w-px h-6 mx-1 bg-white/20"></div>
                    
                    <button @click="darkMode = !darkMode" class="p-2.5 text-white bg-white/10 hover:bg-white/20 rounded-xl transition-all">
                        <span x-show="!darkMode" class="text-yellow-400 text-xl">🌞</span>
                        <span x-show="darkMode" class="text-gray-300 text-xl">🌙</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION MODIFICADO -->
    <div class="relative z-10 flex flex-col items-center justify-center h-[50vh] min-h-[400px] px-4 text-center text-white">
        <h1 class="mb-4 text-4xl font-bold md:text-6xl lg:text-7xl leading-tight animate-fade-in">
            Tu Comunidad <span class="text-secondary">UPEA</span>
        </h1>
        <p class="max-w-2xl mx-auto mb-10 text-lg md:text-2xl text-white/80 font-light animate-fade-in">
            Bienvenido, <span class="font-bold text-white">{{ Auth::user()->name }}</span>. Mantente al día con lo que sucede en tu carrera.
        </p>
        
        <div class="w-full max-w-3xl p-2 mx-auto mb-8 bg-white/10 backdrop-blur-md rounded-2xl animate-fade-in">
            <form action="{{ route('feed') }}" method="GET" class="flex flex-col gap-2 md:flex-row">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar publicaciones..." 
                       class="flex-1 px-4 py-3 text-slate-800 bg-white/20 placeholder-white/60 rounded-xl focus:outline-none focus:ring-2 focus:ring-secondary">
                <button type="submit" class="px-6 py-3 font-semibold text-white transition-all duration-300 bg-secondary hover:bg-primary rounded-xl hover:scale-105">
                    🔍 Buscar
                </button>
            </form>
        </div>
    </div>          </div>
            </div>
     <!-- CONTENIDO PRINCIPAL -->
    <main class="content-container max-w-5xl mx-auto -mt-20 bg-background dark:bg-dark-background rounded-t-[3rem] shadow-2xl">
        
        <!-- Filtros y Nueva Publicación -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Sidebar Filtros -->
            <div class="md:col-span-1 space-y-6">
                <div class="p-6 bg-surface dark:bg-dark-surface rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800">
                    <h3 class="mb-4 font-bold text-text-primary dark:text-dark-text-primary flex items-center gap-2">
                        <span>🔍</span> Filtrar
                    </h3>
                    <form method="GET" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Carrera</label>
                            <select name="career_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl bg-background dark:bg-dark-background dark:border-gray-700 text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-secondary outline-none">
                                <option value="">Todas</option>
                                @foreach($careers as $career)
                                    <option value="{{ $career->id }}" {{ request('career_id') == $career->id ? 'selected' : '' }}>
                                        {{ $career->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Fecha</label>
                            <select name="date_filter" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl bg-background dark:bg-dark-background dark:border-gray-700 text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-secondary outline-none">
                                <option value="">Todas</option>
                                <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Hoy</option>
                                <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>Última semana</option>
                                <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>Último mes</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Orden</label>
                            <select name="sort" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl bg-background dark:bg-dark-background dark:border-gray-700 text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-secondary outline-none">
                                <option value="">Más recientes</option>
                                <option value="most_commented" {{ request('sort') == 'most_commented' ? 'selected' : '' }}>💬 Más comentados</option>
                                <option value="most_reactions" {{ request('sort') == 'most_reactions' ? 'selected' : '' }}>❤️ Más reaccionados</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="w-full py-2.5 text-sm font-semibold text-white transition-all duration-300 rounded-xl bg-primary hover:bg-primary/90">
                            Aplicar Filtros
                        </button>
                        
                        @if(request()->anyFilled(['career_id', 'date_filter', 'sort', 'search']))
                            <a href="{{ route('feed') }}" class="block text-center text-xs text-secondary hover:text-primary font-medium transition-colors">
                                ✖ Limpiar todo
                            </a>
                        @endif
                    </form>
                </div>

                <a href="{{ route('posts.create') }}" class="flex items-center justify-center gap-2 w-full p-4 font-bold text-white transition-all duration-300 rounded-2xl btn-secondary hover:scale-[1.02] active:scale-[0.98]">
                    <span>✨</span> Nueva Publicación
                </a>
            </div>
            
            <!-- Feed de Publicaciones -->
            <div class="md:col-span-3 space-y-6">
                <!-- SECCIÓN DE RESULTADOS DE BÚSQUEDA (Si existe búsqueda) -->
                @if($search)
                    <div class="mb-8 animate-fade-in">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6">
                            <div>
                                <h2 class="text-2xl font-bold text-text-primary dark:text-dark-text-primary">
                                    🔍 Resultados para: <span class="text-secondary">"{{ $search }}"</span>
                                </h2>
                                <p class="text-sm text-text-secondary dark:text-dark-text-secondary mt-1">
                                    Filtrando por: <span class="font-bold text-primary">{{ $searchType === 'title' ? 'Títulos' : ($searchType === 'content' ? 'Descripciones' : 'Usuarios') }}</span>
                                </p>
                            </div>
                        </div>

                        {{-- Selectores de Categoría (Tabs) --}}
                        <div class="flex flex-wrap gap-2 mb-4">
                            <a href="{{ route('feed', array_merge(request()->except(['search_type']), ['search_type' => 'title'])) }}" 
                               class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-all duration-300 {{ $searchType === 'title' ? 'bg-secondary text-white shadow-md' : 'bg-surface dark:bg-dark-surface text-text-secondary hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                <span>📌 Títulos</span>
                                <span class="px-1.5 py-0.5 rounded-lg text-[10px] {{ $searchType === 'title' ? 'bg-white/20' : 'bg-gray-200 dark:bg-gray-700' }}">
                                    {{ $searchCounts['title'] }}
                                </span>
                            </a>
                            
                            <a href="{{ route('feed', array_merge(request()->except(['search_type']), ['search_type' => 'content'])) }}" 
                               class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-all duration-300 {{ $searchType === 'content' ? 'bg-secondary text-white shadow-md' : 'bg-surface dark:bg-dark-surface text-text-secondary hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                <span>📝 Descripciones</span>
                                <span class="px-1.5 py-0.5 rounded-lg text-[10px] {{ $searchType === 'content' ? 'bg-white/20' : 'bg-gray-200 dark:bg-gray-700' }}">
                                    {{ $searchCounts['content'] }}
                                </span>
                            </a>
                            
                            <a href="{{ route('feed', array_merge(request()->except(['search_type']), ['search_type' => 'user'])) }}" 
                               class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-all duration-300 {{ $searchType === 'user' ? 'bg-secondary text-white shadow-md' : 'bg-surface dark:bg-dark-surface text-text-secondary hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                <span>👥 Usuarios</span>
                                <span class="px-1.5 py-0.5 rounded-lg text-[10px] {{ $searchType === 'user' ? 'bg-white/20' : 'bg-gray-200 dark:bg-gray-700' }}">
                                    {{ $searchCounts['user'] }}
                                </span>
                            </a>
                        </div>
                    </div>
                @endif

                @forelse($posts as $post)
                    <div class="shadow-md post-card bg-surface dark:bg-dark-surface rounded-2xl overflow-hidden">
                        <div class="p-6">
                            {{-- Autor --}}
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-11 h-11 font-bold text-white text-sm rounded-full bg-gradient-to-br from-primary to-secondary flex-shrink-0 shadow-sm">
                                        {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-text-primary dark:text-dark-text-primary leading-none">{{ $post->user->name }} {{ $post->user->lastname }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <p class="text-xs text-text-secondary dark:text-dark-text-secondary">{{ $post->created_at->diffForHumans() }}</p>
                                            @if($post->user->career)
                                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-primary/10 text-primary dark:bg-primary/20 dark:text-blue-300">
                                                    {{ $post->user->career->nombre }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if(auth()->id() === $post->user_id)
                                    <a href="{{ route('posts.edit', $post) }}" class="p-2 text-text-secondary hover:text-primary transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                @endif
                            </div>

                            {{-- Título y contenido --}}
                            <a href="{{ route('posts.show', $post) }}" class="group">
                                <h2 class="mb-2 text-xl font-bold text-text-primary dark:text-dark-text-primary group-hover:text-primary transition-colors">{{ $post->title }}</h2>
                            </a>
                            <p class="mb-4 text-text-secondary dark:text-dark-text-secondary leading-relaxed">{{ Str::limit($post->content, 200) }}</p>

                            {{-- Imágenes mejoradas --}}
                            @if($post->images)
                                @php $imgs = json_decode($post->images, true); @endphp
                                @if(is_array($imgs) && count($imgs) > 0)
                                    @php $imgCount = count(array_slice($imgs, 0, 2)); @endphp
                                    <div class="{{ $imgCount > 1 ? 'grid grid-cols-2 gap-3' : '' }} mb-4">
                                        @foreach(array_slice($imgs, 0, 2) as $img)
                                            <div class="rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden bg-gray-50 dark:bg-gray-900 shadow-sm">
                                                <img src="{{ $img }}" alt="Imagen de la publicación"
                                                     class="w-full h-auto max-h-[500px] object-contain hover:scale-105 transition-transform duration-500">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if(count($imgs) > 2)
                                        <p class="text-xs text-secondary font-medium mb-4">+{{ count($imgs) - 2 }} imágenes adicionales</p>
                                    @endif
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
                            <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-100 dark:border-gray-800">
                                @foreach($reactionTypes as $key => $reaction)
                                    <button class="reaction-btn px-3 py-1.5 rounded-full text-sm font-medium transition-all duration-200 flex items-center gap-1.5
                                        {{ $currentReaction === $key ? $reaction['color'].' text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}"
                                        data-post-id="{{ $post->id }}" data-type="{{ $key }}">
                                        <span>{{ $reaction['emoji'] }}</span>
                                        <span class="count-{{ $key }}" data-post-id="{{ $post->id }}">{{ $post->reactions->where('type', $key)->count() }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- SECCIÓN DE COMENTARIOS (Nativo) --}}
                        <details class="border-t border-gray-100 dark:border-gray-800 group">
                            <summary class="flex items-center gap-2 px-6 py-4 cursor-pointer select-none
                                            text-sm font-bold text-text-secondary dark:text-dark-text-secondary
                                            hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors list-none">
                                <span class="text-lg">💬</span>
                                <span>{{ $post->comments->count() }} comentario{{ $post->comments->count() !== 1 ? 's' : '' }}</span>
                                <svg class="ml-auto w-4 h-4 transition-transform duration-300 group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </summary>

                            <div class="px-6 pb-6 pt-2">
                                @if($post->comments->count() > 0)
                                    <div class="space-y-4 mb-6 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                                        @foreach($post->comments as $comment)
                                            <div class="flex gap-3">
                                                <div class="flex-shrink-0 flex items-center justify-center w-9 h-9 text-xs font-bold text-white rounded-full bg-gradient-to-br from-primary to-secondary shadow-sm">
                                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                </div>
                                                <div class="flex-1 bg-gray-50 dark:bg-gray-800/40 rounded-2xl rounded-tl-none px-4 py-3 border border-gray-100 dark:border-gray-700">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <span class="text-sm font-bold text-text-primary dark:text-dark-text-primary">{{ $comment->user->name }}</span>
                                                        <span class="text-[10px] text-text-secondary dark:text-dark-text-secondary">{{ $comment->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-sm text-text-secondary dark:text-dark-text-secondary">{{ $comment->content }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-center text-sm text-text-secondary dark:text-dark-text-secondary mb-6 py-4 italic">
                                        No hay comentarios aún. ¡Inicia la conversación!
                                    </p>
                                @endif

                                {{-- Formulario para comentar --}}
                                <form action="{{ route('comments.store', $post) }}" method="POST">
                                    @csrf
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 flex items-center justify-center w-9 h-9 text-xs font-bold text-white rounded-full bg-primary/20 text-primary border border-primary/10">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 flex items-center gap-2 bg-gray-100 dark:bg-gray-800 rounded-full px-5 py-2.5 focus-within:ring-2 focus-within:ring-secondary/50 transition-all">
                                            <input type="text" name="content" required placeholder="Añade un comentario..."
                                                   class="flex-1 bg-transparent text-sm text-text-primary dark:text-dark-text-primary outline-none placeholder-gray-400">
                                            <button type="submit" class="text-secondary hover:text-primary transition-colors flex-shrink-0">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </details>
                    </div>
                @empty
                    <div class="py-20 text-center bg-surface dark:bg-dark-surface rounded-2xl shadow-sm border border-dashed border-gray-300 dark:border-gray-700">
                        <div class="text-5xl mb-4">📭</div>
                        <h3 class="text-xl font-bold text-text-primary dark:text-dark-text-primary">No hay publicaciones</h3>
                        <p class="text-text-secondary dark:text-dark-text-secondary mt-2">Sé el primero en compartir algo con la comunidad.</p>
                        <a href="{{ route('posts.create') }}" class="mt-6 inline-block px-8 py-3 font-bold text-white rounded-xl btn-primary">
                            Crear mi primera publicación
                        </a>
                    </div>
                @endforelse

                <div class="mt-10">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER PREMIUM -->
    <footer class="relative z-10 py-16 bg-primary dark:bg-dark-primary border-t border-white/5">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-white/10 rounded-2xl">
                        <span class="text-2xl">🦅</span>
                    </div>
                    <div>
                        <span class="text-2xl font-bold text-white block">UniSocial</span>
                        <span class="text-sm text-white/40 font-medium">UPEA Comunidad Universitaria</span>
                    </div>
                </div>
                
                <div class="flex gap-8">
                    <a href="#" class="text-white/60 hover:text-secondary transition-colors font-medium">Privacidad</a>
                    <a href="#" class="text-white/60 hover:text-secondary transition-colors font-medium">Términos</a>
                    <a href="#" class="text-white/60 hover:text-secondary transition-colors font-medium">Ayuda</a>
                </div>
                
                <div class="text-right">
                    <p class="text-sm text-white/40 font-light">
                        © {{ date('Y') }} UniSocial. Hecho para estudiantes.
                    </p>
                </div>
            </div>
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
                        if (counter) counter.textContent = count;
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