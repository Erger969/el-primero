<x-app-layout>
    <style>
        @media (max-width: 768px) {
            .overflow-x-auto {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            table {
                min-width: 800px;
            }
        }
    </style>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6">👥 Gestión de Usuarios</h1>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Filtros -->
                    <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <input type="text" name="search" placeholder="Buscar por nombre o email..." 
                               value="{{ request('search') }}"
                               class="border-gray-300 rounded-md shadow-sm">

                        <select name="role" class="border-gray-300 rounded-md shadow-sm">
                            <option value="">Todos los roles</option>
                            @foreach($roles as $key => $role)
                                <option value="{{ $key }}" {{ request('role') == $key ? 'selected' : '' }}>
                                    {{ $role }}
                                </option>
                            @endforeach
                        </select>

                        <select name="career" class="border-gray-300 rounded-md shadow-sm">
                            <option value="">Todas las carreras</option>
                            @foreach($careers as $career)
                                <option value="{{ $career->id }}" {{ request('career') == $career->id ? 'selected' : '' }}>
                                    {{ $career->nombre }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            🔍 Filtrar
                        </button>
                    </form>

                    <!-- Tabla de usuarios -->
                    <div class="overflow-x-auto shadow-md rounded-lg">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">Nombre</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-56">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Carrera</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Rol</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Registro</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $user->id }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                        {{ $user->name }} {{ $user->lastname }}
                                    </td>
                                    <td class="px-4 py-3 text-sm break-all">
                                        <a href="mailto:{{ $user->email }}" class="text-blue-600 hover:underline">
                                            {{ $user->email }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        {{ $user->career->nombre ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold inline-block w-24 text-center
                                            @if($user->role_id == 3) bg-red-100 text-red-800
                                            @elseif($user->role_id == 2) bg-purple-100 text-purple-800
                                            @elseif($user->role_id == 4) bg-gray-300 text-gray-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            {{ $roles[$user->role_id] ?? 'Desconocido' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <div class="flex justify-center gap-1">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                                            class="bg-blue-500 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs"
                                            title="Editar usuario">
                                                ✏️ Editar
                                            </a>
                                            
                                            @if($user->role_id == 4)
                                                <form action="{{ route('admin.users.restore', $user->id) }}" 
                                                    method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-2 py-1 rounded text-xs"
                                                            title="Restaurar usuario">
                                                        🔄 Restaurar
                                                    </button>
                                                </form>
                                            @elseif($user->role_id != 3)
                                                <form action="{{ route('admin.users.suspend', $user->id) }}" 
                                                    method="POST" class="inline-flex items-center gap-1">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="duration" class="text-[10px] py-0.5 px-1 border-gray-300 rounded focus:ring-0 w-20">
                                                        <option value="1">1 día</option>
                                                        <option value="3">3 días</option>
                                                        <option value="7">7 días</option>
                                                        <option value="30">30 días</option>
                                                        <option value="permanent">Permanente</option>
                                                    </select>
                                                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white px-2 py-1 rounded text-xs"
                                                            onclick="return confirm('¿Suspender este usuario?')"
                                                            title="Suspender usuario">
                                                        ⛔ Suspender
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if($user->role_id != 3)
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                                    method="POST" class="inline-block"
                                                    onsubmit="return confirm('¿Eliminar este usuario? Se borrarán todas sus publicaciones y comentarios.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-2 py-1 rounded text-xs"
                                                            title="Eliminar usuario">
                                                        🗑️ Eliminar
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>