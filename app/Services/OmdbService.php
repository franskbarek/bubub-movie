<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class OmdbService
{
    private string $apiKey;
    private string $baseUrl = 'https://www.omdbapi.com/';

    public function __construct()
    {
        $this->apiKey = config('services.omdb.key');
    }

    /**
     * Search movies by title and optional parameters.
     */
    public function search(string $query, int $page = 1, string $type = 'movie', ?string $year = null): array
    {
        $cacheKey = "omdb_search_{$query}_{$page}_{$type}_{$year}";

        return Cache::remember($cacheKey, 300, function () use ($query, $page, $type, $year) {
            $params = [
                'apikey' => $this->apiKey,
                's'      => $query,
                'page'   => $page,
                'type'   => $type,
            ];

            if ($year) {
                $params['y'] = $year;
            }

            $response = Http::get($this->baseUrl, $params);

            if ($response->failed()) {
                return ['Search' => [], 'totalResults' => 0, 'error' => 'API request failed'];
            }

            return $response->json();
        });
    }

    /**
     * Get movie details by IMDb ID.
     */
    public function getById(string $imdbId): array
    {
        $cacheKey = "omdb_movie_{$imdbId}";

        return Cache::remember($cacheKey, 3600, function () use ($imdbId) {
            $response = Http::get($this->baseUrl, [
                'apikey' => $this->apiKey,
                'i'      => $imdbId,
                'plot'   => 'full',
            ]);

            if ($response->failed()) {
                return ['Error' => 'API request failed', 'Response' => 'False'];
            }

            return $response->json();
        });
    }

    /**
     * Get popular/trending movies (using default searches).
     */
    public function getPopular(int $page = 1): array
    {
        return $this->search('marvel', $page);
    }

    /**
     * Get movies by genre/keyword.
     */
    public function getByKeyword(string $keyword, int $page = 1): array
    {
        return $this->search($keyword, $page);
    }
}
