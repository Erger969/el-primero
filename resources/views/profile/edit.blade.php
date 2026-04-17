<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-6">✏️ Editar perfil</h2>
                    
                    <form action="{{ route('profile.update-description') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="descripcion" class="block text-gray-700 font-bold mb-2">Biografía</label>
                            <textarea name="descripcion" id="descripcion" rows="5" 
                                      class="w-full border-gray-300 rounded-md shadow-sm"
                                      placeholder="Cuéntanos sobre ti...">{{ old('descripcion', $user->descripcion) }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">Máximo 500 caracteres.</p>
                        </div>
                        
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('profile.show', $user->id) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>