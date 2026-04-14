<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;

class PostSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            Post::create([
                'user_id' => $user->id,
                'title' => 'Bienvenidos a la red social universitaria',
                'content' => 'Este es un ejemplo de publicación. Pronto habrá más noticias sobre eventos y cursos.',
                'images' => null,
                'is_hidden' => false,
            ]);
        }
    }
}