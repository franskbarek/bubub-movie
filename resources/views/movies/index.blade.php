@extends('layouts.app')

@section('title', __('movies.browse'))

@section('content')

{{-- Hero Carousel --}}
<section id="heroCarousel" style="display:none;position:relative;height:90vh;min-height:560px;overflow:hidden;">

    {{-- Slides container --}}
    <div id="heroSlides" style="display:flex;height:100%;transition:transform 0.7s cubic-bezier(.4,0,.2,1);will-change:transform;"></div>

    {{-- Gradient overlay --}}
    <div style="position:absolute;inset:0;background:linear-gradient(to right,rgba(0,0,0,0.85) 0%,rgba(0,0,0,0.3) 55%,transparent 100%),linear-gradient(to top,#141414 0%,transparent 35%);pointer-events:none;z-index:2;"></div>

    {{-- Content overlay --}}
    <div id="heroContent" style="position:absolute;inset:0;z-index:3;display:flex;align-items:center;padding:0 4%;pointer-events:none;">
        <div style="max-width:580px;pointer-events:all;">
            {{-- Badge box office --}}
            <div id="hBadge" style="display:flex;align-items:center;gap:8px;margin-bottom:14px;"></div>
            {{-- Genres --}}
            <div id="hGenres" style="display:flex;gap:8px;margin-bottom:12px;flex-wrap:wrap;"></div>
            {{-- Title --}}
            <h1 id="hTitle" style="font-size:clamp(1.8rem,5vw,3.2rem);font-weight:900;line-height:1.05;margin-bottom:14px;text-shadow:0 2px 8px rgba(0,0,0,0.5);"></h1>
            {{-- Meta row --}}
            <div id="hMeta" style="display:flex;gap:14px;flex-wrap:wrap;align-items:center;margin-bottom:16px;font-size:0.9rem;"></div>
            {{-- Ratings row --}}
            <div id="hRatings" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px;"></div>
            {{-- Plot --}}
            <p id="hPlot" style="color:#ccc;font-size:0.95rem;line-height:1.7;margin-bottom:24px;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;"></p>
            {{-- Cast --}}
            <p id="hCast" style="color:#aaa;font-size:0.82rem;margin-bottom:24px;"></p>
            {{-- Actions --}}
            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                <a id="hLink" href="#" class="btn btn-primary"><i class="fas fa-info-circle"></i> {{ __('movies.more_info') }}</a>
                <button id="hFavBtn" class="btn btn-secondary" onclick="heroFavToggle()"><i class="far fa-heart"></i> {{ __('favorites.add') }}</button>
            </div>
        </div>
    </div>

    {{-- Prev / Next arrows --}}
    <button id="heroPrev" onclick="heroSlide(-1)" style="position:absolute;left:16px;top:50%;transform:translateY(-50%);z-index:10;background:rgba(0,0,0,0.6);border:2px solid rgba(255,255,255,0.25);color:white;width:48px;height:48px;border-radius:50%;font-size:1.1rem;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:center;" onmouseover="this.style.background='rgba(229,9,20,0.8)';this.style.borderColor='transparent'" onmouseout="this.style.background='rgba(0,0,0,0.6)';this.style.borderColor='rgba(255,255,255,0.25)'">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button id="heroNext" onclick="heroSlide(1)" style="position:absolute;right:16px;top:50%;transform:translateY(-50%);z-index:10;background:rgba(0,0,0,0.6);border:2px solid rgba(255,255,255,0.25);color:white;width:48px;height:48px;border-radius:50%;font-size:1.1rem;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:center;" onmouseover="this.style.background='rgba(229,9,20,0.8)';this.style.borderColor='transparent'" onmouseout="this.style.background='rgba(0,0,0,0.6)';this.style.borderColor='rgba(255,255,255,0.25)'">
        <i class="fas fa-chevron-right"></i>
    </button>

    {{-- Dots --}}
    <div id="heroDots" style="position:absolute;bottom:24px;left:50%;transform:translateX(-50%);z-index:10;display:flex;gap:8px;"></div>

    {{-- Slide counter --}}
    <div id="heroCounter" style="position:absolute;top:90px;right:24px;z-index:10;font-size:0.8rem;color:rgba(255,255,255,0.5);font-weight:600;letter-spacing:1px;"></div>
