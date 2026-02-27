@extends('layouts.app')

@section('title', $movie['Title'] ?? 'Movie Detail')

@section('content')
<article>
    {{-- Hero/Backdrop --}}
    <div class="detail-hero">
        <div class="detail-bg">
            @if(isset($movie['Poster']) && $movie['Poster'] !== 'N/A')
            <img src="{{ $movie['Poster'] }}" alt="{{ $movie['Title'] }}">
            @else
            <div style="width:100%;height:100%;background:linear-gradient(135deg,#1a1a2e,#16213e,#0f3460)"></div>
            @endif
            <div class="detail-gradient"></div>
        </div>

        <div class="detail-content">
            <div class="detail-layout">
                {{-- Poster --}}
                <div class="detail-poster">
                    @if(isset($movie['Poster']) && $movie['Poster'] !== 'N/A')
                    <img src="{{ $movie['Poster'] }}" alt="{{ $movie['Title'] }}">
                    @else
                    <div style="aspect-ratio:2/3;background:linear-gradient(135deg,#1a1a2e,#16213e);display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-film" style="font-size:3rem;color:#555"></i>
                    </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="detail-info">
                    {{-- Genres --}}
                    @if(isset($movie['Genre']) && $movie['Genre'] !== 'N/A')
                    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;">
                        @foreach(explode(',', $movie['Genre']) as $genre)
                        <span class="genre-tag">{{ trim($genre) }}</span>
                        @endforeach
                    </div>
                    @endif

                    <h1 class="detail-title">{{ $movie['Title'] ?? 'Unknown' }}</h1>

                    <div class="detail-meta">
                        @if(isset($movie['Year']) && $movie['Year'] !== 'N/A')
                        <span class="year-badge">{{ $movie['Year'] }}</span>
                        @endif
                        @if(isset($movie['Runtime']) && $movie['Runtime'] !== 'N/A')
                        <span>{{ $movie['Runtime'] }}</span>
                        @endif
                        @if(isset($movie['Rated']) && $movie['Rated'] !== 'N/A')
                        <span style="border:1px solid #aaa;padding:2px 7px;font-size:0.75rem;border-radius:3px;">{{ $movie['Rated'] }}</span>
                        @endif
                        @if(isset($movie['imdbRating']) && $movie['imdbRating'] !== 'N/A')
                        <span class="detail-rating"><i class="fas fa-star" style="color:#f5c518"></i> {{ $movie['imdbRating'] }}/10</span>
                        @endif
                    </div>

                    {{-- Ratings row --}}
                    @if(isset($movie['Ratings']) && count($movie['Ratings']) > 0)
                    <div class="ratings-row">
                        @foreach($movie['Ratings'] as $rating)
                        <div class="rating-badge">
                            <div class="source">{{ $rating['Source'] }}</div>
                            <div class="value">{{ $rating['Value'] }}</div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <p class="detail-plot">{{ $movie['Plot'] ?? '' }}</p>

                    <div class="detail-actions">
                        <a href="{{ route('movies.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('movies.back') }}
                        </a>
                        <button
                            id="favBtn"
                            class="btn {{ $isFavorite ? 'btn-primary' : 'btn-outline' }}"
                            onclick="toggleFavDetail()"
                        >
                            <i class="{{ $isFavorite ? 'fas' : 'far' }} fa-heart"></i>
                            <span id="favBtnText">{{ $isFavorite ? __('favorites.remove') : __('favorites.add') }}</span>
                        </button>
                    </div>

                    {{-- Stats Grid --}}
                    <div class="detail-stats">
                        @if(isset($movie['Director']) && $movie['Director'] !== 'N/A')
                        <div class="stat-item">
                            <div class="stat-label">{{ __('movies.director') }}</div>
                            <div class="stat-value">{{ $movie['Director'] }}</div>
                        </div>
                        @endif
                        @if(isset($movie['Actors']) && $movie['Actors'] !== 'N/A')
                        <div class="stat-item">
                            <div class="stat-label">{{ __('movies.cast') }}</div>
                            <div class="stat-value">{{ $movie['Actors'] }}</div>
                        </div>
                        @endif
                        @if(isset($movie['Writer']) && $movie['Writer'] !== 'N/A')
                        <div class="stat-item">
                            <div class="stat-label">{{ __('movies.writer') }}</div>
                            <div class="stat-value">{{ $movie['Writer'] }}</div>
                        </div>
                        @endif
                        @if(isset($movie['Language']) && $movie['Language'] !== 'N/A')
                        <div class="stat-item">
                            <div class="stat-label">{{ __('movies.language') }}</div>
                            <div class="stat-value">{{ $movie['Language'] }}</div>
                        </div>
                        @endif
                        @if(isset($movie['Country']) && $movie['Country'] !== 'N/A')
                        <div class="stat-item">
                            <div class="stat-label">{{ __('movies.country') }}</div>
                            <div class="stat-value">{{ $movie['Country'] }}</div>
                        </div>
                        @endif
                        @if(isset($movie['BoxOffice']) && $movie['BoxOffice'] !== 'N/A')
                        <div class="stat-item">
                            <div class="stat-label">{{ __('movies.box_office') }}</div>
                            <div class="stat-value">{{ $movie['BoxOffice'] }}</div>
                        </div>
                        @endif
                        @if(isset($movie['Awards']) && $movie['Awards'] !== 'N/A')
                        <div class="stat-item">
                            <div class="stat-label">{{ __('movies.awards') }}</div>
                            <div class="stat-value">{{ $movie['Awards'] }}</div>
                        </div>
                        @endif
                        @if(isset($movie['Released']) && $movie['Released'] !== 'N/A')
                        <div class="stat-item">
                            <div class="stat-label">{{ __('movies.released') }}</div>
                            <div class="stat-value">{{ $movie['Released'] }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Similar / More Like This --}}
    @if(!empty($similar))
    <section class="row-section" style="margin-top: 40px;">
        <h2 class="row-title">{{ __('movies.more_like_this') }}</h2>
        <div class="movies-row">
            @foreach($similar as $m)
            @include('components.movie-card', ['movie' => $m, 'favoriteIds' => [$isFavorite ? $movie['imdbID'] : '']])
            @endforeach
        </div>
    </section>
    @endif
