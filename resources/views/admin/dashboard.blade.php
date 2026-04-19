<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6">📊 Dashboard del Administrador</h1>
                    
                    <!-- Tarjetas de estadísticas generales -->
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-8">
                        <div class="bg-blue-100 p-4 rounded-lg text-center">
                            <h3 class="text-lg font-bold">👥 Usuarios</h3>
                            <p class="text-3xl font-bold">{{ $totalUsers }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg text-center">
                            <h3 class="text-lg font-bold">📝 Publicaciones</h3>
                            <p class="text-3xl font-bold">{{ $totalPosts }}</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-lg text-center">
                            <h3 class="text-lg font-bold">💬 Comentarios</h3>
                            <p class="text-3xl font-bold">{{ $totalComments }}</p>
                        </div>
                        <div class="bg-red-100 p-4 rounded-lg text-center">
                            <h3 class="text-lg font-bold">⚠️ Reportes</h3>
                            <p class="text-3xl font-bold">{{ $totalReports }}</p>
                        </div>
                        <div class="bg-purple-100 p-4 rounded-lg text-center">
                            <h3 class="text-lg font-bold">👑 Solicitudes</h3>
                            <p class="text-3xl font-bold">{{ $totalMasterRequests }}</p>
                        </div>
                        <div class="bg-indigo-100 p-4 rounded-lg text-center">
                            <h3 class="text-lg font-bold">📅 Hoy</h3>
                            <p class="text-3xl font-bold">{{ $postsToday }}</p>
                        </div>
                    </div>
                    
                    <!-- Publicaciones por período -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <div class="bg-gray-100 p-4 rounded-lg text-center">
                            <h3 class="text-lg font-bold">📆 Esta semana</h3>
                            <p class="text-2xl font-bold">{{ $postsThisWeek }} publicaciones</p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg text-center">
                            <h3 class="text-lg font-bold">📆 Este mes</h3>
                            <p class="text-2xl font-bold">{{ $postsThisMonth }} publicaciones</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Top 5 publicaciones con más comentarios -->
                        <div class="border rounded-lg p-4">
                            <h2 class="text-xl font-bold mb-4">💬 Top 5 con más comentarios</h2>
                            @foreach($topCommentedPosts as $post)
                                <div class="py-2 border-b">
                                    <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:underline">
                                        {{ $post->title }}
                                    </a>
                                    <p class="text-sm text-gray-500">{{ $post->comments_count }} comentarios - por {{ $post->user->name }}</p>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Top 5 publicaciones con más reacciones -->
                        <div class="border rounded-lg p-4">
                            <h2 class="text-xl font-bold mb-4">❤️ Top 5 con más reacciones</h2>
                            @foreach($topReactedPosts as $post)
                                <div class="py-2 border-b">
                                    <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:underline">
                                        {{ $post->title }}
                                    </a>
                                    <p class="text-sm text-gray-500">{{ $post->reactions_count }} reacciones - por {{ $post->user->name }}</p>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Top 5 usuarios que más publican -->
                        <div class="border rounded-lg p-4">
                            <h2 class="text-xl font-bold mb-4">🏆 Top 5 usuarios que más publican</h2>
                            @foreach($topUsers as $user)
                                <div class="flex justify-between items-center py-2 border-b">
                                    <span>{{ $user->name }} {{ $user->lastname }}</span>
                                    <span class="font-bold">{{ $user->posts_count }} publicaciones</span>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Usuarios por carrera -->
                        <div class="border rounded-lg p-4">
                            <h2 class="text-xl font-bold mb-4">🎓 Usuarios por carrera</h2>
                            @foreach($usersByCareer as $career)
                                <div class="flex justify-between items-center py-2 border-b">
                                    <span>{{ $career->career->nombre ?? 'Sin carrera' }}</span>
                                    <span class="font-bold">{{ $career->total }} usuarios</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Enlaces rápidos a gestión -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                        <a href="{{ route('admin.users.index') }}" class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center transition">
                            👥 Gestionar Usuarios
                        </a>
                        <a href="{{ route('admin.careers.index') }}" class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center transition">
                            📚 Gestionar Carreras   
                        </a>
                        <a href="{{ route('admin.posts.index') }}" class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center transition">
                            📝 Gestionar Publicaciones
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center transition">
                            ⚠️ Ver Reportes
                        </a>
                        <a href="{{ route('admin.master-requests.index') }}" class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center transition">
                            👑 Solicitudes Master
                        </a>
                        <a href="{{ route('admin.master-activity.index') }}" class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg text-center transition">
                            📋 Actividad de Masters
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>