</section>

{{-- Search & Filter Bar --}}
<div class="filter-bar" style="padding-top:24px;">
    <div style="display:flex;gap:12px;flex-wrap:wrap;width:100%;align-items:center;">
        <div style="flex:1;min-width:220px;position:relative;">
            <input
                type="text"
                id="searchInput"
                placeholder="{{ __('movies.search_placeholder') }}"
                autocomplete="off"
                style="width:100%;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:white;padding:10px 16px;border-radius:4px;font-size:0.9rem;outline:none;"
            >
            <div id="autocompleteBox" style="display:none;position:absolute;top:100%;left:0;right:0;background:#1f1f1f;border:1px solid #333;border-radius:0 0 4px 4px;z-index:100;"></div>
        </div>
        <select id="typeFilter" class="filter-select">
            <option value="">{{ __('movies.type_all') }}</option>
            <option value="movie">{{ __('movies.type_movie') }}</option>
            <option value="series">{{ __('movies.type_series') }}</option>
        </select>
        <input type="text" id="yearFilter" placeholder="{{ __('movies.year_placeholder') }}"
            style="width:90px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:white;padding:10px 14px;border-radius:4px;font-size:0.9rem;outline:none;"
        >
        <button onclick="doSearch()" class="btn btn-primary" style="padding:10px 20px;">
            <i class="fas fa-search"></i> {{ __('movies.search') }}
        </button>
    </div>
</div>

{{-- Search Results --}}
<div id="searchResultsSection" style="display:none;padding:0 4%;margin-bottom:40px;">
    <h2 class="row-title" id="searchResultsTitle" style="margin-bottom:16px;"></h2>
    <div class="search-grid" id="searchGrid"></div>
    <div style="text-align:center;padding:32px 0;">
        <div class="spinner" id="loadingSpinner" style="display:none;"><div class="spinner-ring"></div></div>
        <div id="endMessage" style="color:var(--text-muted);font-size:0.85rem;display:none;">{{ __('movies.end_of_results') }}</div>
    </div>
    <div id="searchSentinel" style="height:10px;"></div>
</div>

{{-- Category Rows --}}
<div id="categoriesSection">
    <section class="row-section" id="cat-trending">
        <h2 class="row-title">{{ __('movies.trending') }}</h2>
        <div class="movies-row" id="row-trending"><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div></div>
    </section>
    <div class="section-divider"></div>
    <section class="row-section" id="cat-action">
        <h2 class="row-title">{{ __('movies.action') }}</h2>
        <div class="movies-row" id="row-action"><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div></div>
    </section>
    <div class="section-divider"></div>
    <section class="row-section" id="cat-drama">
        <h2 class="row-title">{{ __('movies.drama') }}</h2>
        <div class="movies-row" id="row-drama"><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div></div>
    </section>
    <div class="section-divider"></div>
    <section class="row-section" id="cat-comedy">
        <h2 class="row-title">{{ __('movies.comedy') }}</h2>
        <div class="movies-row" id="row-comedy"><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div><div class="skeleton-card"></div></div>
    </section>
</div>

