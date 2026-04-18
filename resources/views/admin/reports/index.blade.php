<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6">⚠️ Bandeja de Reportes</h1>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto shadow-md rounded-lg">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-16">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-40">Reportado por</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-48">Publicación</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-24">Categoría</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-24">Estado</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-28">Fecha</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase w-48">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($reports as $report)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $report->id }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $report->user->name }} {{ $report->user->lastname }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <a href="{{ route('posts.show', $report->post) }}" target="_blank" class="text-blue-600 hover:underline">
                                            {{ Str::limit($report->post->title, 40) }}
                                        </a>
                                        <br>
                                        <span class="text-xs text-gray-500">Autor: {{ $report->post->user->name }}</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        @switch($report->category)
                                            @case('spam') Spam @break
                                            @case('acoso') Acoso @break
                                            @case('ofensivo') Ofensivo @break
                                            @case('desinformacion') Desinformación @break
                                            @default Otro
                                        @endswitch
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if($report->status == 'pending')
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                ⏳ Pendiente
                                            </span>
                                        @elseif($report->status == 'resolved')
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                ✅ Resuelto
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                ❌ Rechazado
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        {{ $report->created_at->format('d/m/Y H:i') }}
                                     </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <div class="flex justify-center gap-1">
                                            @if($report->status == 'pending')
                                                <form action="{{ route('admin.reports.hide-post', $report->id) }}" 
                                                      method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white px-2 py-1 rounded text-xs"
                                                            onclick="return confirm('¿Ocultar esta publicación?')">
                                                        🔒 Ocultar publicación
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('admin.reports.resolve', $report->id) }}" 
                                                      method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-2 py-1 rounded text-xs">
                                                        ✅ Marcar resuelto
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('admin.reports.reject', $report->id) }}" 
                                                      method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-2 py-1 rounded text-xs">
                                                        ❌ Rechazar
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 text-xs">Procesado</span>
                                            @endif
                                        </div>
                                     </td>
                                 </tr>
                                @endforeach
                            </tbody>
                         </table>
                    </div>

                    <div class="mt-4">
                        {{ $reports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>