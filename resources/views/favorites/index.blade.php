@extends('layouts.app')

@section('title', __('favorites.my_favorites'))

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-heart" style="color:#E50914;margin-right:12px"></i>{{ __('favorites.my_favorites') }}</h1>
    @if($favorites->count() > 0)
    <p style="color:var(--text-secondary);margin-top:8px;font-size:0.9rem;">
        {{ $favorites->count() }} {{ __('favorites.saved_movies') }}
    </p>
    @endif
</div>
<div class="section-divider"></div>

@if($favorites->isEmpty())
<div class="empty-state">
    <i class="far fa-heart"></i>
    <h3>{{ __('favorites.empty_title') }}</h3>
    <p>{{ __('favorites.empty_desc') }}</p>
    <a href="{{ route('movies.index') }}" class="btn btn-primary" style="margin-top:24px">
        <i class="fas fa-film"></i> {{ __('favorites.browse_movies') }}
    </a>
</div>
@else
<div class="search-grid" id="favGrid">
    @foreach($favorites as $fav)
    <div class="movie-card" id="fav-{{ $fav->imdb_id }}" style="position:relative;">
        <a href="{{ route('movies.show', $fav->imdb_id) }}" style="text-decoration:none;display:block;">
            @if($fav->poster)
            <img
                data-src="{{ $fav->poster }}"
                alt="{{ $fav->title }}"
                class="card-poster"
                src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
            >
            @else
            <div class="card-poster-placeholder">
                <i class="fas fa-film"></i>
                <span>{{ $fav->title }}</span>
            </div>
            @endif
            <div class="card-overlay">
                <div class="card-title">{{ $fav->title }}</div>
                <div class="card-meta">
                    @if($fav->year)
                    <span class="year-badge">{{ $fav->year }}</span>
                    @endif
                    @if($fav->type)
                    <span class="card-type-badge badge-{{ $fav->type === 'series' ? 'series' : 'movie' }}">{{ ucfirst($fav->type) }}</span>
                    @endif
                </div>
            </div>
        </a>
        <button
            class="fav-btn favorited"
            style="opacity:1;"
            onclick="removeFavorite('{{ $fav->imdb_id }}')"
            title="{{ __('favorites.remove') }}"
        >
            <i class="fas fa-heart"></i>
        </button>
    </div>
    @endforeach
</div>
@endif

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

async function removeFavorite(imdbId) {
    if (!confirm('{{ __("favorites.confirm_remove") }}')) return;

    try {
        const res = await fetch(`/favorites/${imdbId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (data.success) {
            const card = document.getElementById(`fav-${imdbId}`);
            card.style.transition = 'opacity 0.3s, transform 0.3s';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => {
                card.remove();
                const remaining = document.querySelectorAll('#favGrid .movie-card').length;
                if (remaining === 0) location.reload();
            }, 300);
            showToast(data.message, 'success');
        }
    } catch(e) {
        showToast('{{ __("favorites.error") }}', 'error');
    }
}
</script>
@endpush
@endsection
