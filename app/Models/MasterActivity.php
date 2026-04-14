<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterActivity extends Model
{
    use HasFactory;

    protected $fillable = ['master_id', 'post_id', 'action'];

    public function master()
    {
        return $this->belongsTo(User::class, 'master_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}