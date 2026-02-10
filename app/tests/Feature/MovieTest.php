<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\Movie;
use Tests\TestCase;

class MovieTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        Genre::truncate();
    }
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_api_returns_movies_from_database(): void
    {
        Movie::factory()->create([
            'title' => [
                'en' => 'Imported movie',
                'pl' => 'Zaimportowany film',
            ],
            'genre_ids' => [1, 2]
        ]);

        $response = $this->getJson('/api/movies');
        $response->assertJsonFragment(['title' => 'Imported movie']);
    }
}
