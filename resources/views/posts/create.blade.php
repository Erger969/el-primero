<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{}" 
      x-init="$store.darkMode = { value: localStorage.getItem('darkMode') === 'true', toggle() { this.value = !this.value; localStorage.setItem('darkMode', this.value); if (this.value) { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); } } }"
      :class="$store.darkMode.value ? 'dark' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UniSocial - Nueva Publicación</title>
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
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
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
    <div class="relative z-10 flex-1 py-10 px-4 sm:px-6 lg:px-8 max-w-[900px] w-full mx-auto flex flex-col justify-center animate-fade-in">
        
        <div class="w-full bg-white dark:bg-[#1e293b] rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800">
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#1A3C5E] to-[#2A6B9E] px-8 py-6 flex items-center justify-between">
                <h2 class="text-2xl sm:text-3xl font-bold text-white flex items-center gap-3">
                    <span class="text-3xl">📝</span> Crear nueva publicación
                </h2>
            </div>

            <!-- Form Body -->
            <div class="p-8 sm:p-10">
                @if ($errors->any())
                    <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-5 py-4 rounded-xl mb-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-2 font-bold">
                            <span>⚠️</span> Por favor corrige los siguientes errores:
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-sm ml-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label for="title" class="block text-gray-700 dark:text-gray-300 font-bold mb-2 text-lg">Título *</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="¿De qué trata tu publicación?"
                               class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-xl px-5 py-4 text-gray-900 dark:text-gray-100 outline-none focus:ring-2 focus:ring-[#C4A35A] transition-all shadow-inner text-lg"
                               required>
                    </div>

                    <div>
                        <label for="content" class="block text-gray-700 dark:text-gray-300 font-bold mb-2 text-lg">Contenido *</label>
                        <textarea name="content" id="content" rows="6" placeholder="Escribe todos los detalles aquí..."
                                  class="w-full bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-xl px-5 py-4 text-gray-900 dark:text-gray-100 outline-none focus:ring-2 focus:ring-[#C4A35A] transition-all shadow-inner text-base resize-y"
                                  required>{{ old('content') }}</textarea>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-800/50 p-6 rounded-2xl border border-dashed border-gray-300 dark:border-gray-600">
                        <label for="images" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">📸 Agregar Imágenes <span class="font-normal text-sm text-gray-500">(Opcional)</span></label>
                        <div class="relative mt-2 flex items-center justify-center w-full">
                            <input type="file" name="images[]" id="images" multiple accept="image/jpeg,image/png,image/jpg"
                                   class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-[#1A3C5E] file:text-white hover:file:bg-[#2A6B9E] file:transition-colors file:cursor-pointer cursor-pointer bg-white dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-xl shadow-inner">
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-3 flex items-center gap-1">
                            <span>ℹ️</span> Formatos permitidos: JPG, PNG. Máximo 5 imágenes de 2MB cada una.
                        </p>
                    </div>

                    <!-- Vista previa de imágenes -->
                    <div id="preview-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 hidden mt-6 p-4 bg-gray-50 dark:bg-[#0f172a] rounded-2xl border border-gray-200 dark:border-gray-700">
                        <!-- Aquí se mostrarán las previsualizaciones -->
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-800 mt-8">
                        <button type="button" onclick="window.history.back();" class="px-8 py-3.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition-all shadow-sm flex items-center justify-center gap-2">
                            <span>❌</span> Cancelar
                        </button>
                        <button type="submit" class="px-8 py-3.5 bg-gradient-to-r from-[#1A3C5E] to-[#2A6B9E] hover:from-[#C4A35A] hover:to-[#D4B06A] text-white font-bold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <span>🚀</span> Publicar ahora
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Vista previa de imágenes
        const imagesInput = document.getElementById('images');
        const previewContainer = document.getElementById('preview-container');
        
        imagesInput.addEventListener('change', function() {
            previewContainer.innerHTML = '';
            previewContainer.classList.add('hidden');
            
            const files = Array.from(this.files);
            
            if (files.length > 0) {
                previewContainer.classList.remove('hidden');
                
                // Limitar a 5 para la vista previa visual
                const filesToShow = files.slice(0, 5);
                
                filesToShow.forEach(file => {
                    const reader = new FileReader();
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'relative group rounded-xl overflow-hidden shadow-sm aspect-square border border-gray-200 dark:border-gray-700';
                    
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-500';
                        previewDiv.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                    previewContainer.appendChild(previewDiv);
                });
                
                if(files.length > 5) {
                    const extraDiv = document.createElement('div');
                    extraDiv.className = 'relative flex items-center justify-center rounded-xl bg-gray-200 dark:bg-gray-800 aspect-square border border-gray-300 dark:border-gray-700';
                    extraDiv.innerHTML = `<span class="text-xl font-bold text-gray-600 dark:text-gray-400">+${files.length - 5}</span>`;
                    previewContainer.appendChild(extraDiv);
                }
            }
        });
    </script>
</body>
</html>