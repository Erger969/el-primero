<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6">📝 Gestión de Publicaciones</h1>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Filtros -->
                    <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <input type="text" name="search" placeholder="Buscar por título..." 
                               value="{{ request('search') }}"
                               class="border-gray-300 rounded-md shadow-sm">

                        <select name="hidden" class="border-gray-300 rounded-md shadow-sm">
                            <option value="">Todas las publicaciones</option>
                            <option value="no" {{ request('hidden') == 'no' ? 'selected' : '' }}>Solo visibles</option>
                            <option value="yes" {{ request('hidden') == 'yes' ? 'selected' : '' }}>Solo ocultas</option>
                        </select>

                        <select name="user_id" class="border-gray-300 rounded-md shadow-sm">
                            <option value="">Todos los usuarios</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} {{ $user->lastname }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            🔍 Filtrar
                        </button>
                    </form>

                    <!-- Tabla de publicaciones -->
                    <div class="overflow-x-auto shadow-md rounded-lg">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-16">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-48">Título</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-40">Autor</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-28">Fecha</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-24">Estado</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase w-48">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($posts as $post)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $post->id }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <a href="{{ route('posts.show', $post) }}" target="_blank" class="text-blue-600 hover:underline">
                                            {{ Str::limit($post->title, 50) }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        {{ $post->user->name }} {{ $post->user->lastname }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        {{ $post->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if($post->is_hidden)
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                🔒 Oculto
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                ✅ Visible
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <div class="flex justify-center gap-1">
                                            <a href="{{ route('admin.posts.edit', $post->id) }}" 
                                               class="bg-blue-500 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs"
                                               title="Editar publicación">
                                                ✏️ Editar
                                            </a>
                                            
                                            @if($post->is_hidden)
                                                <form action="{{ route('admin.posts.show', $post->id) }}" 
                                                      method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-2 py-1 rounded text-xs"
                                                            title="Mostrar publicación">
                                                        👁️ Mostrar
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.posts.hide', $post->id) }}" 
                                                      method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white px-2 py-1 rounded text-xs"
                                                            onclick="return confirm('¿Ocultar esta publicación?')"
                                                            title="Ocultar publicación">
                                                        🔒 Ocultar
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('admin.posts.destroy', $post->id) }}" 
                                                  method="POST" class="inline-block"
                                                  onsubmit="return confirm('¿Eliminar esta publicación? Se borrarán también sus comentarios y reacciones.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-2 py-1 rounded text-xs"
                                                        title="Eliminar publicación">
                                                    🗑️ Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $posts->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>