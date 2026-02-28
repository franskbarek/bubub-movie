<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Favorite;

class MovieController extends Controller
{
    private string $apiUrl = 'https://www.omdbapi.com/';

    private array $popular = [
        'Avengers', 'Batman', 'Spider-Man', 'Star Wars', 'Jurassic',
        'Avatar', 'Lion King', 'Frozen', 'Top Gun', 'Inception',
        'Transformers', 'Captain Marvel', 'Harry Potter', 'Fast Furious',
        'Black Panther', 'Iron Man', 'Thor', 'Wonder Woman', 'Interstellar',
    ];

    public function index()
    {
        $favoriteIds = Favorite::where('user_id', auth()->id())
            ->pluck('imdb_id')
            ->toArray();

        return view('movies.index', compact('favoriteIds'));
    }

    public function search(Request $request)
    {
        $q    = trim($request->input('q', ''));
        $page = max(1, (int) $request->input('page', 1));
        $type = $request->input('type', '');
        $year = $request->input('year', '');

        // Jika tidak ada query tapi ada year/type → pakai keyword populer acak
        // Jika tidak ada query, tidak ada year, tidak ada type → tampilkan populer
        if (!$q) {
            $q = $this->popular[array_rand($this->popular)];
        }

        $params = [
            'apikey' => config('services.omdb.key'),
            's'      => $q,
            'page'   => $page,
        ];

        if ($type) $params['type'] = $type;
        if ($year) $params['y']    = $year;

        $res = Http::timeout(10)->get($this->apiUrl, $params);

        return response()->json($res->json());
    }

    public function autocomplete(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) < 3) {
            return response()->json([]);
        }

        $res = Http::timeout(10)->get($this->apiUrl, [
            'apikey' => config('services.omdb.key'),
            's'      => $q,
            'page'   => 1,
        ]);

        $data = $res->json();

        if (($data['Response'] ?? '') !== 'True') {
            return response()->json([]);
        }

        $suggestions = collect($data['Search'] ?? [])
            ->map(fn($m) => [
                'title'  => $m['Title'],
                'year'   => $m['Year'],
                'imdbId' => $m['imdbID'],
            ])
            ->take(6)
            ->values();

        return response()->json($suggestions);
    }

    public function show(string $imdbID)
    {
        $res = Http::timeout(10)->get($this->apiUrl, [
            'apikey' => config('services.omdb.key'),
            'i'      => $imdbID,
            'plot'   => 'full',
        ]);

        $movie = $res->json();

        if (isset($movie['Response']) && $movie['Response'] === 'False') {
            abort(404, __('movies.not_found'));
        }

        $isFavorite = Favorite::where('user_id', auth()->id())
            ->where('imdb_id', $imdbID)
            ->exists();

        // Similar movies berdasarkan genre pertama
        $similar = [];
        if (!empty($movie['Genre'])) {
            $firstGenre = trim(explode(',', $movie['Genre'])[0]);
            $simRes = Http::timeout(10)->get($this->apiUrl, [
                'apikey' => config('services.omdb.key'),
                's'      => $firstGenre,
                'page'   => 1,
                'type'   => 'movie',
            ]);
            $simData = $simRes->json();
            $similar = collect($simData['Search'] ?? [])
                ->filter(fn($m) => $m['imdbID'] !== $imdbID)
                ->take(8)
                ->values()
                ->toArray();
        }

        return view('movies.show', compact('movie', 'isFavorite', 'similar'));
    }

    /**
     * Return full movie detail as JSON (used by hero carousel).
     */
    public function detailJson(Request $request)
    {
        $imdbId = $request->input('i', '');
        if (!$imdbId) {
            return response()->json(['Response' => 'False', 'Error' => 'No ID provided']);
        }

        $res = Http::timeout(10)->get($this->apiUrl, [
            'apikey' => config('services.omdb.key'),
            'i'      => $imdbId,
            'plot'   => 'full',
        ]);

        return response()->json($res->json());
    }
}
