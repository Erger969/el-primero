<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Botón volver -->
                    <div class="mb-4">
                        <a href="{{ route('feed') }}" class="text-blue-500 hover:underline">← Volver al feed</a>
                    </div>

                    <!-- Autor y fecha -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-lg">{{ $post->user->name }} {{ $post->user->lastname }}</h3>
                            <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                            @if($post->user->career)
                                <p class="text-xs text-gray-400">{{ $post->user->career->nombre }}</p>
                            @endif
                        </div>
                        @if(auth()->id() === $post->user_id)
                            <div class="flex gap-2">
                                <a href="{{ route('posts.edit', $post) }}" class="text-gray-500 hover:text-gray-700">✏️ Editar</a>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('¿Eliminar esta publicación?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">🗑️ Eliminar</button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Título y contenido -->
                    <h1 class="text-2xl font-bold mb-4">{{ $post->title }}</h1>
                    <p class="text-gray-700 mb-6 whitespace-pre-line">{{ $post->content }}</p>

                    <!-- Imágenes -->
                    @if($post->images)
                        @php $images = json_decode($post->images, true); @endphp
                        @if(is_array($images) && count($images) > 0)
                            <div class="grid grid-cols-2 gap-2 mb-6">
                                @foreach($images as $image)
                                    <img src="{{ $image }}" alt="Imagen" class="rounded-lg w-full h-48 object-cover">
                                @endforeach
                            </div>
                        @endif
                    @endif

                    <!-- Reacciones -->
                    <div class="flex gap-2 mb-6 border-t pt-4">
                        @php
                            $reactionTypes = [
                                'ya' => ['emoji' => '😊', 'label' => 'Ya', 'color' => 'bg-green-500'],
                                'ahh' => ['emoji' => '😮', 'label' => 'Ahh', 'color' => 'bg-yellow-500'],
                                'ehh' => ['emoji' => '🤔', 'label' => 'Ehh', 'color' => 'bg-purple-500'],
                                'ohh' => ['emoji' => '😲', 'label' => 'Ohh', 'color' => 'bg-red-500'],
                                'uhh' => ['emoji' => '😅', 'label' => 'Uhh', 'color' => 'bg-blue-500']
                            ];
                        @endphp
                        
                        @foreach($reactionTypes as $key => $reaction)
                            <button 
                                class="reaction-btn px-4 py-2 rounded-lg font-semibold transition
                                       {{ $userReaction && $userReaction->type === $key 
                                           ? $reaction['color'] . ' text-white' 
                                           : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
                                data-post-id="{{ $post->id }}"
                                data-type="{{ $key }}">
                                {{ $reaction['emoji'] }} {{ $reaction['label'] }}
                                <span class="count-{{ $key }} ml-1">({{ $post->reactions->where('type', $key)->count() }})</span>
                            </button>
                        @endforeach
                    </div>

                    <!-- Comentarios -->
                    <div class="border-t pt-4">
                        <h3 class="font-bold mb-4">Comentarios ({{ $post->comments->count() }})</h3>
                        
                        @foreach($post->comments as $comment)
                            <div class="mb-3 pb-2 border-b">
                                <div class="flex justify-between">
                                    <strong>{{ $comment->user->name }}</strong>
                                    <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-gray-600 text-sm">{{ $comment->content }}</p>
                            </div>
                        @endforeach

                        <!-- Formulario para comentar -->
                        <form action="{{ route('comments.store', $post) }}" method="POST" class="mt-4">
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
        </div>
    </div>

    @push('scripts')
    <script>
        const reactionColors = {
            'ya': 'bg-green-500',
            'ahh': 'bg-yellow-500',
            'ehh': 'bg-purple-500',
            'ohh': 'bg-red-500',
            'uhh': 'bg-blue-500'
        };
        
        document.querySelectorAll('.reaction-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const postId = this.dataset.postId;
                const type = this.dataset.type;
                
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
                    
                    for (const [reactionType, count] of Object.entries(data.counts)) {
                        const counter = document.querySelector(`.count-${reactionType}`);
                        if (counter) counter.textContent = `(${count})`;
                    }
                    
                    const container = this.parentElement;
                    container.querySelectorAll('.reaction-btn').forEach(btn => {
                        btn.classList.remove('bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-red-500', 'bg-blue-500', 'text-white');
                        btn.classList.add('bg-gray-200', 'text-gray-700');
                    });
                    
                    this.classList.remove('bg-gray-200', 'text-gray-700');
                    this.classList.add(reactionColors[type], 'text-white');
                    
                } catch (error) {
                    console.error('Error:', error);
                }
            });
        });
    </script>
    @endpush
</x-app-layout>