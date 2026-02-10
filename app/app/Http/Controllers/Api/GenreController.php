<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GenreResource;
use App\Models\Genre;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::paginate(10);

        return GenreResource::collection($genres);
    }
}
