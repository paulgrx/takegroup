<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $fillable = [
        'tmdb_id',
        'name',
        'genre_ids',
        'original_language',
        'first_air_date',
        'last_air_date',
        'popularity'
    ];
    protected $casts = [
        'name' => 'collection',
        'genre_ids' => 'collection'
    ];
}