<style>
@keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
.skeleton-card {
    aspect-ratio: 2/3;
    background: linear-gradient(90deg, #1f1f1f 25%, #2a2a2a 50%, #1f1f1f 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
    border-radius: 4px;
}
.autocomplete-item {
    padding: 10px 16px; cursor: pointer; font-size: 0.88rem;
    display: flex; gap: 12px; align-items: center; border-bottom: 1px solid #2a2a2a;
    transition: background 0.15s;
}
.autocomplete-item:hover { background: rgba(255,255,255,0.07); }
.autocomplete-item:last-child { border-bottom: none; }
.ac-year { color: var(--text-muted); font-size: 0.78rem; margin-left: auto; }
</style>

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const favoriteIds = new Set(@json($favoriteIds));

// lazyObserver & observeLazy defined in layouts/app.blade.php

// ─── Hero Carousel ────────────────────────────────────────
let heroMovies   = [];   // array of full detail objects
let heroIndex    = 0;
let heroAutoTimer = null;
let heroImdbId   = null;

// Box office keywords 2025-2026
const BOX_OFFICE_QUERIES = [
    'Minecraft Movie 2025', 'Thunderbolts 2025', 'Mission Impossible',
    'Superman 2025', 'Jurassic World Rebirth', 'Final Destination',
    'Avatar', 'Avengers Doomsday', 'Captain America', 'Fantastic Four 2025'
];

async function initHeroCarousel() {
    const slidesEl = document.getElementById('heroSlides');
    const carousel = document.getElementById('heroCarousel');

    // Fetch all 10 queries in parallel
    const promises = BOX_OFFICE_QUERIES.map(q =>
        fetch('/movies/search?q=' + encodeURIComponent(q) + '&type=movie&page=1', { headers: {'Accept':'application/json'} })
            .then(r => r.json())
            .then(d => (d.Search && d.Search[0]) ? d.Search[0] : null)
            .catch(() => null)
    );
    const results = (await Promise.all(promises)).filter(Boolean);

    if (!results.length) return;

    // Fetch full detail for each in parallel (for plot, rating, cast, etc.)
    const detailPromises = results.map(m =>
        fetch('/movies/search?q=' + encodeURIComponent(m.Title) + '&type=movie', { headers: {'Accept':'application/json'} })
            .then(r => r.json())
            .then(d => {
                // Merge search result with whatever extra we have
                const first = (d.Search || []).find(x => x.imdbID === m.imdbID) || m;
                return { ...m, ...first };
            })
            .catch(() => m)
    );

    // Actually fetch detail page via omdb i= for full plot/ratings
    const fullDetailPromises = results.map(m =>
        fetch('/movies/detail-json?i=' + m.imdbID, { headers: {'Accept':'application/json'} })
            .catch(() => null)
    );

    heroMovies = results; // use search results first for speed (render immediately)

    // Build slides with search data first
    slidesEl.innerHTML = '';
    heroMovies.forEach((m, i) => {
        const slide = document.createElement('div');
        slide.style.cssText = 'min-width:100%;height:100%;position:relative;flex-shrink:0;';
        const poster = (m.Poster && m.Poster !== 'N/A') ? m.Poster : '';
        if (poster) {
            const img = document.createElement('img');
            img.src = poster;
            img.style.cssText = 'position:absolute;right:0;top:0;bottom:0;width:65%;height:100%;object-fit:cover;object-position:top;';
            slide.appendChild(img);
        } else {
            slide.style.background = 'linear-gradient(135deg,#1a1a2e,#0f3460)';
        }
        slidesEl.appendChild(slide);
    });

    // Build dots
    const dotsEl = document.getElementById('heroDots');
    dotsEl.innerHTML = '';
    heroMovies.forEach((_, i) => {
        const dot = document.createElement('button');
        dot.style.cssText = 'width:8px;height:8px;border-radius:50%;border:none;cursor:pointer;transition:all 0.3s;background:rgba(255,255,255,0.4);padding:0;';
        dot.onclick = () => goToSlide(i);
        dotsEl.appendChild(dot);
    });

    carousel.style.display = 'block';
    goToSlide(0);
    startAutoPlay();

    // Enrich with full detail in background (non-blocking)
    enrichHeroDetails(heroMovies);
}

async function enrichHeroDetails(movies) {
    // Fetch full detail one by one for richer data (plot, ratings, cast)
    for (let i = 0; i < movies.length; i++) {
        try {
            const res  = await fetch('/movies/detail-json?i=' + movies[i].imdbID, { headers: {'Accept':'application/json'} });
            const data = await res.json();
            if (data && data.Response === 'True') {
                heroMovies[i] = { ...heroMovies[i], ...data };
                // Refresh content if this is the current slide
                if (i === heroIndex) goToSlide(heroIndex);
            }
        } catch(e) {}
        // Small delay to avoid rate limiting
        await new Promise(r => setTimeout(r, 300));
    }
}

function goToSlide(idx) {
    if (!heroMovies.length) return;
    heroIndex = ((idx % heroMovies.length) + heroMovies.length) % heroMovies.length;

    // Move slides
    document.getElementById('heroSlides').style.transform = `translateX(-${heroIndex * 100}%)`;

    // Update dots
    document.querySelectorAll('#heroDots button').forEach((d, i) => {
        d.style.background    = i === heroIndex ? '#E50914' : 'rgba(255,255,255,0.4)';
        d.style.width         = i === heroIndex ? '24px' : '8px';
        d.style.borderRadius  = i === heroIndex ? '4px' : '50%';
    });

    // Update counter
    document.getElementById('heroCounter').textContent = (heroIndex + 1) + ' / ' + heroMovies.length;

    // Update content
    const m = heroMovies[heroIndex];
    heroImdbId = m.imdbID;

    // Badge
    document.getElementById('hBadge').innerHTML = `
        <span style="background:rgba(229,9,20,0.15);border:1px solid rgba(229,9,20,0.4);color:#ff6b6b;padding:4px 10px;border-radius:4px;font-size:0.72rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;">
            <i class="fas fa-fire"></i> Box Office ${m.Year || '2025'}
        </span>
        <span style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);color:#aaa;padding:4px 10px;border-radius:4px;font-size:0.72rem;font-weight:600;">
            #${heroIndex + 1} {{ __('movies.trending') }}
        </span>
    `;

    // Genres
    const genre = (m.Genre && m.Genre !== 'N/A') ? m.Genre : '';
    document.getElementById('hGenres').innerHTML = genre
        .split(',').slice(0,4)
        .map(g => `<span class="genre-tag">${g.trim()}</span>`).join('');

    // Title
    document.getElementById('hTitle').textContent = m.Title || '';

    // Meta
    const rating = (m.imdbRating && m.imdbRating !== 'N/A')
        ? `<span style="color:#f5c518;font-weight:800;font-size:1rem;"><i class="fas fa-star"></i> ${m.imdbRating}<span style="color:#888;font-size:0.8rem;font-weight:400;">/10</span></span>` : '';
    const year    = m.Year    ? `<span style="color:#46d369;font-weight:600;">${m.Year}</span>` : '';
    const runtime = (m.Runtime && m.Runtime !== 'N/A') ? `<span style="color:#aaa;"><i class="fas fa-clock" style="margin-right:4px;font-size:0.8rem;"></i>${m.Runtime}</span>` : '';
    const rated   = (m.Rated  && m.Rated   !== 'N/A') ? `<span style="border:1px solid #555;padding:2px 7px;font-size:0.75rem;border-radius:3px;color:#aaa;">${m.Rated}</span>` : '';
    const type    = `<span style="background:rgba(0,113,235,0.2);border:1px solid rgba(0,113,235,0.4);color:#5b9cf6;padding:2px 8px;border-radius:3px;font-size:0.75rem;font-weight:700;text-transform:uppercase;">${m.Type || 'movie'}</span>`;
    document.getElementById('hMeta').innerHTML = [rating, year, runtime, rated, type].filter(Boolean).join('');

    // Ratings badges
    const ratings = m.Ratings || [];
    document.getElementById('hRatings').innerHTML = ratings.slice(0, 3).map(r => {
        const icons = { 'Internet Movie Database': 'fas fa-film', 'Rotten Tomatoes': 'fas fa-pepper-hot', 'Metacritic': 'fas fa-gamepad' };
        const icon = icons[r.Source] || 'fas fa-star';
        return `<span style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.1);padding:5px 10px;border-radius:6px;font-size:0.8rem;display:flex;align-items:center;gap:6px;">
            <i class="${icon}" style="color:#f5c518;font-size:0.75rem;"></i>
            <span style="color:#aaa;font-size:0.72rem;">${r.Source.replace('Internet Movie Database','IMDb').replace('Rotten Tomatoes','RT')}</span>
            <strong>${r.Value}</strong>
        </span>`;
    }).join('');

    // Plot
    const plot = (m.Plot && m.Plot !== 'N/A') ? m.Plot : '{{ __("movies.hero_loading") }}';
    document.getElementById('hPlot').textContent = plot;

    // Cast
    const actors = (m.Actors && m.Actors !== 'N/A') ? m.Actors : '';
    document.getElementById('hCast').innerHTML = actors
        ? `<i class="fas fa-users" style="margin-right:6px;color:#888;"></i><span style="color:#888;">{{ __("movies.cast") }}:</span> ${actors}`
        : '';

    // Link & fav
    document.getElementById('hLink').href = '/movies/' + m.imdbID;
    updateHeroFavBtn();
}

function heroSlide(dir) {
    resetAutoPlay();
    goToSlide(heroIndex + dir);
}

function startAutoPlay() {
    heroAutoTimer = setInterval(() => goToSlide(heroIndex + 1), 6000);
}

function resetAutoPlay() {
    clearInterval(heroAutoTimer);
    startAutoPlay();
}

// Pause on hover
document.addEventListener('DOMContentLoaded', () => {
    const c = document.getElementById('heroCarousel');
    if (c) {
        c.addEventListener('mouseenter', () => clearInterval(heroAutoTimer));
        c.addEventListener('mouseleave', () => startAutoPlay());
    }
});

// Swipe support
let touchStartX = 0;
document.addEventListener('DOMContentLoaded', () => {
    const c = document.getElementById('heroCarousel');
    if (!c) return;
    c.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
    c.addEventListener('touchend', e => {
        const diff = touchStartX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) heroSlide(diff > 0 ? 1 : -1);
    });
});

