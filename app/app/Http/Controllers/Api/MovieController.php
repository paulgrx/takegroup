<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use App\Models\Movie;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::paginate(10);

        return MovieResource::collection($movies);
    }
}
