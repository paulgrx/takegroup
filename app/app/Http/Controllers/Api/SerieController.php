<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SerieResource;
use App\Models\Serie;

class SerieController extends Controller
{
    public function index()
    {
        $series = Serie::paginate(10);

        return SerieResource::collection($series);
    }
}