</article>

@push('scripts')
<script>
const imdbId = @json($movie['imdbID'] ?? '');
const movieTitle = @json($movie['Title'] ?? '');
const movieYear = @json($movie['Year'] ?? '');
const moviePoster = @json(($movie['Poster'] ?? '') !== 'N/A' ? ($movie['Poster'] ?? '') : '');
const movieType = @json($movie['Type'] ?? 'movie');
let isFavorite = {{ $isFavorite ? 'true' : 'false' }};
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

async function toggleFavDetail() {
    const btn = document.getElementById('favBtn');
    const btnText = document.getElementById('favBtnText');
    btn.disabled = true;
    try {
        let response;
        if (isFavorite) {
            response = await fetch(`/favorites/${imdbId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            });
        } else {
            response = await fetch('/favorites', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ imdb_id: imdbId, title: movieTitle, year: movieYear, poster: moviePoster, type: movieType })
            });
        }
        const data = await response.json();
        if (data.success) {
            isFavorite = data.favorited;
            btn.className = `btn ${isFavorite ? 'btn-primary' : 'btn-outline'}`;
            btn.querySelector('i').className = `${isFavorite ? 'fas' : 'far'} fa-heart`;
            btnText.textContent = isFavorite ? '{{ __("favorites.remove") }}' : '{{ __("favorites.add") }}';
            showToast(data.message, 'success');
        }
    } catch(e) {
        showToast('{{ __("favorites.error") }}', 'error');
    } finally {
        btn.disabled = false;
    }
}
</script>
@endpush
@endsection
