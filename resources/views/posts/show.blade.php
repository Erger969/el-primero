<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{}" 
      x-init="$store.darkMode = { value: localStorage.getItem('darkMode') === 'true', toggle() { this.value = !this.value; localStorage.setItem('darkMode', this.value); if (this.value) { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); } } }"
      :class="$store.darkMode.value ? 'dark' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UniSocial - {{ $post->title }}</title>
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

        /* We reuse the hero-video and overlay to maintain the same exact background feel, but keep it fixed so it doesn't move and we blur it more */
        .hero-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -2;
            filter: blur(12px);
        }
        
        .hero-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(10, 22, 32, 0.85) 0%, rgba(26, 60, 94, 0.8) 100%);
            z-index: -1;
            backdrop-filter: blur(8px);
        }
        
        .glass-nav {
            backdrop-filter: blur(16px);
            background-color: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .dark .glass-nav {
            background-color: rgba(10, 22, 32, 0.7);
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
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-slate-900 overflow-x-hidden transition-colors duration-300 min-h-screen flex flex-col"
      x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true'
      }" 
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" 
      :class="{ 'light': !darkMode, 'dark': darkMode }">
    
    <!-- Background Video Blur -->
    <video autoplay loop muted playsinline class="hero-video">
        <source src="{{ asset('videos/video_UPEA_4k.mp4') }}" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>
    
    <!-- NAVBAR STICKY -->
    <nav class="sticky top-0 z-50 glass-nav transition-all duration-500">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <a href="{{ route('home') }}" class="flex items-center gap-3 transition-transform hover:scale-105 group">
                    <div class="flex items-center justify-center w-11 h-11 bg-gradient-to-br from-[#1A3C5E] to-[#C4A35A] rounded-2xl shadow-lg group-hover:rotate-12 transition-all duration-300">
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
                        <div class="absolute left-0 z-50 w-64 mt-2 bg-white border border-gray-200 shadow-xl dark:bg-[#1e293b] rounded-xl dropdown-menu top-full dark:border-gray-700">
                            <div class="p-2">
                                <div class="px-3 py-2 text-xs font-semibold text-gray-500 border-b dark:text-gray-400 dark:border-gray-700">
                                    Todas las carreras
                                </div>
                                @foreach(\App\Models\Career::all() as $career)
                                    <a href="{{ route('feed') }}?career_id={{ $career->id }}" 
                                       class="flex items-center justify-between px-3 py-2 text-sm transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100">
                                        <span>{{ $career->nombre }}</span>
                                        <span class="text-xs text-gray-400">{{ $career->users()->count() }} estudiantes</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown Perfil -->
                    @auth
                    <div class="relative dropdown-trigger">
                        <button class="flex items-center gap-2 px-4 py-2 text-white font-medium transition-all duration-300 rounded-xl bg-white/10 hover:bg-white/20 backdrop-blur-sm">
                            <div class="flex items-center justify-center w-6 h-6 text-xs font-bold text-white rounded-full bg-gradient-to-br from-[#1A3C5E] to-[#C4A35A]">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="hidden sm:inline text-sm">{{ Auth::user()->name }}</span>
                        </button>
                        <div class="absolute right-0 z-50 w-48 mt-2 bg-white border border-gray-200 shadow-xl dropdown-menu top-full dark:bg-[#1e293b] rounded-xl dark:border-gray-700">
                            <div class="p-2">
                                <a href="{{ route('profile.show', Auth::id()) }}" class="flex items-center gap-2 px-3 py-2 text-sm transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    👤 Mi Perfil
                                </a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 text-sm transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    ✏️ Editar Perfil
                                </a>
                                @if(Auth::user()->role_id == 3)
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-sm transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100">
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
    
    <!-- MAIN CONTENT -->
    <div class="relative z-10 flex-1 py-8 px-4 sm:px-6 lg:px-8 max-w-[1400px] w-full mx-auto">
        
        <!-- Back Button -->
        <button onclick="window.history.back(); return false;" class="mb-6 inline-flex items-center gap-2 px-5 py-2.5 text-white bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/10 rounded-xl transition-all shadow-lg hover:-translate-x-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span class="font-bold tracking-wide">Volver al Feed</span>
        </button>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Post Details (7/12) -->
            <div class="lg:col-span-7 xl:col-span-8 bg-white dark:bg-[#1e293b] rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800 flex flex-col animate-fade-in">
                <div class="p-8 flex-1">
                    <!-- Author & Actions -->
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center w-14 h-14 font-bold text-white text-xl rounded-full bg-gradient-to-br from-[#1A3C5E] to-[#C4A35A] shadow-md">
                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-xl text-gray-900 dark:text-gray-100 leading-tight">{{ $post->user->name }} {{ $post->user->lastname }}</h3>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                                    @if($post->user->career)
                                        <span class="text-xs px-3 py-1 rounded-full bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300 font-bold border border-blue-100 dark:border-blue-800">
                                            {{ $post->user->career->nombre }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        @if(auth()->id() === $post->user_id)
                            <div class="flex gap-2">
                                <a href="{{ route('posts.edit', $post) }}" class="p-2.5 text-gray-400 hover:text-[#1A3C5E] dark:hover:text-[#C4A35A] transition-colors rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800" title="Editar">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta publicación?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 text-gray-400 hover:text-red-500 transition-colors rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800" title="Eliminar">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <h1 class="text-3xl sm:text-4xl font-bold mb-6 text-gray-900 dark:text-gray-100 leading-snug">{{ $post->title }}</h1>
                    <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed mb-8 whitespace-pre-line">{{ $post->content }}</p>
                    
                    <!-- Images -->
                    @if($post->images)
                        @php $images = json_decode($post->images, true); @endphp
                        @if(is_array($images) && count($images) > 0)
                            <div class="grid grid-cols-1 {{ count($images) > 1 ? 'sm:grid-cols-2' : '' }} gap-4 mb-6">
                                @foreach($images as $image)
                                    <div class="rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden bg-gray-50 dark:bg-gray-900 shadow-sm group">
                                        <img src="{{ $image }}" alt="Imagen de publicación" class="w-full h-auto max-h-[600px] object-contain group-hover:scale-[1.03] transition-transform duration-700">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Reactions Section -->
                <div class="p-6 lg:p-8 bg-gray-50 dark:bg-gray-800/40 border-t border-gray-100 dark:border-gray-800">
                    <h4 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span>⭐</span> Reacciones
                    </h4>
                    <div class="flex flex-wrap gap-3">
                        @php
                            $reactionTypes = [
                                'ya'  => ['emoji' => '😊', 'label' => 'Ya',  'color' => 'bg-green-500'],
                                'ahh' => ['emoji' => '😮', 'label' => 'Ahh', 'color' => 'bg-yellow-500'],
                                'ehh' => ['emoji' => '🤔', 'label' => 'Ehh', 'color' => 'bg-purple-500'],
                                'ohh' => ['emoji' => '😲', 'label' => 'Ohh', 'color' => 'bg-red-500'],
                                'uhh' => ['emoji' => '😅', 'label' => 'Uhh', 'color' => 'bg-blue-500'],
                            ];
                            // Usamos el mismo patrón que el Feed si es que la variable existe de otra forma
                            $currentReaction = isset($userReaction) ? $userReaction->type : null;
                        @endphp
                        
                        @foreach($reactionTypes as $key => $reaction)
                            <button class="reaction-btn px-5 py-2.5 rounded-2xl font-bold transition-all duration-300 flex items-center gap-2 shadow-sm
                                   {{ $currentReaction === $key 
                                       ? $reaction['color'] . ' text-white scale-105 shadow-md' 
                                       : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:scale-105 border border-gray-100 dark:border-gray-700' }}"
                                data-post-id="{{ $post->id }}"
                                data-type="{{ $key }}">
                                <span class="text-xl">{{ $reaction['emoji'] }}</span>
                                <span>{{ $reaction['label'] }}</span>
                                <span class="count-{{ $key }} ml-1 px-2.5 py-0.5 rounded-full text-xs font-black {{ $currentReaction === $key ? 'bg-white/30' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                                    {{ $post->reactions->where('type', $key)->count() }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Comments Cascade (5/12) -->
            <div class="lg:col-span-5 xl:col-span-4 flex flex-col h-[600px] lg:h-[calc(100vh-160px)] lg:sticky lg:top-28 bg-white dark:bg-[#1e293b] rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-800 overflow-hidden animate-fade-in" style="animation-delay: 0.1s;">
                <!-- Header -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/40">
                    <h3 class="text-xl font-bold flex items-center gap-3 text-gray-900 dark:text-gray-100">
                        <span class="text-2xl">💬</span> 
                        Comentarios 
                        <span class="bg-gradient-to-r from-[#1A3C5E] to-[#C4A35A] text-white text-sm font-black px-3 py-1 rounded-full shadow-sm">{{ $post->comments->count() }}</span>
                    </h3>
                </div>
                
                <!-- Comments Scrollable Area -->
                <div class="flex-1 overflow-y-auto p-6 space-y-5 custom-scrollbar bg-white dark:bg-[#1e293b]">
                    @forelse($post->comments as $comment)
                        <div class="flex gap-4 group">
                            <div class="flex-shrink-0 flex items-center justify-center w-11 h-11 text-sm font-bold text-white rounded-full bg-gradient-to-br from-[#1A3C5E] to-[#C4A35A] shadow-md group-hover:scale-110 transition-transform duration-300">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 bg-gray-50 dark:bg-gray-800/60 rounded-2xl rounded-tl-none px-5 py-4 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-300">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $comment->user->name }} {{ $comment->user->lastname }}</span>
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-2 py-1 rounded-lg">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $comment->content }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full opacity-60 space-y-4">
                            <span class="text-6xl drop-shadow-md">💭</span>
                            <p class="text-gray-500 dark:text-gray-400 font-bold text-lg">Sé el primero en comentar.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Comment Form -->
                <div class="p-6 bg-gray-50 dark:bg-gray-800/40 border-t border-gray-100 dark:border-gray-800 z-10 shadow-[0_-10px_20px_-15px_rgba(0,0,0,0.1)]">
                    @auth
                        <form action="{{ route('comments.store', $post) }}" method="POST">
                            @csrf
                            <div class="flex flex-col gap-3">
                                <div class="flex gap-3">
                                    <div class="flex-shrink-0 flex items-center justify-center w-11 h-11 text-sm font-bold text-[#1A3C5E] bg-blue-100 dark:bg-blue-900/40 dark:text-blue-300 rounded-full shadow-inner">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <textarea name="content" required placeholder="Escribe tu comentario aquí..." rows="2"
                                              class="w-full bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-2xl px-4 py-3 text-sm text-gray-900 dark:text-gray-100 outline-none focus:ring-2 focus:ring-[#C4A35A] transition-all resize-none shadow-sm"></textarea>
                                </div>
                                <button type="submit" class="self-end px-6 py-2.5 bg-gradient-to-r from-[#1A3C5E] to-[#2A6B9E] hover:from-[#C4A35A] hover:to-[#D4B06A] text-white font-bold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center gap-2">
                                    <span>Publicar</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-2">
                            <a href="{{ route('home') }}" class="inline-block w-full px-6 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                                🔐 Inicia sesión para comentar
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <script>
        const reactionColors = {
            'ya': 'bg-green-500',
            'ahh': 'bg-yellow-500',
            'ehh': 'bg-purple-500',
            'ohh': 'bg-red-500',
            'uhh': 'bg-blue-500'
        };
        
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
                    
                    // Update all counts
                    for (const [reactionType, count] of Object.entries(data.counts)) {
                        const counter = document.querySelector(`.count-${reactionType}`);
                        if (counter) counter.textContent = count;
                    }
                    
                    // Reset all buttons
                    const container = this.parentElement;
                    container.querySelectorAll('.reaction-btn').forEach(b => {
                        b.classList.remove('bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-red-500', 'bg-blue-500', 'text-white', 'scale-105', 'shadow-md');
                        b.classList.add('bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-300', 'border', 'border-gray-100', 'dark:border-gray-700');
                        
                        // Reset badge background
                        const badge = b.querySelector(`[class*="count-"]`);
                        if(badge) {
                            badge.classList.remove('bg-white/30', 'text-white');
                            badge.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-500', 'dark:text-gray-400');
                        }
                    });
                    
                    // Set active button
                    this.classList.remove('bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-300', 'border', 'border-gray-100', 'dark:border-gray-700');
                    this.classList.add(reactionColors[type], 'text-white', 'scale-105', 'shadow-md');
                    
                    // Set active badge
                    const activeBadge = this.querySelector(`[class*="count-"]`);
                    if(activeBadge) {
                        activeBadge.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-gray-500', 'dark:text-gray-400');
                        activeBadge.classList.add('bg-white/30', 'text-white');
                    }
                    
                } catch (error) {
                    console.error('Error:', error);
                }
            });
        });
    </script>
</body>
</html>