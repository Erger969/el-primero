<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6">✏️ Editar Publicación</h1>

                    <div class="mb-4 p-3 bg-gray-50 rounded">
                        <p class="text-sm text-gray-600">
                            <strong>Autor:</strong> {{ $post->user->name }} {{ $post->user->lastname }}<br>
                            <strong>Fecha de creación:</strong> {{ $post->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <form action="{{ route('admin.posts.update', $post->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 font-bold mb-2">Título *</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="content" class="block text-gray-700 font-bold mb-2">Contenido *</label>
                            <textarea name="content" id="content" rows="8" 
                                      class="w-full border-gray-300 rounded-md shadow-sm" required>{{ old('content', $post->content) }}</textarea>
                            @error('content') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        @if($post->images)
                            @php $images = json_decode($post->images, true); @endphp
                            @if(is_array($images) && count($images) > 0)
                                <div class="mb-4">
                                    <label class="block text-gray-700 font-bold mb-2">Imágenes actuales</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach($images as $image)
                                            <img src="{{ $image }}" alt="Imagen" class="rounded-lg w-full h-24 object-cover">
                                        @endforeach
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">Para cambiar imágenes, edítalas desde el cliente.</p>
                                </div>
                            @endif
                        @endif

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.posts.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>