@extends('layouts.admin')

@section('header_title', 'Moderación de Comentarios')

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
    <div class="p-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Todos los Comentarios</h3>
                <p class="text-sm text-slate-500">Gestiona y elimina spam o contenido ofensivo de forma global.</p>
            </div>
            
            <form method="GET" class="w-full md:w-auto">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                    </span>
                    <input type="text" name="search" placeholder="Buscar texto o autor..." 
                           value="{{ request('search') }}"
                           class="w-full md:w-72 pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                        <th class="pb-4 px-4">Autor</th>
                        <th class="pb-4 px-4">Comentario</th>
                        <th class="pb-4 px-4">En Publicación</th>
                        <th class="pb-4 px-4 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                    @forelse($comments as $comment)
                    <tr class="group hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                        <td class="py-5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-400 text-xs">
                                    {{ substr($comment->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $comment->user->name }}</p>
                                    <p class="text-[10px] text-slate-500">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-4">
                            <p class="text-sm text-slate-600 dark:text-slate-300 italic">"{{ $comment->content }}"</p>
                        </td>
                        <td class="py-5 px-4">
                            <a href="{{ route('posts.show', $comment->post_id) }}" target="_blank" class="text-xs text-blue-500 hover:underline flex items-center gap-1">
                                Ver Post
                                <x-heroicon-o-arrow-top-right-on-square class="w-3 h-3" />
                            </a>
                        </td>
                        <td class="py-5 px-4 text-right">
                            <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este comentario definitivamente?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-all">
                                    <x-heroicon-o-trash class="w-5 h-5" />
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-slate-500">No se encontraron comentarios.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $comments->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
