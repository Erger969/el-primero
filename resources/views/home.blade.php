<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Red Social Universitaria</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navbar -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold text-gray-800">UniSocial</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/feed') }}" class="text-gray-700 hover:text-gray-900">Feed</a>
                                <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-gray-900">Perfil</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-gray-700 hover:text-gray-900">Cerrar sesión</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Iniciar sesión</a>
                                <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Registrarse</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero / Carrusel de tendencias -->
        <div class="bg-white shadow-md m-4 rounded-lg overflow-hidden">
            <div class="p-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white">
                <h2 class="text-2xl font-bold">🔥 Tendencias de la semana</h2>
            </div>
            <div class="p-4">
                @if($trendingPosts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($trendingPosts as $post)
                            <div class="border rounded-lg p-4 hover:shadow-lg transition">
                                <h3 class="font-bold text-lg">{{ $post->title }}</h3>
                                <p class="text-gray-600 text-sm">Por: {{ $post->user->name }} {{ $post->user->lastname }}</p>
                                <p class="text-gray-500 text-sm">{{ $post->created_at->diffForHumans() }}</p>
                                <div class="mt-2 flex gap-2 text-sm">
                                    <span>💬 {{ $post->comments_count }}</span>
                                    <span>❤️ {{ $post->reactions_count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No hay tendencias aún. ¡Sé el primero en publicar!</p>
                @endif
            </div>
        </div>

        <!-- Grid de publicaciones (3x2) -->
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <h2 class="text-2xl font-bold mb-4">📰 Últimas noticias</h2>
                
                @if($posts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($posts as $post)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                                @if($post->images && count(json_decode($post->images, true)) > 0)
                                    @php $images = json_decode($post->images, true); @endphp
                                    <img src="{{ $images[0] }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400">Sin imagen</span>
                                    </div>
                                @endif
                                
                                <div class="p-4">
                                    <h3 class="font-bold text-xl mb-2">{{ $post->title }}</h3>
                                    <p class="text-gray-700 text-sm mb-2">{{ Str::limit($post->content, 100) }}</p>
                                    <div class="flex justify-between items-center text-sm text-gray-500">
                                        <span>👤 {{ $post->user->name }}</span>
                                        <span>📅 {{ $post->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Paginación -->
                    <div class="mt-6">
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <p class="text-gray-500">No hay publicaciones aún. ¡Sé el primero en publicar!</p>
                        @auth
                            <a href="{{ route('posts.create') }}" class="mt-3 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Crear publicación
                            </a>
                        @endauth
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white mt-12 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p>© {{ date('Y') }} UniSocial - Red social universitaria</p>
                <div class="flex justify-center space-x-4 mt-2">
                    <a href="#" class="hover:text-blue-400">Facebook</a>
                    <a href="#" class="hover:text-blue-400">Instagram</a>
                    <a href="#" class="hover:text-blue-400">Twitter</a>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>