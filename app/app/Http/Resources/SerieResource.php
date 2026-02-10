<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SerieResource extends JsonResource
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
            'name' => $this->name[app()->getLocale()],
            'genre_ids' => $this->genre_ids,
            'original_language' => $this->original_language,
            'first_air_date' => $this->first_air_date,
            'last_air_date' => $this->last_air_date,
            'popularity' => $this->popularity
        ];
    }
}
