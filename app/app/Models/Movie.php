<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $table = 'movies';
    protected $fillable = [
        'tmdb_id',
        'title',
        'genre_ids',
        'original_language',
        'popularity',
        'release_date'
    ];
    protected $casts = [
        'title' => 'collection',
        'genre_ids' => 'collection'
    ];
}
