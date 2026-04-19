@props(['post', 'userReactions' => null])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6">
        <!-- Encabezado: autor y fecha -->
        <div class="flex justify-between items-start">
            <div>
                <h3 class="font-bold text-lg">{{ $post->user->name }} {{ $post->user->lastname }}</h3>
                <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                @if($post->user->career)
                    <p class="text-xs text-gray-400">{{ $post->user->career->nombre }}</p>
                @endif
            </div>
            <div class="flex gap-2">
                @auth
                    @if(auth()->id() === $post->user_id)
                        <a href="{{ route('posts.edit', $post) }}" class="text-gray-500 hover:text-gray-700">✏️ Editar</a>
                    @endif
                    
                    @if(auth()->id() !== $post->user_id)
                        <button type="button" 
                                onclick="openReportModal({{ $post->id }})"
                                class="text-gray-400 hover:text-red-500 text-sm">
                            🚨 Reportar
                        </button>
                    @endif
                    
                    @if(auth()->user()->role_id == 2 && auth()->id() !== $post->user_id)
                        <form action="{{ route('master.posts.hide', $post) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" class="text-yellow-600 hover:text-yellow-800 text-sm ml-2">
                                @if($post->is_hidden)
                                    👁️ Mostrar
                                @else
                                    🔒 Ocultar
                                @endif
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Título y contenido -->
        <a href="{{ route('posts.show', $post) }}">
            <h2 class="text-xl font-bold mt-4 mb-2 hover:text-blue-600">{{ $post->title }}</h2>
        </a>
        <p class="text-gray-700 mb-4">{{ $post->content }}</p>

        <!-- Imágenes (si tiene) -->
        @if($post->images)
            @php $images = json_decode($post->images, true); @endphp
            @if(is_array($images) && count($images) > 0)
                <div class="grid grid-cols-2 gap-2 mb-4">
                    @foreach($images as $image)
                        <img src="{{ $image }}" alt="Imagen" class="rounded-lg w-full h-32 object-cover">
                    @endforeach
                </div>
            @endif
        @endif

        <!-- Reacciones (cambia según autenticación) -->
        <div class="flex gap-2 mb-4 flex-wrap">
            @auth
                {{-- Usuario logueado: botones interactivos --}}
                @php
                    $reactionTypes = [
                        'ya' => ['emoji' => '😊', 'label' => 'Ya', 'color' => 'bg-green-500'],
                        'ahh' => ['emoji' => '😮', 'label' => 'Ahh', 'color' => 'bg-yellow-500'],
                        'ehh' => ['emoji' => '🤔', 'label' => 'Ehh', 'color' => 'bg-purple-500'],
                        'ohh' => ['emoji' => '😲', 'label' => 'Ohh', 'color' => 'bg-red-500'],
                        'uhh' => ['emoji' => '😅', 'label' => 'Uhh', 'color' => 'bg-blue-500']
                    ];
                    $currentReaction = $userReactions[$post->id]->type ?? null;
                @endphp
                
                @foreach($reactionTypes as $key => $reaction)
                    <button 
                        class="reaction-btn px-4 py-2 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105
                               {{ $currentReaction === $key 
                                   ? $reaction['color'] . ' text-white shadow-md' 
                                   : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
                        data-post-id="{{ $post->id }}"
                        data-type="{{ $key }}">
                        <span class="text-lg">{{ $reaction['emoji'] }}</span>
                        <span class="ml-1">{{ $reaction['label'] }}</span>
                        <span class="count-{{ $key }} ml-1 text-sm font-bold">
                            ({{ $post->reactions->where('type', $key)->count() }})
                        </span>
                    </button>
                @endforeach
            @else
                {{-- Usuario no logueado: solo contadores (sin botones) --}}
                @php
                    $reactionTypes = ['ya' => '😊', 'ahh' => '😮', 'ehh' => '🤔', 'ohh' => '😲', 'uhh' => '😅'];
                @endphp
                @foreach($reactionTypes as $key => $emoji)
                    <div class="px-4 py-2 rounded-lg bg-gray-100 text-gray-600">
                        <span class="text-lg">{{ $emoji }}</span>
                        <span class="ml-1">{{ $key }}</span>
                        <span class="ml-1 text-sm font-bold">
                            ({{ $post->reactions->where('type', $key)->count() }})
                        </span>
                    </div>
                @endforeach
                <div class="text-sm text-gray-400 ml-2">
                    🔒 <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Inicia sesión</a> para reaccionar
                </div>
            @endauth
        </div>

        <!-- Comentarios -->
        <div class="border-t pt-4 mt-2">
            <h4 class="font-bold mb-2">Comentarios ({{ $post->comments->count() }})</h4>
            
            @foreach($post->comments->take(5) as $comment)
                <div class="mb-2 text-sm border-b pb-1">
                    <strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}
                    <div class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</div>
                </div>
            @endforeach

            @if($post->comments->count() > 5)
                <div class="text-sm text-gray-500 mt-1">
                    y {{ $post->comments->count() - 5 }} comentarios más...
                </div>
            @endif

            @auth
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
            @else
                <div class="text-sm text-gray-400 mt-2">
                    🔒 <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Inicia sesión</a> para comentar
                </div>
            @endauth
        </div>
    </div>
</div>