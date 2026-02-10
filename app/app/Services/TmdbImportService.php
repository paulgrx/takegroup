<?php

namespace App\Services;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Serie;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TmdbImportService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.tmdb.base_url');
    }

    private function languages(): array
    {
        return [
            'en',
            'de',
            'pl'
        ];
    }

    public function importGenres(): void
    {
        $endpoints = [
            'genre/movie/list',
            'genre/tv/list'
        ];

        $pairs = collect($endpoints)->crossJoin($this->languages());

        $rows = $pairs->flatMap(function ($pair) {
            [$endpoint, $lang] = $pair;

            $response = Http::withToken(config('services.tmdb.api_key'))->get($this->baseUrl . $endpoint, ['language' => $lang]);

            if (!$response->successful()) {
                Log::warning("Failed to fetch genres for {$lang} language: {$response->body()}");
                return [];
            }

            return collect($response->json('genres', []))
                ->map(fn ($genre) => [
                    'tmdb_id' => $genre['id'],
                    'lang'    => $lang,
                    'name'    => $genre['name']
                ]);
        });

        $genres = $rows
            ->groupBy('tmdb_id')
            ->map(fn ($items) => [
                'tmdb_id' => $items->first()['tmdb_id'],
                'name' => $items
                    ->unique('lang')
                    ->mapWithKeys(fn ($item) => [$item['lang'] => $item['name']])
            ])
            ->values();

        $genres->each(fn ($genre) => Genre::updateOrCreate(['tmdb_id' => $genre['tmdb_id']], ['name' => $genre['name']]));
    }

    public function importMovies(): void
    {
        $movieIds = $this->fetchIdsFromList('movie/popular', 50);

        $requests = $movieIds
            ->crossJoin(collect($this->languages()))
            ->map(fn ($pair) => [
                'movie_id' => $pair[0],
                'lang' => $pair[1]
            ])
            ->values();

        $responses = Http::pool(fn ($pool) => $requests
            ->map(fn ($req) => $pool
                ->withToken(config('services.tmdb.api_key'))
                ->get("{$this->baseUrl}movie/{$req['movie_id']}", ['language' => $req['lang']])
            )->all()
        );

        $movies = collect($responses)
            ->zip($requests)
            ->filter(fn ($pair) =>
                $pair[0] instanceof Response &&
                $pair[0]->successful()
            )
            ->map(fn ($pair) => [
                'tmdb_id' => $pair[1]['movie_id'],
                'lang' => $pair[1]['lang'],
                'data' => $pair[0]->json()
            ])
            ->groupBy('tmdb_id')
            ->map(fn ($items, $tmdbId) => [
                'tmdb_id' => $tmdbId,
                'title' => $items->mapWithKeys(fn ($item) => [$item['lang'] => $item['data']['title']]),
                'genre_ids' => collect($items->first()['data']['genres'])->pluck('id'),
                'original_language' => $items->first()['data']['original_language'],
                'popularity' => $items->first()['data']['popularity'],
                'release_date' => $items->first()['data']['release_date']
            ])
            ->values();

        foreach ($movies as $movie) {
            Movie::updateOrCreate(
                ['tmdb_id' => $movie['tmdb_id']],
                [
                    'title' => $movie['title'],
                    'genre_ids' => $movie['genre_ids'],
                    'release_date' => $movie['release_date'],
                    'original_language' => $movie['original_language'],
                    'popularity' => $movie['popularity']
                ]
            );
        }
    }

    public function importSeries(): void
    {
        $serieIds = $this->fetchIdsFromList('tv/popular', 10);

        $requests = $serieIds
            ->crossJoin(collect($this->languages()))
            ->map(fn ($pair) => [
                'serie_id' => $pair[0],
                'lang' => $pair[1]
            ])
            ->values();

        $responses = Http::pool(fn ($pool) => $requests
            ->map(fn ($req) => $pool
                ->withToken(config('services.tmdb.api_key'))
                ->get("{$this->baseUrl}tv/{$req['serie_id']}", ['language' => $req['lang']])
            )->all()
        );

        $series = collect($responses)
            ->zip($requests)
            ->filter(fn ($pair) =>
                $pair[0] instanceof Response &&
                $pair[0]->successful()
            )
            ->map(fn ($pair) => [
                'tmdb_id' => $pair[1]['serie_id'],
                'lang' => $pair[1]['lang'],
                'data' => $pair[0]->json()
            ])
            ->groupBy('tmdb_id')
            ->map(fn ($items, $tmdbId) => [
                'tmdb_id' => $tmdbId,
                'name' => $items->mapWithKeys(fn ($item) => [$item['lang'] => $item['data']['name']]),
                'genre_ids' => collect($items->first()['data']['genres'])->pluck('id'),
                'original_language' => $items->first()['data']['original_language'],
                'first_air_date' => $items->first()['data']['first_air_date'],
                'last_air_date' => $items->first()['data']['last_air_date'],
                'popularity' => $items->first()['data']['popularity']
            ])
            ->values();

        foreach ($series as $movie) {
            Serie::updateOrCreate(
                ['tmdb_id' => $movie['tmdb_id']],
                [
                    'name' => $movie['name'],
                    'genre_ids' => $movie['genre_ids'],
                    'original_language' => $movie['original_language'],
                    'first_air_date' => $movie['first_air_date'],
                    'last_air_date' => $movie['last_air_date'],
                    'popularity' => $movie['popularity']
                ]
            );
        }
    }

    private function fetchIdsFromList(string $endpoint, int $limit): Collection
    {
        $ids = collect();

        for ($page = 1; $ids->count() < $limit; $page++) {
            $response = Http::withToken(config('services.tmdb.api_key'))->get($this->baseUrl . $endpoint, ['language' => 'en-US', 'page' => $page]);

            if (!$response->successful()) {
                Log::warning("Failed to fetch list of ids for {$endpoint} : {$response->body()}");
                break;
            }

            $pageIds = collect($response->json('results', []))->pluck('id')->all();

            if (empty($pageIds)) {
                break;
            }

            $ids = $ids->concat($pageIds);
        }

        return $ids->unique()->take($limit)->values();
    }
}
