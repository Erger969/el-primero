@extends('layouts.admin')

@section('header_title', 'Galería de Medios')

@section('content')
<div class="space-y-8 animate-fade-in">
    <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700">
        <div class="mb-8">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Auditoría Visual de Multimedia</h3>
            <p class="text-sm text-slate-500">Revisa todas las imágenes subidas por los usuarios en sus publicaciones.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            @forelse($postsWithImages as $post)
                @php $postImages = is_array($post->images) ? $post->images : json_decode($post->images, true); @endphp
                @if(is_array($postImages))
                    @foreach($postImages as $image)
                    <div class="group relative aspect-square bg-slate-100 dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700">
                        <img src="{{ $image }}" alt="Media" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-3 flex flex-col justify-end">
                            <p class="text-[10px] text-white font-bold truncate">{{ $post->user->name }}</p>
                            <div class="flex gap-2 mt-2">
                                <a href="{{ route('posts.show', $post) }}" target="_blank" class="flex-1 bg-white/20 hover:bg-white/40 backdrop-blur-md text-white text-[9px] font-bold py-1.5 rounded-lg text-center transition-colors">
                                    Ver Post
                                </a>
                                <a href="{{ route('admin.posts.edit', $post) }}" class="p-1.5 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
                                    <x-heroicon-o-pencil-square class="w-3 h-3" />
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
                @endif
            @empty
                <div class="col-span-full py-12 text-center text-slate-500">No se encontraron medios subidos.</div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $postsWithImages->links() }}
        </div>
    </div>
</div>
@endsection
