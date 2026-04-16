<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-6">📝 Crear nueva publicación</h2>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 font-bold mb-2">Título *</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="block text-gray-700 font-bold mb-2">Contenido *</label>
                            <textarea name="content" id="content" rows="6" 
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      required>{{ old('content') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="images" class="block text-gray-700 font-bold mb-2">Imágenes (máx. 5, 2MB cada una)</label>
                            <input type="file" name="images[]" id="images" multiple accept="image/jpeg,image/png,image/jpg"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="text-sm text-gray-500 mt-1">Formatos permitidos: JPG, PNG. Tamaño máximo: 2MB por imagen.</p>
                        </div>

                        <!-- Vista previa de imágenes -->
                        <div id="preview-container" class="grid grid-cols-2 gap-2 mb-4 hidden">
                            <!-- Aquí se mostrarán las previsualizaciones -->
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('feed') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Publicar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Vista previa de imágenes
        const imagesInput = document.getElementById('images');
        const previewContainer = document.getElementById('preview-container');
        
        imagesInput.addEventListener('change', function() {
            previewContainer.innerHTML = '';
            previewContainer.classList.add('hidden');
            
            const files = Array.from(this.files);
            
            if (files.length > 0) {
                previewContainer.classList.remove('hidden');
                files.forEach(file => {
                    const reader = new FileReader();
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'relative';
                    
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-full h-32 object-cover rounded-lg';
                        previewDiv.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                    previewContainer.appendChild(previewDiv);
                });
            }
        });
    </script>
    @endpush
</x-app-layout>