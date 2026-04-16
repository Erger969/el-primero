<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Botón nueva publicación -->
            <div class="mb-6">
                <a href="{{ route('posts.create') }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    + Nueva publicación
                </a>
            </div>

            <!-- Publicaciones -->
            @forelse($posts as $post)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-lg">{{ $post->user->name }} {{ $post->user->lastname }}</h3>
                                <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <h2 class="text-xl font-bold mt-4 mb-2">{{ $post->title }}</h2>
                        <p class="text-gray-700 mb-4">{{ $post->content }}</p>

                        <!-- Reacciones -->
                        <div class="flex gap-2 mb-4">
                            @php
                                $reactionTypes = ['ya' => '😊', 'ahh' => '😮', 'ehh' => '🤔', 'ohh' => '😲', 'uhh' => '😅'];
                                $currentReaction = $userReactions[$post->id]->type ?? null;
                            @endphp
                            
                            @foreach($reactionTypes as $key => $emoji)
                                <button 
                                    class="reaction-btn px-3 py-1 rounded transition {{ $currentReaction === $key ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}"
                                    data-post-id="{{ $post->id }}"
                                    data-type="{{ $key }}">
                                    {{ $emoji }} {{ $key }}
                                    <span class="count-{{ $key }}">({{ $post->reactions->where('type', $key)->count() }})</span>
                                </button>
                            @endforeach
                        </div>

                        <!-- Mostrar comentarios existentes -->
                        <div class="border-t pt-4 mt-2">
                            <h4 class="font-bold mb-2">Comentarios ({{ $post->comments->count() }})</h4>
                            
                            @foreach($post->comments as $comment)
                                <div class="mb-2 text-sm border-b pb-1">
                                    <strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}
                                    <div class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</div>
                                </div>
                            @endforeach

                            <!-- Formulario para nuevo comentario -->
                            <form action="{{ route('comments.store', $post) }}" method="POST" class="mt-2">
                                @csrf
                                <div class="flex gap-2">
                                    <input type="text" name="content" placeholder="Escribe un comentario..." 
                                           class="flex-1 border-gray-300 rounded-md shadow-sm text-sm">
                                    <button type="submit" class="text-sm bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                        Comentar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500">
                        No hay publicaciones aún. ¡Sé el primero en publicar!
                    </div>
                </div>
            @endforelse

            <!-- Paginación -->
            <div class="mt-4">
                {{ $posts->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Script de reacciones cargado'); // Debug
            
            document.querySelectorAll('.reaction-btn').forEach(btn => {
                btn.addEventListener('click', async function() {
                    const postId = this.dataset.postId;
                    const type = this.dataset.type;
                    
                    console.log('Reaccionando a post:', postId, 'con tipo:', type);
                    
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
                        console.log('Respuesta:', data);
                        
                        // Actualizar contadores
                        for (const [reactionType, count] of Object.entries(data.counts)) {
                            const counter = document.querySelector(`.count-${reactionType}[data-post-id="${postId}"]`);
                            if (counter) counter.textContent = `(${count})`;
                        }
                        
                        // Actualizar botón activo
                        const container = this.parentElement;
                        container.querySelectorAll('.reaction-btn').forEach(btn => {
                            btn.classList.remove('bg-blue-500', 'text-white');
                            btn.classList.add('bg-gray-200');
                        });
                        this.classList.remove('bg-gray-200');
                        this.classList.add('bg-blue-500', 'text-white');
                        
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error al reaccionar. Revisa la consola.');
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>