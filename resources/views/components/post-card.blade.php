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
            @auth
                @if(auth()->id() === $post->user_id)
                    <a href="{{ route('posts.edit', $post) }}" class="text-gray-500 hover:text-gray-700">✏️ Editar</a>
                @endif
            @endauth
        </div>

        <!-- Reportar -->
        @if(auth()->id() !== $post->user_id)
            <button type="button" 
                    onclick="openReportModal({{ $post->id }})"
                    class="text-gray-400 hover:text-red-500 text-sm">
                🚨 Reportar
            </button>
        @endif

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
        <div class="flex gap-2 mb-4">
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

        <!-- Comentarios (cambia según autenticación) -->
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
                {{-- Usuario logueado: puede comentar --}}
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

        <!-- Modal para reportar -->
        <div id="reportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-bold mb-4">Reportar publicación</h3>
                <form id="reportForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Motivo del reporte</label>
                        <select name="category" required class="w-full border-gray-300 rounded-md shadow-sm">
                            <option value="spam">Spam o contenido engañoso</option>
                            <option value="acoso">Acoso o intimidación</option>
                            <option value="ofensivo">Contenido ofensivo</option>
                            <option value="desinformacion">Desinformación</option>
                            <option value="otro">Otro motivo</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Descripción adicional (opcional)</label>
                        <textarea name="reason" rows="3" class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeReportModal()" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
                            Cancelar
                        </button>
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded">
                            Enviar reporte
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            let currentPostId = null;
            
            function openReportModal(postId) {
                currentPostId = postId;
                const form = document.getElementById('reportForm');
                form.action = `/posts/${postId}/report`;
                document.getElementById('reportModal').classList.remove('hidden');
                document.getElementById('reportModal').classList.add('flex');
            }
            
            function closeReportModal() {
                document.getElementById('reportModal').classList.add('hidden');
                document.getElementById('reportModal').classList.remove('flex');
            }
        </script> 
    </div>
</div>