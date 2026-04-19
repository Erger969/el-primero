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
    
    <!-- Modal para reportar (UN SOLO MODAL PARA TODA LA PÁGINA) -->
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
</x-app-layout>