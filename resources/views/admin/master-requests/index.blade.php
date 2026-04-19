<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6">👑 Solicitudes de Ascenso a Master</h1>

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

                    <!-- Solicitudes pendientes -->
                    <h2 class="text-xl font-bold mb-4">⏳ Pendientes</h2>
                    <div class="overflow-x-auto shadow-md rounded-lg mb-8">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Carrera</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha de solicitud</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Publicaciones</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($requests as $request)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <strong>{{ $request->user->name }} {{ $request->user->lastname }}</strong><br>
                                        <span class="text-xs text-gray-500">{{ $request->user->email }}</span>
                                    </td>
                                    <td class="px-4 py-3">{{ $request->user->career->nombre ?? '—' }}</td>
                                    <td class="px-4 py-3">{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3">{{ $request->user->posts()->count() }} publicaciones</td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center gap-2">
                                            <form action="{{ route('admin.master-requests.approve', $request->id) }}" 
                                                  method="POST" class="inline-block">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-3 py-1 rounded text-sm"
                                                        onclick="return confirm('¿Aprobar a {{ $request->user->name }} como Master?')">
                                                    ✅ Aprobar
                                                </button>
                                            </form>
                                            
                                            <button type="button" 
                                                    onclick="openRejectModal({{ $request->id }})"
                                                    class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                                ❌ Rechazar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        No hay solicitudes pendientes.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $requests->links() }}

                    <!-- Historial de solicitudes procesadas -->
                    <h2 class="text-xl font-bold mb-4 mt-8">📜 Historial</h2>
                    <div class="overflow-x-auto shadow-md rounded-lg">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revisado por</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motivo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($history as $request)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">{{ $request->user->name }} {{ $request->user->lastname }}</td>
                                    <td class="px-4 py-3">
                                        @if($request->status == 'approved')
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">✅ Aprobado</span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">❌ Rechazado</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $request->reviewer->name ?? '—' }}</td>
                                    <td class="px-4 py-3">{{ $request->updated_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $request->rejection_reason ?? '—' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        No hay solicitudes procesadas.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $history->appends(['history_page' => $history->currentPage()])->links('pagination::default') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para rechazar -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="text-lg font-bold mb-4">Rechazar solicitud</h3>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Motivo del rechazo (opcional)</label>
                    <textarea name="rejection_reason" rows="3" class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeRejectModal()" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded">
                        Rechazar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentRequestId = null;
        
        function openRejectModal(requestId) {
            currentRequestId = requestId;
            const form = document.getElementById('rejectForm');
            form.action = `/admin/master-requests/${requestId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }
        
        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
        }
    </script>
</x-app-layout>