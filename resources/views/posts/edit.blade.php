<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{}" 
      x-init="$store.darkMode = { value: localStorage.getItem('darkMode') === 'true', toggle() { this.value = !this.value; localStorage.setItem('darkMode', this.value); if (this.value) { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); } } }"
      :class="$store.darkMode.value ? 'dark' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UniSocial - Editar Publicación</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
    @include('partials.navbar')
    
    <!-- MAIN CONTENT -->
    <div class="relative z-10 flex-1 py-8 px-4 sm:px-6 lg:px-8 max-w-3xl w-full mx-auto">
        
        <!-- Back Button -->
        <a href="{{ route('posts.show', $post) }}" class="mb-6 inline-flex items-center gap-2 px-5 py-2.5 text-white bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/10 rounded-xl transition-all shadow-lg hover:-translate-x-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span class="font-bold tracking-wide">Volver a la publicación</span>
        </a>

        <!-- Edit Form Card -->
        <div class="bg-white dark:bg-[#1e293b] rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800 animate-fade-in">
            <div class="p-8 border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/40">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-3">
                    <span class="text-3xl">✏️</span> 
                    Editar Publicación
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Modifica los detalles de tu publicación. Los cambios quedarán registrados.</p>
            </div>

            <div class="p-8">
                <form action="{{ route('posts.update', $post) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="title" class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-2 ml-1">Título</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required
                               class="w-full px-5 py-3.5 bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#C4A35A] focus:border-transparent transition-all shadow-sm">
                        @error('title') <p class="text-red-500 text-xs mt-1.5 ml-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-2 ml-1">Contenido</label>
                        <textarea name="content" id="content" rows="8" required
                                  class="w-full px-5 py-3.5 bg-gray-50 dark:bg-[#0f172a] border border-gray-200 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#C4A35A] focus:border-transparent transition-all shadow-sm resize-none">{{ old('content', $post->content) }}</textarea>
                        @error('content') <p class="text-red-500 text-xs mt-1.5 ml-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <!-- Images Preview -->
                    @php $postImages = is_array($post->images) ? $post->images : json_decode($post->images, true); @endphp
                    @if(is_array($postImages) && count($postImages) > 0)
                        <div class="mt-6">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-3 ml-1">Imágenes Adjuntas</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                @foreach($postImages as $image)
                                    <div class="relative aspect-square rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm">
                                        <img src="{{ $image }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 ml-1 italic">* La gestión de imágenes se realiza desde el panel administrativo si eres Master.</p>
                        </div>
                    @endif

                    <div class="pt-6 mt-6 border-t border-gray-100 dark:border-gray-800 flex flex-col-reverse sm:flex-row justify-end gap-3">
                        <a href="{{ route('posts.show', $post) }}" class="w-full sm:w-auto px-6 py-3 text-center text-sm font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-[#1A3C5E] to-[#2A6B9E] hover:from-[#C4A35A] hover:to-[#D4B06A] text-white font-bold rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <span>Guardar Cambios</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>