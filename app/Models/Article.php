<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'content', 'source', 'category', 'author', 'published_at'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_preferences');
    }
}
