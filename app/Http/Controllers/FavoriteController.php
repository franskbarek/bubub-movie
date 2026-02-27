<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Services\OmdbService;

class FavoriteController extends Controller
{
    public function __construct(private OmdbService $omdb) {}

    public function index()
    {
        $favorites = Favorite::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('favorites.index', compact('favorites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'imdb_id'    => 'required|string',
            'title'      => 'required|string|max:255',
            'year'       => 'nullable|string|max:10',
            'poster'     => 'nullable|string',
            'type'       => 'nullable|string|max:20',
        ]);

        $favorite = Favorite::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'imdb_id' => $validated['imdb_id'],
            ],
            [
                'title'  => $validated['title'],
                'year'   => $validated['year'] ?? null,
                'poster' => $validated['poster'] ?? null,
                'type'   => $validated['type'] ?? 'movie',
            ]
        );

        return response()->json([
            'success'    => true,
            'favorited'  => true,
            'message'    => __('favorites.added'),
        ]);
    }

    public function destroy(string $imdbID)
    {
        Favorite::where('user_id', auth()->id())
            ->where('imdb_id', $imdbID)
            ->delete();

        return response()->json([
            'success'    => true,
            'favorited'  => false,
            'message'    => __('favorites.removed'),
        ]);
    }

    public function check(string $imdbID)
    {
        $exists = Favorite::where('user_id', auth()->id())
            ->where('imdb_id', $imdbID)
            ->exists();

        return response()->json(['favorited' => $exists]);
    }
}
