<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Botón nueva publicación -->
            <div class="mb-6">
                <a href="{{ route('posts.create') }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    + Nueva publicación
                </a>
            </div>

            <!-- Publicaciones usando el componente -->
            @forelse($posts as $post)
                <x-post-card :post="$post" :userReactions="$userReactions" />
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
                        
                        // Actualizar contadores
                        for (const [reactionType, count] of Object.entries(data.counts)) {
                            const counter = document.querySelector(`.count-${reactionType}[data-post-id="${postId}"]`);
                            if (counter) counter.textContent = `(${count})`;
                        }
                        
                        // Actualizar botón activo
                        const container = this.parentElement;
                        container.querySelectorAll('.reaction-btn').forEach(btn => {
                            btn.classList.remove('bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-red-500', 'bg-blue-500', 'text-white', 'shadow-md');
                            btn.classList.add('bg-gray-200', 'text-gray-700');
                        });
                        
                        this.classList.remove('bg-gray-200', 'text-gray-700');
                        this.classList.add(reactionColors[type], 'text-white', 'shadow-md');
                        
                    } catch (error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>