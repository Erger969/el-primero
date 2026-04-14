<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'content', 'images', 'is_hidden', 'hidden_until'
    ];

    protected $casts = [
        'images' => 'array',
        'hidden_until' => 'datetime',
        'is_hidden' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function masterActivities()
    {
        return $this->hasMany(MasterActivity::class);
    }

    public function postModifications()
    {
        return $this->hasMany(PostModification::class);
    }

    // Scope para publicaciones no ocultas
    public function scopeVisible($query)
    {
        return $query->where('is_hidden', false)
                     ->where(function($q) {
                         $q->whereNull('hidden_until')
                           ->orWhere('hidden_until', '>', now());
                     });
    }
}