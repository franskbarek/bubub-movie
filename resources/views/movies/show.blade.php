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
                    <div style="position:relative;">
                        <div class="skeleton-card" id="posterSkeleton" style="position:absolute;inset:0;border-radius:8px;z-index:1;"></div>
                        <img data-src="{{ $movie['Poster'] }}" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" alt="{{ $movie['Title'] }}" class="lazy" style="opacity:0;transition:opacity 0.5s;border-radius:8px;width:100%;" onload="this.style.opacity=1;document.getElementById('posterSkeleton')&&(document.getElementById('posterSkeleton').style.display='none')">
                    </div>
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
    <section class="row-section" style="margin-top:40px;padding-bottom:60px;" id="similarSection">
        <h2 class="row-title">{{ __('movies.more_like_this') }}</h2>

        {{-- Grid instead of row for infinite scroll --}}
        <div class="search-grid" id="similarGrid">
            @foreach($similar as $m)
            @include('components.movie-card', ['movie' => $m, 'favoriteIds' => [$isFavorite ? $movie['imdbID'] : '']])
            @endforeach
        </div>

        {{-- Loading & end --}}
        <div style="text-align:center;padding:32px 0;">
            <div class="spinner" id="similarSpinner" style="display:none;justify-content:center;"><div class="spinner-ring"></div></div>
            <div id="similarEnd" style="color:var(--text-muted);font-size:0.85rem;display:none;">
                <i class="fas fa-check-circle" style="margin-right:6px;color:#46d369;"></i>{{ __('movies.end_of_results') }}
            </div>
        </div>

        {{-- Sentinel for infinite scroll --}}
        <div id="similarSentinel" style="height:10px;"></div>
    </section>
</article>

@push('scripts')
<script>
// ── Config dari server ────────────────────────────────────
const imdbId    = @json($movie['imdbID'] ?? '');
const movieGenre = @json(isset($movie['Genre']) && $movie['Genre'] !== 'N/A' ? trim(explode(',', $movie['Genre'])[0]) : 'action');
const movieTitle  = @json($movie['Title'] ?? '');
const movieYear = @json($movie['Year'] ?? '');
const moviePoster = @json(($movie['Poster'] ?? '') !== 'N/A' ? ($movie['Poster'] ?? '') : '');
const movieType = @json($movie['Type'] ?? 'movie');
let isFavorite = {{ $isFavorite ? 'true' : 'false' }};
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

// ── Infinite Scroll - More Like This ─────────────────────
let similarPage    = 2; // halaman 1 sudah dirender server
let similarLoading = false;
let similarHasMore = true;
const similarLoadedIds = new Set(@json(collect($similar)->pluck('imdbID')->toArray()));

function buildSimilarCard(m) {
    const poster = (m.Poster && m.Poster !== 'N/A') ? m.Poster : null;
    const card   = document.createElement('div');
    card.className = 'movie-card';
    card.style.cssText = 'animation:fadeIn 0.3s ease;';
    card.innerHTML = `
        <a href="/movies/${m.imdbID}" class="card-link">
            <div class="card-poster">
                ${poster
                    ? `<img data-src="${poster}" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" alt="${m.Title}" class="lazy" style="opacity:0;transition:opacity 0.4s;">`
                    : `<div style="width:100%;height:100%;background:linear-gradient(135deg,#1a1a2e,#16213e);display:flex;align-items:center;justify-content:center;"><i class="fas fa-film" style="color:#555;font-size:2rem;"></i></div>`
                }
                <div class="card-overlay">
                    <div class="card-title">${m.Title}</div>
                    <div class="card-meta">${m.Year || ''}</div>
                </div>
            </div>
        </a>
    `;
    return card;
}

async function fetchSimilarPage() {
    if (similarLoading || !similarHasMore) return;
    similarLoading = true;

    const spinner = document.getElementById('similarSpinner');
    const endMsg  = document.getElementById('similarEnd');
    const grid    = document.getElementById('similarGrid');
    spinner.style.display = 'flex';

    try {
        const res  = await fetch(`/movies/search?q=${encodeURIComponent(movieGenre)}&type=movie&page=${similarPage}`, {
            headers: { 'Accept': 'application/json' }
        });
        const data   = await res.json();
        const movies = (data.Search || []).filter(m => m.imdbID !== imdbId && !similarLoadedIds.has(m.imdbID));

        if (!movies.length) {
            similarHasMore = false;
            endMsg.style.display = 'block';
        } else {
            const frag = document.createDocumentFragment();
            movies.forEach(m => {
                similarLoadedIds.add(m.imdbID);
                frag.appendChild(buildSimilarCard(m));
            });
            grid.appendChild(frag);
            if (typeof observeLazy === 'function') observeLazy(grid);

            similarPage++;
            // OMDb max 100 results (10 per page)
            similarHasMore = similarPage <= 10 && movies.length > 0;
            if (!similarHasMore) endMsg.style.display = 'block';
        }
    } catch(e) { console.error(e); }
    finally {
        similarLoading = false;
        spinner.style.display = 'none';
    }
}

// Observer + scroll fallback
document.addEventListener('DOMContentLoaded', () => {
    if (typeof observeLazy === 'function') observeLazy(document);

    const sentinel = document.getElementById('similarSentinel');
    if (sentinel) {
        new IntersectionObserver(entries => {
            if (entries[0].isIntersecting && similarHasMore && !similarLoading) {
                fetchSimilarPage();
            }
        }, { rootMargin: '500px' }).observe(sentinel);
    }
});

window.addEventListener('scroll', () => {
    if (!similarHasMore || similarLoading) return;
    const scrollBottom = window.innerHeight + window.scrollY;
    const docHeight    = document.documentElement.scrollHeight;
    if (scrollBottom >= docHeight - 400) fetchSimilarPage();
}, { passive: true });

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
