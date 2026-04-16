<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">📊 Dashboard del Administrador</h1>
                    <p class="mb-4">Bienvenido, {{ Auth::user()->name }}. Este es el panel de control.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <div class="bg-blue-100 p-4 rounded-lg">
                            <h3 class="font-bold">Usuarios</h3>
                            <p class="text-2xl">{{ \App\Models\User::count() }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg">
                            <h3 class="font-bold">Publicaciones</h3>
                            <p class="text-2xl">{{ \App\Models\Post::count() }}</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-lg">
                            <h3 class="font-bold">Comentarios</h3>
                            <p class="text-2xl">{{ \App\Models\Comment::count() }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 bg-gray-100 rounded-lg">
                        <p class="text-gray-600">⚠️ Este es un dashboard temporal. Más adelante agregaremos todas las funcionalidades de administración (CRUD de usuarios, reportes, solicitudes, etc.)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>