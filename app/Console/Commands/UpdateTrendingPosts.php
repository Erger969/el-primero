<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UpdateTrendingPosts extends Command
{
    protected $signature = 'trending:update';
    protected $description = 'Actualiza el ranking de publicaciones tendencia';

    public function handle()
    {
        // Calcular top 5 publicaciones con más interacciones (comentarios + reacciones) de la última semana
        $trending = Post::with('user')
            ->visible()
            ->withCount(['comments', 'reactions'])
            ->where('created_at', '>=', now()->subWeek())
            ->orderByRaw('(comments_count + reactions_count) DESC')
            ->limit(5)
            ->get();

        // Guardar en caché por 1 hora
        Cache::put('trending_posts', $trending, now()->addHour());

        $this->info('Tendencias actualizadas correctamente. Total: ' . $trending->count());
    }
}