@extends('layouts.admin')

@section('header_title', 'Gestión de Publicaciones')

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
    <div class="p-8">
        <!-- Filtros -->
        <div class="mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Buscar por título</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                        </span>
                        <input type="text" name="search" placeholder="Título de la publicación..." 
                               value="{{ request('search') }}"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-blue-500 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Estado</label>
                    <select name="hidden" class="w-full py-2.5 bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-blue-500 transition-all cursor-pointer">
                        <option value="">Todas</option>
                        <option value="no" {{ request('hidden') == 'no' ? 'selected' : '' }}>Solo visibles</option>
                        <option value="yes" {{ request('hidden') == 'yes' ? 'selected' : '' }}>Solo ocultas</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                        <x-heroicon-o-funnel class="w-5 h-5" />
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de publicaciones -->
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                        <th class="pb-4 px-4">Publicación</th>
                        <th class="pb-4 px-4">Autor</th>
                        <th class="pb-4 px-4 text-center">Estado</th>
                        <th class="pb-4 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                    @foreach($posts as $post)
                    <tr class="group hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                        <td class="py-5 px-4">
                            <div class="flex items-center gap-4">
                                @php $postImages = is_array($post->images) ? $post->images : json_decode($post->images, true); @endphp
                                @if(is_array($postImages) && count($postImages) > 0)
                                    <img src="{{ $postImages[0] }}" class="w-12 h-12 rounded-lg object-cover shadow-sm">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-400">
                                        <x-heroicon-o-photo class="w-6 h-6" />
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-white truncate max-w-xs">{{ $post->title }}</p>
                                    <p class="text-[10px] text-slate-500">{{ $post->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-4">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-medium text-slate-600 dark:text-slate-400">{{ $post->user->name }} {{ $post->user->lastname }}</span>
                            </div>
                        </td>
                        <td class="py-5 px-4 text-center">
                            @if($post->is_hidden)
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-rose-100 text-rose-700 border border-rose-200 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800">
                                    Oculto
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase bg-emerald-100 text-emerald-700 border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800">
                                    Visible
                                </span>
                            @endif
                        </td>
                        <td class="py-5 px-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('posts.show', $post) }}" target="_blank" class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all" title="Ver">
                                    <x-heroicon-o-eye class="w-5 h-5" />
                                </a>
                                <a href="{{ route('admin.posts.edit', $post->id) }}" class="p-2 text-slate-400 hover:text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-all" title="Editar">
                                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                                </a>
                                
                                @if($post->is_hidden)
                                    <form action="{{ route('admin.posts.show', $post->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="p-2 text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-all" title="Hacer Visible">
                                            <x-heroicon-o-lock-open class="w-5 h-5" />
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.posts.hide', $post->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Ocultar esta publicación?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-all" title="Ocultar">
                                            <x-heroicon-o-lock-closed class="w-5 h-5" />
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('¿Eliminar definitivamente?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-all" title="Eliminar">
                                        <x-heroicon-o-trash class="w-5 h-5" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $posts->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection