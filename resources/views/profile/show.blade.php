<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{}" 
      x-init="$store.darkMode = { value: localStorage.getItem('darkMode') === 'true', toggle() { this.value = !this.value; localStorage.setItem('darkMode', this.value); if (this.value) { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); } } }"
      :class="$store.darkMode.value ? 'dark' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UniSocial - Perfil de {{ $user->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    </style>
</head>
<body class="bg-gray-50 dark:bg-slate-900 overflow-x-hidden transition-colors duration-300 min-h-screen flex flex-col"
      x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true',
          editingProfile: false
      }" 
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" 
      :class="{ 'light': !darkMode, 'dark': darkMode }">
    
    <!-- Background Video Blur -->
    <video autoplay loop muted playsinline class="hero-video">
        <source src="{{ asset('videos/video_UPEA_4k.mp4') }}" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>
    
    <!-- NAVBAR STICKY -->
    @include('partials.navbar')
    
    <!-- MAIN CONTENT -->
    <div class="relative z-10 flex-1 py-8 px-4 sm:px-6 lg:px-8 max-w-5xl w-full mx-auto animate-fade-in">
        
        <button onclick="window.history.back(); return false;" class="mb-6 inline-flex items-center gap-2 px-5 py-2.5 text-white bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/10 rounded-xl transition-all shadow-lg hover:-translate-x-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span class="font-bold tracking-wide">Volver Atras</span>
        </button>

        @if(session('success'))
            <div class="mb-6 bg-green-500/20 border border-green-500/50 text-green-100 px-6 py-4 rounded-xl flex items-center gap-3 backdrop-blur-sm">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-500/20 border border-red-500/50 text-red-100 px-6 py-4 rounded-xl flex items-center gap-3 backdrop-blur-sm">
                <span>❌</span> {{ session('error') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div class="mb-6 bg-red-500/20 border border-red-500/50 text-red-100 px-6 py-4 rounded-xl backdrop-blur-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800">
            <!-- Profile Header -->
            <div class="h-32 sm:h-48 bg-gradient-to-r from-[#1A3C5E] to-[#2A6B9E] relative">
                @if($isOwnProfile)
                    <button @click="editingProfile = !editingProfile" class="absolute top-4 right-4 bg-white/20 hover:bg-white/30 text-white backdrop-blur-md px-5 py-2.5 rounded-xl transition-colors font-bold shadow-sm border border-white/20 flex items-center gap-2">
                        <span x-show="!editingProfile">✏️ Editar Mi Perfil</span>
                        <span x-show="editingProfile">❌ Cancelar Edición</span>
                    </button>
                @elseif(!$isOwnProfile && auth()->user()->role_id == 3)
                    <button class="absolute top-4 right-4 bg-red-500/80 hover:bg-red-600 text-white px-4 py-2 rounded-xl transition-colors font-bold text-sm shadow-sm border border-red-500/20">
                        ⚠️ Reportar o Moderar
                    </button>
                @endif
            </div>

            <!-- Profile Info (View Mode) -->
            <div x-show="!editingProfile" x-transition.opacity class="px-8 pb-10">
                <div class="relative flex justify-between items-end -mt-16 sm:-mt-24 mb-6">
                    <div class="w-32 h-32 sm:w-40 sm:h-40 rounded-full border-4 border-white dark:border-[#1e293b] bg-gradient-to-br from-[#1A3C5E] to-[#C4A35A] flex items-center justify-center text-5xl sm:text-6xl text-white font-bold shadow-xl overflow-hidden">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-gray-100">{{ $user->name }} {{ $user->lastname }}</h1>
                        <p class="text-lg text-gray-600 dark:text-gray-400 font-medium mt-1">
                            🎓 {{ $user->career->nombre ?? 'Sin carrera registrada' }}
                        </p>
                    </div>

                    <div class="py-6 border-t border-b border-gray-100 dark:border-gray-800">
                        <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Sobre mí</h3>
                        @if($user->descripcion)
                            <p class="text-gray-800 dark:text-gray-200 leading-relaxed text-lg whitespace-pre-line">{{ $user->descripcion }}</p>
                        @else
                            <p class="text-gray-400 dark:text-gray-500 italic">Este usuario aún no ha escrito una descripción.</p>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400 font-medium">
                        <span class="flex items-center gap-1.5 bg-gray-100 dark:bg-gray-800 px-3 py-1.5 rounded-lg">
                            <span>📅</span> Miembro desde {{ $user->created_at->translatedFormat('F Y') }}
                        </span>
                        <span class="flex items-center gap-1.5 bg-gray-100 dark:bg-gray-800 px-3 py-1.5 rounded-lg">
                            <span>📝</span> {{ $posts->total() }} Publicaciones
                        </span>
                    </div>

                    <!-- Master Request Logic for own profile -->
                    @if($isOwnProfile && auth()->user()->role_id == 1)
                        @php
                            $hasPendingRequest = \App\Models\MasterRequest::where('user_id', auth()->id())->where('status', 'pending')->exists();
                        @endphp
                        <div class="mt-6 p-5 bg-purple-50 dark:bg-purple-900/20 border border-purple-100 dark:border-purple-800/50 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                            <div>
                                <h4 class="font-bold text-purple-900 dark:text-purple-300 flex items-center gap-2">
                                    Programa Master 👑
                                </h4>
                                <p class="text-sm text-purple-700 dark:text-purple-400 mt-1">Conviértete en usuario verificado y obtén permisos para moderar contenido.</p>
                            </div>
                            @if(!$hasPendingRequest)
                                <form action="{{ route('profile.request-master') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="whitespace-nowrap bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-md transition-all hover:-translate-y-0.5">
                                        Solicitar Ascenso
                                    </button>
                                </form>
                            @else
                                <div class="bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-400 px-4 py-2 rounded-xl font-bold text-sm border border-yellow-200 dark:border-yellow-800 shadow-inner">
                                    ⏳ Solicitud en revisión
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Profile Form (Edit Mode) -->
            @if($isOwnProfile)
            <div x-show="editingProfile" style="display: none;" x-transition.opacity class="px-8 pb-10 pt-8 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 shadow-inner">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <span>⚙️</span> Actualizar Información
                </h2>
                <form action="{{ route('profile.update-description') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Nombre</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-gray-900 dark:text-gray-100 outline-none focus:ring-2 focus:ring-[#C4A35A] transition-all shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Apellidos</label>
                            <input type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}" class="w-full bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-gray-900 dark:text-gray-100 outline-none focus:ring-2 focus:ring-[#C4A35A] transition-all shadow-sm" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Carrera Profesional</label>
                        <select name="career_id" class="w-full bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-gray-900 dark:text-gray-100 outline-none focus:ring-2 focus:ring-[#C4A35A] transition-all shadow-sm cursor-pointer" required>
                            @foreach(\App\Models\Career::all() as $career)
                                <option value="{{ $career->id }}" {{ (old('career_id', $user->career_id) == $career->id) ? 'selected' : '' }}>
                                    {{ $career->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Biografía / Sobre mí</label>
                        <textarea name="descripcion" rows="4" placeholder="Cuéntale a la comunidad sobre ti..." class="w-full bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 text-gray-900 dark:text-gray-100 outline-none focus:ring-2 focus:ring-[#C4A35A] transition-all resize-y shadow-sm">{{ old('descripcion', $user->descripcion) }}</textarea>
                        <p class="text-sm text-gray-500 mt-2 font-medium">Máximo 500 caracteres.</p>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700 mt-8">
                        <button type="button" @click="editingProfile = false" class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold rounded-xl transition-all shadow-sm">
                            Cancelar
                        </button>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-[#1A3C5E] to-[#2A6B9E] hover:from-[#C4A35A] hover:to-[#D4B06A] text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all hover:-translate-y-0.5 flex items-center gap-2">
                            <span>💾</span> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>

        <!-- User's Posts Section -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                <span class="bg-[#C4A35A]/20 text-[#C4A35A] p-2 rounded-xl">📝</span> 
                Publicaciones de {{ $user->name }}
            </h2>

            <div class="space-y-6">
                @forelse($posts as $post)
                    <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-md border border-gray-100 dark:border-gray-800 p-6 sm:p-8 hover:shadow-xl transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-bold bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-lg">
                                {{ $post->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <a href="{{ route('posts.show', $post) }}" class="block">
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100 group-hover:text-[#C4A35A] transition-colors leading-tight mb-3">
                                {{ $post->title }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 line-clamp-3 leading-relaxed text-lg">
                                {{ $post->content }}
                            </p>
                        </a>
                        <div class="mt-6 flex items-center gap-5 pt-4 border-t border-gray-100 dark:border-gray-800">
                            <a href="{{ route('posts.show', $post) }}" class="inline-flex items-center gap-2 text-sm font-bold text-white bg-gradient-to-r from-[#1A3C5E] to-[#2A6B9E] px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all">
                                Leer más
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-bold flex items-center gap-1.5">
                                <span class="text-lg">💬</span> {{ $post->comments->count() }}
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-bold flex items-center gap-1.5">
                                <span class="text-lg">⭐</span> {{ $post->reactions->count() }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-lg border border-gray-100 dark:border-gray-800 p-12 text-center">
                        <span class="text-7xl opacity-50 mb-6 block">📭</span>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No hay publicaciones</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-lg">Este usuario aún no ha compartido nada con la comunidad.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>
        
    </div>
</body>
</html>