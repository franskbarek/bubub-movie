<a href="{{ route('movies.show', $movie['imdbID']) }}" class="movie-card" style="text-decoration:none;">
    @if(isset($movie['Poster']) && $movie['Poster'] !== 'N/A')
    <img
        data-src="{{ $movie['Poster'] }}"
        alt="{{ $movie['Title'] }}"
        class="card-poster"
        src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
    >
    @else
    <div class="card-poster-placeholder">
        <i class="fas fa-film"></i>
        <span>{{ $movie['Title'] }}</span>
    </div>
    @endif

    <button
        class="fav-btn {{ in_array($movie['imdbID'], $favoriteIds ?? []) ? 'favorited' : '' }}"
        onclick="event.preventDefault();toggleFavorite(this,'{{ $movie['imdbID'] }}','{{ addslashes($movie['Title']) }}','{{ $movie['Year'] ?? '' }}','{{ $movie['Poster'] !== 'N/A' ? $movie['Poster'] : '' }}','{{ $movie['Type'] ?? 'movie' }}')"
        title="{{ __('favorites.add') }}"
    >
        <i class="{{ in_array($movie['imdbID'], $favoriteIds ?? []) ? 'fas' : 'far' }} fa-heart"></i>
    </button>

    <div class="card-overlay">
        <div class="card-title">{{ $movie['Title'] }}</div>
        <div class="card-meta">
            @if(isset($movie['Year']))
            <span class="year-badge">{{ $movie['Year'] }}</span>
            @endif
            @if(isset($movie['Type']))
            <span class="card-type-badge badge-{{ $movie['Type'] === 'series' ? 'series' : 'movie' }}">
                {{ ucfirst($movie['Type']) }}
            </span>
            @endif
        </div>
    </div>
</a>
