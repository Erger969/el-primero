<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Información del perfil -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center gap-6">
                        <!-- Avatar por defecto -->
                        <div class="w-24 h-24 bg-gray-300 rounded-full flex items-center justify-center text-3xl">
                            👤
                        </div>
                        
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold">{{ $user->name }} {{ $user->lastname }}</h1>
                            <p class="text-gray-600">{{ $user->career->nombre ?? 'Sin carrera' }}</p>
                            <p class="text-sm text-gray-400">Miembro desde {{ $user->created_at->format('d/m/Y') }}</p>
                            
                            @if($user->descripcion)
                                <p class="mt-2 text-gray-700">{{ $user->descripcion }}</p>
                            @else
                                <p class="mt-2 text-gray-400 italic">Sin descripción aún.</p>
                            @endif
                        </div>
                        
                        @if($isOwnProfile)
                            <div>
                                <a href="{{ route('profile.edit') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Editar perfil
                                </a>
                            </div>
                        @else
                            <div>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Reportar usuario
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Publicaciones del usuario -->
            <h2 class="text-xl font-bold mb-4">📝 Publicaciones de {{ $user->name }}</h2>
            
            @forelse($posts as $post)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="p-4">
                        <a href="{{ route('posts.show', $post) }}">
                            <h3 class="font-bold text-lg hover:text-blue-600">{{ $post->title }}</h3>
                        </a>
                        <p class="text-gray-600 text-sm mt-1">{{ Str::limit($post->content, 150) }}</p>
                        <p class="text-xs text-gray-400 mt-2">{{ $post->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500">
                        {{ $user->name }} aún no ha publicado nada.
                    </div>
                </div>
            @endforelse
            
            <div class="mt-4">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>