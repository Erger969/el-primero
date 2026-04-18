<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'category',
        'reason',
        'status',
    ];

    // Relación con el usuario que reporta
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con la publicación reportada
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}