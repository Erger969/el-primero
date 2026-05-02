<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UniSocial - Feed de Noticias</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    </style>
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-slate-900 overflow-x-hidden transition-colors duration-300"
      x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true',
          activePanel: 1,
          openComments: {},
      }" 
      x-init="$watch('darkMode', val => { localStorage.setItem('darkMode', val); if(val) document.documentElement.classList.add('dark'); else document.documentElement.classList.remove('dark'); })">
    
    <!-- Video de fondo fijo -->
    <video autoplay loop muted playsinline class="hero-video" style="object-fit: cover; object-position: center 30%;">
        <source src="{{ asset('videos/video_UPEA_4k.mp4') }}" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>
    
    <!-- NAVBAR STICKY -->
    @include('partials.navbar')

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
                <button type="submit" class="flex items-center justify-center gap-2 px-6 py-3 font-semibold text-white transition-all duration-300 bg-secondary hover:bg-primary rounded-xl hover:scale-105">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5" /> Buscar
                </button>
            </form>
        </div>
    </div>          </div>
            </div>
     <!-- CONTENIDO PRINCIPAL -->
    <main class="content-container w-full px-4 md:px-8 mx-auto -mt-20 bg-background dark:bg-dark-background rounded-t-[3rem] shadow-2xl">
        
        <!-- Filtros y Nueva Publicación -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Sidebar Filtros -->
            <div class="md:col-span-1 space-y-6">
                @if(Auth::user()->role_id != 4)
                    <a href="{{ route('posts.create') }}" class="flex items-center justify-center gap-2 w-full p-4 font-bold text-white transition-all duration-300 rounded-2xl bg-gradient-to-r from-secondary to-primary shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98]">
                        <x-heroicon-o-sparkles class="w-5 h-5" /> Nueva Publicación
                    </a>
                @endif
                <div class="p-6 bg-surface dark:bg-dark-surface rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800">
                    <h3 class="mb-4 font-bold text-text-primary dark:text-dark-text-primary flex items-center gap-2">
                        <x-heroicon-o-funnel class="w-5 h-5" /> Filtrar
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
                            <a href="{{ route('feed') }}" class="block flex items-center justify-center gap-1 mt-4 text-xs text-secondary hover:text-primary font-medium transition-colors">
                                <x-heroicon-o-x-mark class="w-4 h-4" /> Limpiar todo
                            </a>
                        @endif
                    </form>
                </div>



                {{-- Contador de acciones para Masters --}}
                @if(Auth::user()->role_id == 2)
                    @php
                        $hidesToday = \App\Models\MasterActivity::where('master_id', Auth::id())
                            ->where('action', 'hide')
                            ->whereDate('created_at', today())
                            ->count();
                        $remaining = max(0, 3 - $hidesToday);
                    @endphp
                    <div class="p-6 bg-gradient-to-br from-secondary/20 to-secondary/10 rounded-2xl shadow-sm border border-secondary/30 backdrop-blur-sm">
                        <h3 class="mb-2 font-bold text-secondary flex items-center gap-2">
                            <span class="text-xl">👑</span> Estado Master
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-gray-500 uppercase">Acciones hoy</span>
                                <span class="text-sm font-black {{ $remaining > 0 ? 'text-secondary' : 'text-red-500' }}">{{ $hidesToday }}/3</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                <div class="bg-secondary h-1.5 rounded-full transition-all duration-500" style="width: {{ ($hidesToday / 3) * 100 }}%"></div>
                            </div>
                            <p class="text-[10px] text-gray-500 italic leading-tight">
                                Tienes <strong>{{ $remaining }}</strong> ocultamientos disponibles para hoy. Úsalos con sabiduría.
                            </p>
                        </div>
                    </div>
                @endif

                {{-- Estado para Usuarios Suspendidos --}}
                @if(Auth::user()->role_id == 4)
                    <div class="p-6 bg-red-500/10 rounded-2xl shadow-sm border border-red-500/30 backdrop-blur-sm">
                        <h3 class="mb-2 font-bold text-red-600 flex items-center gap-2">
                            <x-heroicon-s-no-symbol class="w-5 h-5" /> Modo Lectura
                        </h3>
                        <p class="text-[10px] text-red-500/80 leading-tight">
                            Tu capacidad de interactuar ha sido restringida. Puedes ver publicaciones y comentarios, pero no crear contenido nuevo.
                        </p>
                    </div>
                @endif
            </div>
            
            <!-- Feed de Publicaciones -->
            <div class="md:col-span-3 space-y-6">
                @if(Auth::user()->role_id == 4)
                    <div class="mb-6 p-6 bg-red-500/10 border-2 border-red-500/20 rounded-2xl backdrop-blur-md animate-pulse">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-red-500 rounded-xl text-white shadow-lg shadow-red-500/20">
                                <x-heroicon-s-no-symbol class="w-8 h-8" />
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-red-600 dark:text-red-400">CUENTA SUSPENDIDA</h3>
                                <p class="text-sm text-red-500 font-medium">
                                    Tu cuenta está en modo lectura por infringir las normas de la comunidad. 
                                    @if(Auth::user()->suspended_until)
                                        La restricción terminará el <strong>{{ Auth::user()->suspended_until->format('d/m/Y H:i') }}</strong>.
                                    @else
                                        Esta restricción es <strong>permanente</strong>.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- SECCIÓN DE RESULTADOS DE BÚSQUEDA (Si existe búsqueda) -->
                @if($search)
                    <div class="mb-8 animate-fade-in">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6">
                            <div>
                                <h2 class="text-2xl font-bold text-text-primary dark:text-dark-text-primary flex items-center gap-2">
                                    <x-heroicon-o-magnifying-glass class="w-6 h-6" /> Resultados para: <span class="text-secondary">"{{ $search }}"</span>
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
                                <span><x-heroicon-o-tag class="w-4 h-4 inline-block mr-1 -mt-0.5" /> Títulos</span>
                                <span class="px-1.5 py-0.5 rounded-lg text-[10px] {{ $searchType === 'title' ? 'bg-white/20' : 'bg-gray-200 dark:bg-gray-700' }}">
                                    {{ $searchCounts['title'] }}
                                </span>
                            </a>
                            
                            <a href="{{ route('feed', array_merge(request()->except(['search_type']), ['search_type' => 'content'])) }}" 
                               class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-all duration-300 {{ $searchType === 'content' ? 'bg-secondary text-white shadow-md' : 'bg-surface dark:bg-dark-surface text-text-secondary hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                <span><x-heroicon-o-document-text class="w-4 h-4 inline-block mr-1 -mt-0.5" /> Descripciones</span>
                                <span class="px-1.5 py-0.5 rounded-lg text-[10px] {{ $searchType === 'content' ? 'bg-white/20' : 'bg-gray-200 dark:bg-gray-700' }}">
                                    {{ $searchCounts['content'] }}
                                </span>
                            </a>
                            
                            <a href="{{ route('feed', array_merge(request()->except(['search_type']), ['search_type' => 'user'])) }}" 
                               class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-all duration-300 {{ $searchType === 'user' ? 'bg-secondary text-white shadow-md' : 'bg-surface dark:bg-dark-surface text-text-secondary hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                <span><x-heroicon-o-user-group class="w-4 h-4 inline-block mr-1 -mt-0.5" /> Usuarios</span>
                                <span class="px-1.5 py-0.5 rounded-lg text-[10px] {{ $searchType === 'user' ? 'bg-white/20' : 'bg-gray-200 dark:bg-gray-700' }}">
                                    {{ $searchCounts['user'] }}
                                </span>
                            </a>
                        </div>
                    </div>
                @endif

                @forelse($posts as $post)
                    <div class="shadow-md post-card bg-surface dark:bg-dark-surface rounded-2xl overflow-hidden {{ $post->is_hidden ? 'opacity-75 border-2 border-red-500/20' : '' }}">
                        @if($post->is_hidden)
                            <div class="bg-red-500/10 px-6 py-2 border-b border-red-500/20 flex items-center gap-2">
                                <x-heroicon-s-eye-slash class="w-4 h-4 text-red-500" />
                                <span class="text-xs font-bold text-red-500 uppercase tracking-tight">Esta publicación está oculta para usuarios normales</span>
                            </div>
                        @endif
                        <div class="p-6">
                            {{-- Autor --}}
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-11 h-11 font-bold text-white text-sm rounded-full bg-gradient-to-br from-primary to-secondary flex-shrink-0 shadow-sm">
                                        {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="font-bold text-text-primary dark:text-dark-text-primary leading-none">{{ $post->user->name }} {{ $post->user->lastname }}</p>
                                            @if($post->user->role_id == 2)
                                                <span class="badge-master">Master</span>
                                            @elseif($post->user->role_id == 3)
                                                <span class="badge-admin">Admin</span>
                                            @endif
                                        </div>
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
                                <div class="flex items-center gap-2">
                                    {{-- Botón de moderación para Master y Admin --}}
                                    @if((auth()->user()->role_id == 2 && $post->user_id !== auth()->id()) || auth()->user()->role_id == 3)
                                        <button onclick="toggleHidePost({{ $post->id }}, this)" 
                                                data-hidden="{{ $post->is_hidden ? 'true' : 'false' }}"
                                                class="hide-post-btn {{ $post->is_hidden ? 'hide-post-btn-active' : 'hide-post-btn-inactive' }}"
                                                title="{{ $post->is_hidden ? 'Mostrar publicación' : 'Ocultar publicación' }}">
                                            @if($post->is_hidden)
                                                <x-heroicon-s-eye-slash class="w-4 h-4" />
                                                <span>Oculto</span>
                                            @else
                                                <x-heroicon-o-eye-slash class="w-4 h-4" />
                                                <span>Ocultar</span>
                                            @endif
                                        </button>
                                    @endif

                                    @if(auth()->id() === $post->user_id)
                                        <a href="{{ route('posts.edit', $post) }}" class="p-2 text-text-secondary hover:text-primary transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>

                            {{-- Título y contenido --}}
                            <a href="{{ route('posts.show', $post) }}" class="group">
                                <h2 class="mb-2 text-xl font-bold text-text-primary dark:text-dark-text-primary group-hover:text-primary transition-colors">{{ $post->title }}</h2>
                            </a>
                            <p class="mb-4 text-text-secondary dark:text-dark-text-secondary leading-relaxed">{{ Str::limit($post->content, 200) }}</p>

                            {{-- Imágenes mejoradas --}}
                            @if($post->images)
                                @php $imgs = $post->images; @endphp
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
                                <x-heroicon-o-chat-bubble-left-ellipsis class="w-6 h-6" />
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
                        <div class="text-5xl mb-4 flex justify-center text-gray-300 dark:text-gray-600"><x-heroicon-o-inbox class="w-16 h-16" /></div>
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
                        <x-heroicon-s-academic-cap class="w-8 h-8 text-white" />
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
        
        async function toggleHidePost(postId, btn) {
            try {
                const response = await fetch(`/master/posts/${postId}/hide`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const isHidden = data.status === 'hidden';
                    btn.dataset.hidden = isHidden.toString();
                    
                    // Actualizar estilos del botón
                    if (isHidden) {
                        btn.classList.remove('hide-post-btn-inactive');
                        btn.classList.add('hide-post-btn-active');
                        btn.innerHTML = '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" /><path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" /></svg><span>Oculto</span>';
                    } else {
                        btn.classList.remove('hide-post-btn-active');
                        btn.classList.add('hide-post-btn-inactive');
                        btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg><span>Ocultar</span>';
                    }
                    
                    // Actualizar la tarjeta (opacidad y borde)
                    const card = btn.closest('.post-card');
                    if (isHidden) {
                        card.classList.add('opacity-75', 'border-2', 'border-red-500/20');
                        // Añadir banner de oculto si no existe
                        if (!card.querySelector('.bg-red-500/10')) {
                            const banner = document.createElement('div');
                            banner.className = 'bg-red-500/10 px-6 py-2 border-b border-red-500/20 flex items-center gap-2';
                            banner.innerHTML = '<svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" /><path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" /></svg><span class="text-xs font-bold text-red-500 uppercase tracking-tight">Esta publicación está oculta para usuarios normales</span>';
                            card.prepend(banner);
                        }
                    } else {
                        card.classList.remove('opacity-75', 'border-2', 'border-red-500/20');
                        const banner = card.querySelector('.bg-red-500/10');
                        if (banner) banner.remove();
                    }
                    
                    // Actualizar contador en sidebar si existe
                    const sidebarCounter = document.querySelector('.text-sm.font-black.text-secondary');
                    if (sidebarCounter) {
                        sidebarCounter.textContent = `${data.hides_today}/3`;
                        if (data.remaining === 0) sidebarCounter.classList.replace('text-secondary', 'text-red-500');
                        else sidebarCounter.classList.replace('text-red-500', 'text-secondary');
                        
                        const progressBar = document.querySelector('.bg-secondary.h-1.5.rounded-full');
                        if (progressBar) progressBar.style.width = `${(data.hides_today / 3) * 100}%`;
                        
                        const remainingText = document.querySelector('.text-\\[10px\\].text-gray-500.italic strong');
                        if (remainingText) remainingText.textContent = data.remaining;
                    }
                } else {
                    alert(data.error || 'Ocurrió un error al procesar la solicitud.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error inesperado.');
            }
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