function updateHeroFavBtn() {
    if (!heroImdbId) return;
    const btn  = document.getElementById('hFavBtn');
    const isFav = favoriteIds.has(heroImdbId);
    btn.className = 'btn ' + (isFav ? 'btn-primary' : 'btn-secondary');
    btn.innerHTML = `<i class="${isFav ? 'fas' : 'far'} fa-heart"></i> ${isFav ? '{{ __("favorites.remove") }}' : '{{ __("favorites.add") }}'}`;
}

async function heroFavToggle() {
    if (!heroImdbId) return;
    const m   = heroMovies[heroIndex];
    const btn = document.getElementById('hFavBtn');
    btn.disabled = true;
    const isFav = favoriteIds.has(heroImdbId);
    try {
        let res;
        if (isFav) {
            res = await fetch('/favorites/' + heroImdbId, { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'} });
        } else {
            res = await fetch('/favorites', {
                method:'POST',
                headers:{'X-CSRF-TOKEN':CSRF,'Content-Type':'application/json','Accept':'application/json'},
                body: JSON.stringify({ imdb_id: m.imdbID, title: m.Title, year: m.Year||'', poster: (m.Poster && m.Poster!=='N/A') ? m.Poster : '', type: m.Type||'movie' })
            });
        }
        const data = await res.json();
        if (data.success) {
            data.favorited ? favoriteIds.add(heroImdbId) : favoriteIds.delete(heroImdbId);
            updateHeroFavBtn();
            document.querySelectorAll(`.fav-btn[data-id="${heroImdbId}"]`).forEach(b => {
                b.classList.toggle('favorited', data.favorited);
                b.querySelector('i').className = data.favorited ? 'fas fa-heart' : 'far fa-heart';
            });
            showToast(data.message, 'success');
        }
    } catch(e) { showToast('Error','error'); }
    finally { btn.disabled = false; }
}

// ─── Build movie card ─────────────────────────────────────
function buildCard(movie) {
    const isFav     = favoriteIds.has(movie.imdbID);
    const hasPoster = movie.Poster && movie.Poster !== 'N/A';
    const safeTitle = (movie.Title || '').replace(/\\/g,'\\\\').replace(/'/g,"\\'").replace(/"/g,'&quot;');
    const poster    = hasPoster ? movie.Poster : '';

    const a = document.createElement('a');
    a.href            = '/movies/' + movie.imdbID;
    a.className       = 'movie-card';
    a.style.textDecoration = 'none';

    a.innerHTML = `
        ${ hasPoster
            ? `<img data-src="${poster}" alt="${safeTitle}" class="card-poster" style="opacity:0;transition:opacity 0.4s;">`
            : `<div class="card-poster-placeholder"><i class="fas fa-film"></i><span>${movie.Title || ''}</span></div>`
        }
        <button class="fav-btn ${isFav ? 'favorited' : ''}"
            data-id="${movie.imdbID}"
            data-title="${safeTitle}"
            data-year="${movie.Year || ''}"
            data-poster="${poster}"
            data-type="${movie.Type || 'movie'}"
            title="Favorite">
            <i class="${isFav ? 'fas' : 'far'} fa-heart"></i>
        </button>
        <div class="card-overlay">
            <div class="card-title">${movie.Title || ''}</div>
            <div class="card-meta">
                <span class="year-badge">${movie.Year || ''}</span>
                <span class="card-type-badge badge-${movie.Type === 'series' ? 'series' : 'movie'}">${movie.Type || 'movie'}</span>
            </div>
        </div>
    `;

    // Fav button click — use event delegation via data attributes
    a.querySelector('.fav-btn').addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        handleFav(
            this,
            this.dataset.id,
            this.dataset.title,
            this.dataset.year,
            this.dataset.poster,
            this.dataset.type
        );
    });

    return a;
}

