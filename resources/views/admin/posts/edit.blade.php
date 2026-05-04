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

                <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_hidden" class="sr-only peer" {{ $post->is_hidden ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-rose-500"></div>
                        <span class="ml-3 text-sm font-bold text-slate-600 dark:text-slate-400">Ocultar Publicación</span>
                    </label>
                    <p class="text-[10px] text-slate-400 italic">(Si se activa, no aparecerá en el feed público)</p>
                </div>

                @php $postImages = is_array($post->images) ? $post->images : json_decode($post->images, true); @endphp
                @if(is_array($postImages) && count($postImages) > 0)
                    <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-2xl" x-data="{ removed: [] }">
                        <h4 class="text-xs font-bold text-slate-400 uppercase mb-4 flex items-center gap-2">
                            <x-heroicon-o-photo class="w-4 h-4 text-blue-500" />
                            Multimedia Adjunta
                        </h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($postImages as $image)
                                <div class="relative aspect-square group" x-show="!removed.includes('{{ $image }}')">
                                    <img src="{{ $image }}" alt="Imagen" class="w-full h-full rounded-xl object-cover shadow-sm">
                                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center gap-2">
                                        <a href="{{ $image }}" target="_blank" class="p-2 bg-white rounded-full shadow-lg text-slate-800 hover:text-blue-500 transition-colors">
                                            <x-heroicon-o-arrows-pointing-out class="w-4 h-4" />
                                        </a>
                                        <button type="button" @click="removed.push('{{ $image }}')" class="p-2 bg-white rounded-full shadow-lg text-rose-500 hover:bg-rose-50 transition-colors">
                                            <x-heroicon-o-trash class="w-4 h-4" />
                                        </button>
                                    </div>
                                    <template x-if="removed.includes('{{ $image }}')">
                                        <input type="hidden" name="removed_images[]" value="{{ $image }}">
                                    </template>
                                </div>
                            @endforeach
                        </div>
                        <p x-show="removed.length > 0" class="mt-4 text-xs font-bold text-rose-500 flex items-center gap-2">
                            <x-heroicon-o-information-circle class="w-4 h-4" />
                            Hay imágenes marcadas para eliminar al guardar.
                        </p>
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