@extends('layouts.admin')

@section('header_title', 'Editar Publicación')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden animate-fade-in">
        <div class="p-8">
            <!-- Info Banner -->
            <div class="mb-8 p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-600 dark:text-slate-400">
                        {{ substr($post->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $post->user->name }} {{ $post->user->lastname }}</p>
                        <p class="text-xs text-slate-500">Publicado el {{ $post->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs font-mono text-slate-400">ID: #{{ $post->id }}</span>
                </div>
            </div>

            <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Título de la Publicación</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-blue-500 transition-all">
                    @error('title') <p class="text-rose-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="content" class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Contenido de la Publicación</label>
                    <textarea name="content" id="content" rows="10" required
                              class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-blue-500 transition-all resize-none">{{ old('content', $post->content) }}</textarea>
                    @error('content') <p class="text-rose-500 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                @if(!empty($post->images) && count($post->images) > 0)
                    <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-2xl">
                        <h4 class="text-xs font-bold text-slate-400 uppercase mb-4 flex items-center gap-2">
                            <x-heroicon-o-photo class="w-4 h-4 text-blue-500" />
                            Multimedia Adjunta
                        </h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($post->images as $image)
                                <div class="relative aspect-square group">
                                    <img src="{{ $image }}" alt="Imagen" class="w-full h-full rounded-xl object-cover shadow-sm">
                                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center">
                                        <a href="{{ $image }}" target="_blank" class="p-2 bg-white rounded-full shadow-lg text-slate-800">
                                            <x-heroicon-o-arrows-pointing-out class="w-4 h-4" />
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-50 dark:border-slate-700/50">
                    <a href="{{ route('admin.posts.index') }}" class="px-6 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-700 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                        <x-heroicon-o-check class="w-5 h-5" />
                        Actualizar Publicación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection