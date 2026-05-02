<!-- NAVBAR STICKY -->
<nav class="sticky top-0 z-50 glass-nav transition-all duration-500">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <a href="{{ route('home') }}" class="flex items-center gap-3 transition-transform hover:scale-105 group">
                <div class="flex items-center justify-center w-11 h-11 bg-gradient-to-br from-[#1A3C5E] to-[#C4A35A] rounded-2xl shadow-lg group-hover:rotate-12 transition-all duration-300">
                    <x-heroicon-s-academic-cap class="w-6 h-6 text-white" />
                </div>
                <span class="text-2xl font-bold tracking-tight text-white">UniSocial</span>
            </a>
            
            <div class="flex items-center gap-3">
                @auth
                <!-- Botón Inicio / Feed -->
                <a href="{{ route('feed') }}" class="hidden sm:flex items-center gap-2 px-4 py-2 text-white font-medium transition-all duration-300 rounded-xl bg-white/10 hover:bg-white/20 backdrop-blur-sm shadow-sm hover:scale-105">
                    <x-heroicon-o-home class="w-5 h-5" /> <span class="hidden md:inline text-sm">Inicio</span>
                </a>

                <!-- Botón Nueva Publicación -->
                <a href="{{ route('posts.create') }}" class="flex items-center gap-2 px-4 py-2 text-white font-bold transition-all duration-300 rounded-xl bg-gradient-to-r from-[#C4A35A] to-[#D4B06A] hover:from-[#1A3C5E] hover:to-[#2A6B9E] backdrop-blur-sm shadow-md hover:shadow-lg hover:-translate-y-0.5" title="Crear Publicación">
                    <x-heroicon-o-plus-circle class="w-5 h-5" /> <span class="hidden sm:inline text-sm">Crear</span>
                </a>
                <!-- Botón Mi Perfil -->
                <a href="{{ route('profile.show', Auth::id()) }}" class="flex items-center gap-2 px-4 py-2 text-white font-medium transition-all duration-300 rounded-xl bg-white/10 hover:bg-white/20 backdrop-blur-sm shadow-sm hover:scale-105">
                    <div class="flex items-center justify-center w-6 h-6 text-xs font-bold text-white rounded-full bg-gradient-to-br from-[#1A3C5E] to-[#C4A35A]">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <span class="hidden sm:inline text-sm">{{ Auth::user()->name }}</span>
                </a>

                @if(Auth::user()->role_id == 3)
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-white font-medium transition-all duration-300 rounded-xl bg-purple-600/50 hover:bg-purple-600/80 backdrop-blur-sm shadow-sm hover:scale-105">
                        <x-heroicon-o-chart-bar class="w-5 h-5" /> Admin
                    </a>
                @endif

                <!-- Botón Cerrar Sesión -->
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 text-white font-medium transition-all duration-300 rounded-xl bg-red-500/20 hover:bg-red-500/40 border border-red-500/30 backdrop-blur-sm shadow-sm hover:scale-105" title="Cerrar sesión">
                        <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" /> <span class="hidden sm:inline text-sm">Salir</span>
                    </button>
                </form>
                @endauth
                
                <div class="w-px h-6 mx-1 bg-white/20"></div>
                
                <button @click="darkMode = !darkMode" class="p-2.5 text-white bg-white/10 hover:bg-white/20 rounded-xl transition-all">
                    <span x-show="!darkMode"><x-heroicon-o-sun class="w-6 h-6 text-yellow-400" /></span>
                    <span x-show="darkMode"><x-heroicon-o-moon class="w-6 h-6 text-gray-300" /></span>
                </button>
            </div>
        </div>
    </div>
</nav>
