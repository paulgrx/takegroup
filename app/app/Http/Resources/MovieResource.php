<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tmdb_id' => $this->tmdb_id,
            'title' => $this->title[app()->getLocale()],
            'genre_ids' => $this->genre_ids,
            'original_language' => $this->original_language,
            'popularity' => $this->popularity,
            'release_date' => $this->release_date
        ];
    }
}