// ─── Fav toggle ───────────────────────────────────────────
async function handleFav(btn, imdbId, title, year, poster, type) {
    btn.style.pointerEvents = 'none';
    const isFav = favoriteIds.has(imdbId);
    try {
        let res;
        if (isFav) {
            res = await fetch('/favorites/' + imdbId, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            });
        } else {
            res = await fetch('/favorites', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ imdb_id: imdbId, title, year, poster, type })
            });
        }
        const data = await res.json();
        if (data.success) {
            data.favorited ? favoriteIds.add(imdbId) : favoriteIds.delete(imdbId);
            btn.classList.toggle('favorited', data.favorited);
            btn.querySelector('i').className = data.favorited ? 'fas fa-heart' : 'far fa-heart';
            showToast(data.message, 'success');
            // Update all cards with same imdbId
            document.querySelectorAll(`.fav-btn[data-id="${imdbId}"]`).forEach(b => {
                b.classList.toggle('favorited', data.favorited);
                b.querySelector('i').className = data.favorited ? 'fas fa-heart' : 'far fa-heart';
            });
            updateHeroFavBtn();
        }
    } catch(e) { showToast('Error', 'error'); }
    finally { btn.style.pointerEvents = ''; }
}

// ─── Load category rows ───────────────────────────────────
async function loadCategory(rowId, query) {
    const row = document.getElementById('row-' + rowId);
    try {
        const res  = await fetch('/movies/search?q=' + encodeURIComponent(query) + '&type=movie', { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        const movies = data.Search || [];

        row.innerHTML = '';

        if (!movies.length) {
            row.closest('section').style.display = 'none';
            return;
        }

        movies.forEach(m => row.appendChild(buildCard(m)));
        observeLazy(row);

    } catch(e) {
        row.closest('section').style.display = 'none';
    }
}

// ─── Search ───────────────────────────────────────────────
let searchPage    = 1;
let searchTotal   = 0;
let searchLoading = false;
let searchHasMore = false;
let currentQuery  = '';
let currentType   = '';
let currentYear   = '';

function doSearch() {
    currentQuery = document.getElementById('searchInput').value.trim();
    currentType  = document.getElementById('typeFilter').value;
    currentYear  = document.getElementById('yearFilter').value.trim();
    searchPage   = 1;
    searchTotal  = 0;
    searchHasMore = false;

    document.getElementById('searchGrid').innerHTML = '';
    document.getElementById('endMessage').style.display = 'none';

    // Trigger search jika ada query ATAU year ATAU type dipilih
    const hasFilter = currentQuery || currentYear || currentType;

    if (hasFilter) {
        document.getElementById('categoriesSection').style.display    = 'none';
        document.getElementById('heroSection').style.display          = 'none';
        document.getElementById('searchResultsSection').style.display = 'block';

        // Build label
        let label = '{{ __("movies.search_results") }}';
        const parts = [];
        if (currentQuery) parts.push('"' + currentQuery + '"');
        if (currentType)  parts.push('{{ __("movies.type") }}: ' + currentType);
        if (currentYear)  parts.push('{{ __("movies.year") }}: ' + currentYear);
        if (parts.length) label = '{{ __("movies.results_for") }}: ' + parts.join(' · ');
        document.getElementById('searchResultsTitle').textContent = label;

        fetchSearchPage();
    } else {
        document.getElementById('categoriesSection').style.display    = 'block';
        document.getElementById('heroSection').style.display          = 'flex';
        document.getElementById('searchResultsSection').style.display = 'none';
    }
}

async function fetchSearchPage() {
    if (searchLoading || (!searchHasMore && searchPage > 1)) return;
    searchLoading = true;
    document.getElementById('loadingSpinner').style.display = 'flex';

    try {
        const params = new URLSearchParams({
            q:    currentQuery,
            type: currentType,
            year: currentYear,
            page: searchPage,
        });
        const res    = await fetch('/movies/search?' + params, { headers: { 'Accept': 'application/json' } });
        const data   = await res.json();
        const movies = data.Search || [];
        const total  = parseInt(data.totalResults || 0);

        if (searchPage === 1) {
            searchTotal = total;
            document.getElementById('searchResultsTitle').textContent =
                '{{ __("movies.results_for") }}: "' + currentQuery + '" (' + total + ' {{ __("movies.results") }})';
        }

        const grid = document.getElementById('searchGrid');

        if (!movies.length && searchPage === 1) {
            grid.innerHTML = `
                <div class="empty-state" style="grid-column:1/-1">
                    <i class="fas fa-film"></i>
                    <h3>{{ __('movies.no_results') }}</h3>
                    <p>{{ __('movies.no_results_desc') }}</p>
                </div>`;
            searchHasMore = false;
        } else {
            const fragment = document.createDocumentFragment();
            movies.forEach(m => fragment.appendChild(buildCard(m)));
            grid.appendChild(fragment);
            observeLazy(grid); // <-- observe AFTER appending

            searchPage++;
            const loaded = grid.querySelectorAll('.movie-card').length;
            searchHasMore = loaded < total && movies.length > 0;

            if (!searchHasMore) {
                document.getElementById('endMessage').style.display = 'block';
            }
        }
    } catch(e) { console.error(e); }
    finally {
        searchLoading = false;
        document.getElementById('loadingSpinner').style.display = 'none';
    }
}

// Infinite scroll
new IntersectionObserver(entries => {
    if (entries[0].isIntersecting && searchHasMore && !searchLoading) fetchSearchPage();
}, { rootMargin: '400px' }).observe(document.getElementById('searchSentinel'));

// ─── Autocomplete ─────────────────────────────────────────
let acTimer;
const searchInput = document.getElementById('searchInput');
const acBox       = document.getElementById('autocompleteBox');

searchInput.addEventListener('input', () => {
    clearTimeout(acTimer);
    const q = searchInput.value.trim();
    if (q.length < 3) { acBox.style.display = 'none'; return; }
    acTimer = setTimeout(async () => {
        try {
            const res  = await fetch('/movies/autocomplete?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            if (!data.length) { acBox.style.display = 'none'; return; }
            acBox.innerHTML = data.map(s => `
                <div class="autocomplete-item" onclick="selectAc('${s.title.replace(/'/g,"\\'")}')">
                    <i class="fas fa-film" style="color:var(--text-muted);font-size:0.8rem;flex-shrink:0;"></i>
                    <span>${s.title}</span>
                    <span class="ac-year">${s.year}</span>
                </div>
            `).join('');
            acBox.style.display = 'block';
        } catch(e) {}
    }, 300);
});

function selectAc(title) {
    searchInput.value = title;
    acBox.style.display = 'none';
    doSearch();
}

searchInput.addEventListener('keydown', e => {
    if (e.key === 'Enter') { acBox.style.display = 'none'; doSearch(); }
});
document.addEventListener('click', e => {
    if (!searchInput.contains(e.target) && !acBox.contains(e.target)) acBox.style.display = 'none';
});

// Auto search when type or year filter changes
document.getElementById('typeFilter').addEventListener('change', doSearch);
document.getElementById('yearFilter').addEventListener('keydown', e => {
    if (e.key === 'Enter') doSearch();
});
// Also trigger when year is cleared
document.getElementById('yearFilter').addEventListener('input', function() {
    if (this.value === '') doSearch();
});

// ─── Init ─────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    initHeroCarousel();
    loadCategory('trending', 'avengers');
    loadCategory('action',   'action');
    loadCategory('drama',    'drama');
    loadCategory('comedy',   'comedy');
});
</script>
@endpush

@endsection
