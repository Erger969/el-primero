<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6">📋 Actividad de Masters</h1>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto shadow-md rounded-lg">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Master</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Publicación</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($activities as $activity)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        {{ $activity->master->name }} {{ $activity->master->lastname }}<br>
                                        <span class="text-xs text-gray-500">{{ $activity->master->email }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('posts.show', $activity->post) }}" target="_blank" class="text-blue-600 hover:underline">
                                            {{ Str::limit($activity->post->title, 40) }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($activity->action == 'hide')
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">🔒 Ocultó</span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">👁️ Mostró</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <form action="{{ route('admin.master-activity.revoke', $activity->master_id) }}" 
                                              method="POST" class="inline-block"
                                              onsubmit="return confirm('¿Revocar permisos de Master a {{ $activity->master->name }}? Volverá a ser Universitario.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                                ⛔ Revocar permisos
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        No hay actividades registradas.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>