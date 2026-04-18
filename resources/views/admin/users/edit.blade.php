<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6">✏️ Editar Usuario</h1>

                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 font-bold mb-2">Nombre *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="lastname" class="block text-gray-700 font-bold mb-2">Apellidos *</label>
                                <input type="text" name="lastname" id="lastname" value="{{ old('lastname', $user->lastname) }}" 
                                       class="w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('lastname') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 font-bold mb-2">Email *</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                   class="w-full border-gray-300 rounded-md shadow-sm" required>
                            @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="career_id" class="block text-gray-700 font-bold mb-2">Carrera</label>
                                <select name="career_id" id="career_id" class="w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Sin carrera</option>
                                    @foreach($careers as $career)
                                        <option value="{{ $career->id }}" {{ old('career_id', $user->career_id) == $career->id ? 'selected' : '' }}>
                                            {{ $career->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="role_id" class="block text-gray-700 font-bold mb-2">Rol *</label>
                                <select name="role_id" id="role_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                    @foreach($roles as $key => $role)
                                        <option value="{{ $key }}" {{ old('role_id', $user->role_id) == $key ? 'selected' : '' }}>
                                            {{ $role }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 font-bold mb-2">Nueva Contraseña (opcional)</label>
                            <input type="password" name="password" id="password" 
                                   class="w-full border-gray-300 rounded-md shadow-sm">
                            <p class="text-sm text-gray-500 mt-1">Dejar en blanco para mantener la contraseña actual.</p>
                            @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.users.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>