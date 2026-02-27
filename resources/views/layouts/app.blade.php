<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bubub Movie') — Bubub Movie</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --netflix-red: #E50914;
            --netflix-red-dark: #B81D24;
            --bg-primary: #141414;
            --bg-secondary: #1f1f1f;
            --bg-card: #2f2f2f;
            --text-primary: #FFFFFF;
            --text-secondary: #B3B3B3;
            --text-muted: #6B6B6B;
            --border: #333;
            --overlay: rgba(0,0,0,0.7);
            --shadow: 0 4px 20px rgba(0,0,0,0.5);
        }

        html, body { background: var(--bg-primary); color: var(--text-primary); font-family: 'Inter', sans-serif; min-height: 100vh; }

        /* ── Navbar ── */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            padding: 0 4%; height: 68px;
            display: flex; align-items: center; justify-content: space-between;
            background: linear-gradient(to bottom, rgba(0,0,0,0.9) 0%, transparent 100%);
            transition: background 0.3s ease;
        }
        .navbar.scrolled { background: var(--bg-primary); box-shadow: 0 2px 10px rgba(0,0,0,0.8); }

        .navbar-brand { display: flex; align-items: center; gap: 8px; text-decoration: none; }
        .navbar-logo { font-size: 1.8rem; font-weight: 900; color: var(--netflix-red); letter-spacing: -1px; }
        .navbar-logo span { color: var(--text-primary); }

        .navbar-nav { display: flex; align-items: center; gap: 20px; }
        .navbar-nav a {
            color: var(--text-secondary); text-decoration: none; font-size: 0.9rem;
            font-weight: 500; transition: color 0.2s;
        }
        .navbar-nav a:hover, .navbar-nav a.active { color: var(--text-primary); }

        .navbar-right { display: flex; align-items: center; gap: 16px; }

        .search-bar {
            display: flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
            border-radius: 4px; padding: 6px 12px; transition: all 0.3s;
        }
        .search-bar:focus-within { background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.4); }
        .search-bar input {
            background: none; border: none; color: var(--text-primary);
            font-size: 0.85rem; outline: none; width: 200px;
        }
        .search-bar input::placeholder { color: var(--text-secondary); }
        .search-bar i { color: var(--text-secondary); font-size: 0.85rem; }

        .lang-switcher {
            display: flex; gap: 4px;
        }
        .lang-switcher a {
            padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600;
            color: var(--text-secondary); text-decoration: none; transition: all 0.2s;
            border: 1px solid transparent;
        }
        .lang-switcher a.active { color: var(--text-primary); border-color: var(--text-secondary); }
        .lang-switcher a:hover { color: var(--text-primary); }

        .btn-logout {
            background: none; border: 1px solid var(--text-secondary);
            color: var(--text-secondary); padding: 6px 14px; border-radius: 4px;
            cursor: pointer; font-size: 0.8rem; font-weight: 500; transition: all 0.2s;
        }
        .btn-logout:hover { color: var(--text-primary); border-color: var(--text-primary); }

        /* ── Main Content ── */
        .main-content { padding-top: 68px; min-height: 100vh; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 24px; border-radius: 4px; font-weight: 600;
            font-size: 0.9rem; cursor: pointer; border: none; text-decoration: none;
            transition: all 0.2s; white-space: nowrap;
        }
        .btn-primary { background: var(--netflix-red); color: white; }
        .btn-primary:hover { background: var(--netflix-red-dark); color: white; }
        .btn-secondary { background: rgba(109,109,110,0.7); color: white; }
        .btn-secondary:hover { background: rgba(109,109,110,0.4); }
        .btn-outline { background: transparent; border: 2px solid white; color: white; }
        .btn-outline:hover { background: rgba(255,255,255,0.1); }

        /* ── Movie Card ── */
        .movie-card {
            position: relative; border-radius: 4px; overflow: hidden;
            cursor: pointer; transition: transform 0.3s ease, z-index 0s;
            background: var(--bg-card);
        }
        .movie-card:hover { transform: scale(1.05); z-index: 10; }
        .movie-card:hover .card-overlay { opacity: 1; }

        .card-poster {
            width: 100%; aspect-ratio: 2/3; object-fit: cover;
            display: block; background: var(--bg-card);
        }
        .card-poster.loaded { opacity: 1; }
        .card-poster[data-src] { opacity: 0; transition: opacity 0.3s; }

        .card-poster-placeholder {
            width: 100%; aspect-ratio: 2/3;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 8px;
        }
        .card-poster-placeholder i { font-size: 2rem; color: var(--text-muted); }
        .card-poster-placeholder span { font-size: 0.75rem; color: var(--text-muted); text-align: center; padding: 0 8px; }

        .card-overlay {
            position: absolute; bottom: 0; left: 0; right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.95));
            padding: 40px 12px 12px; opacity: 0; transition: opacity 0.3s;
        }
        .card-title { font-size: 0.85rem; font-weight: 600; color: white; line-height: 1.2; margin-bottom: 4px; }
        .card-meta { font-size: 0.75rem; color: var(--text-secondary); display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        .card-type-badge {
            font-size: 0.65rem; padding: 2px 6px; border-radius: 2px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .badge-movie { background: var(--netflix-red); color: white; }
        .badge-series { background: #0071EB; color: white; }

        .fav-btn {
            position: absolute; top: 8px; right: 8px;
            background: rgba(0,0,0,0.7); border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%; width: 36px; height: 36px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s; opacity: 0; color: white;
            z-index: 5;
        }
        .movie-card:hover .fav-btn { opacity: 1; }
        .fav-btn.favorited { border-color: var(--netflix-red); color: var(--netflix-red); }
        .fav-btn:hover { background: rgba(0,0,0,0.9); transform: scale(1.1); }
        .fav-btn i { font-size: 0.85rem; }

        /* ── Rows ── */
        .row-section { padding: 0 4%; margin-bottom: 32px; }
        .row-title { font-size: 1.2rem; font-weight: 700; color: var(--text-primary); margin-bottom: 12px; }

        .movies-row {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 8px;
        }
        @media (min-width: 768px) { .movies-row { grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); } }
        @media (min-width: 1200px) { .movies-row { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); } }

        /* ── Search Results Grid ── */
        .search-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 12px; padding: 0 4%;
        }
        @media (min-width: 768px) { .search-grid { grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)); } }

        /* ── Hero Banner ── */
        .hero {
            position: relative; height: 85vh; min-height: 500px;
            display: flex; align-items: center; overflow: hidden;
        }
        .hero-bg {
            position: absolute; inset: 0;
            background: linear-gradient(to right, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 60%, transparent 100%),
                        linear-gradient(to top, var(--bg-primary) 0%, transparent 30%);
            z-index: 1;
        }
        .hero-poster {
            position: absolute; right: 0; top: 0; bottom: 0;
            width: 60%; object-fit: cover; object-position: top;
        }
        .hero-content { position: relative; z-index: 2; padding: 0 4%; max-width: 600px; }
        .hero-title { font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 900; line-height: 1.05; margin-bottom: 16px; }
        .hero-meta { display: flex; gap: 12px; align-items: center; margin-bottom: 16px; font-size: 0.9rem; flex-wrap: wrap; }
        .hero-rating { color: #46d369; font-weight: 700; }
        .hero-plot { color: var(--text-secondary); font-size: 1rem; line-height: 1.6; margin-bottom: 24px; max-width: 450px;
            display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }

        /* ── Filter Bar ── */
        .filter-bar {
            display: flex; gap: 12px; padding: 16px 4%; flex-wrap: wrap; align-items: center;
            background: linear-gradient(to bottom, transparent, var(--bg-primary) 50%);
        }
        .filter-select {
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
            color: var(--text-primary); padding: 8px 14px; border-radius: 4px;
            font-size: 0.85rem; cursor: pointer; outline: none;
            transition: border-color 0.2s;
        }
        .filter-select:focus { border-color: white; }
        .filter-select option { background: var(--bg-secondary); }

        /* ── Loading Spinner ── */
        .spinner {
            display: flex; justify-content: center; padding: 40px;
        }
        .spinner-ring {
            width: 40px; height: 40px; border: 3px solid var(--border);
            border-top-color: var(--netflix-red); border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Empty State ── */
        .empty-state {
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; padding: 80px 20px; text-align: center;
        }
        .empty-state i { font-size: 4rem; color: var(--text-muted); margin-bottom: 16px; }
        .empty-state h3 { font-size: 1.5rem; font-weight: 700; margin-bottom: 8px; }
        .empty-state p { color: var(--text-secondary); max-width: 400px; }

        /* ── Toast ── */
        .toast-container {
            position: fixed; bottom: 24px; right: 24px; z-index: 9999;
            display: flex; flex-direction: column; gap: 8px;
        }
        .toast {
            background: var(--bg-card); border: 1px solid var(--border);
            border-left: 4px solid var(--netflix-red);
            padding: 12px 20px; border-radius: 4px; font-size: 0.85rem;
            box-shadow: var(--shadow); color: var(--text-primary);
            animation: slideIn 0.3s ease;
            display: flex; align-items: center; gap: 10px; min-width: 280px;
        }
        .toast.success { border-left-color: #46d369; }
        .toast.error { border-left-color: var(--netflix-red); }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--bg-card); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        /* ── Misc ── */
        .section-divider { height: 1px; background: var(--border); margin: 0 4% 24px; }
        .page-header { padding: 32px 4% 16px; }
        .page-title { font-size: 1.8rem; font-weight: 800; }

        .year-badge { color: #46d369; font-size: 0.85rem; font-weight: 600; }

        /* ── Detail page ── */
        .detail-hero { position: relative; min-height: 70vh; display: flex; align-items: flex-end; }
        .detail-bg { position: absolute; inset: 0; overflow: hidden; }
        .detail-bg img { width: 100%; height: 100%; object-fit: cover; object-position: top; filter: blur(2px) brightness(0.3); }
        .detail-gradient { position: absolute; inset: 0; background: linear-gradient(to top, var(--bg-primary) 0%, transparent 60%); }
        .detail-content { position: relative; z-index: 2; padding: 40px 4% 40px; width: 100%; }
        .detail-layout { display: flex; gap: 32px; align-items: flex-start; }
        .detail-poster { width: 200px; flex-shrink: 0; border-radius: 8px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,0.8); }
        .detail-poster img { width: 100%; display: block; }
        .detail-info { flex: 1; }
        .detail-title { font-size: clamp(1.5rem, 4vw, 2.8rem); font-weight: 900; margin-bottom: 12px; }
        .detail-meta { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; font-size: 0.9rem; color: var(--text-secondary); align-items: center; }
        .detail-rating { color: #f5c518; font-weight: 700; font-size: 1rem; }
        .detail-plot { color: var(--text-secondary); line-height: 1.7; margin-bottom: 24px; font-size: 0.95rem; }
        .detail-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 32px; }
        .detail-stats { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
        .stat-item { background: rgba(255,255,255,0.05); border-radius: 8px; padding: 16px; }
        .stat-label { font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .stat-value { font-size: 0.95rem; font-weight: 600; color: var(--text-primary); }
        .genre-tag { display: inline-block; background: rgba(229,9,20,0.2); color: #ff6b6b; border: 1px solid rgba(229,9,20,0.3); padding: 4px 10px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }

        @media (max-width: 768px) {
            .detail-layout { flex-direction: column; }
            .detail-poster { width: 140px; }
            .navbar-nav { display: none; }
            .search-bar input { width: 140px; }
            .hero { height: 60vh; }
            .hero-poster { opacity: 0.4; width: 100%; }
        }

        /* Ratings badge */
        .ratings-row { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 16px; }
        .rating-badge { background: rgba(255,255,255,0.08); border-radius: 8px; padding: 8px 14px; text-align: center; }
        .rating-badge .source { font-size: 0.7rem; color: var(--text-muted); margin-bottom: 2px; }
        .rating-badge .value { font-size: 0.95rem; font-weight: 700; color: white; }
    </style>

    @stack('styles')
    <style>
    .nav-ac-item {
        padding: 10px 14px; cursor: pointer;
        display: flex; align-items: center; gap: 10px;
        border-bottom: 1px solid #2a2a2a;
        transition: background 0.15s; color: white;
    }
    .nav-ac-item:hover { background: rgba(255,255,255,0.07); }
    .nav-ac-item:last-child { border-bottom: none; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar" id="mainNavbar">
        <a href="{{ route('movies.index') }}" class="navbar-brand">
            <span class="navbar-logo">BUBUB<span>MOVIE</span></span>
        </a>

        <div class="navbar-nav">
            <a href="{{ route('movies.index') }}" class="{{ request()->routeIs('movies.*') ? 'active' : '' }}">
                {{ __('nav.movies') }}
            </a>
            <a href="{{ route('favorites.index') }}" class="{{ request()->routeIs('favorites.*') ? 'active' : '' }}">
                {{ __('nav.favorites') }}
            </a>
        </div>

        <div class="navbar-right">
            <!-- Search -->
            <div style="position:relative;">
                <form id="navSearchForm" action="{{ route('movies.index') }}" method="GET" class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" id="navSearchInput" name="q" value="{{ request('q') }}" placeholder="{{ __('nav.search_placeholder') }}" autocomplete="off">
                </form>
                <div id="navAcBox" style="display:none;position:absolute;top:calc(100% + 6px);right:0;width:280px;background:#1f1f1f;border:1px solid #333;border-radius:6px;z-index:2000;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,0.6);"></div>
            </div>

            <!-- Language Switcher -->
            <div class="lang-switcher">
                <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                <a href="{{ route('lang.switch', 'id') }}" class="{{ app()->getLocale() === 'id' ? 'active' : '' }}">ID</a>
            </div>

            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            document.getElementById('mainNavbar').classList.toggle('scrolled', window.scrollY > 50);
        });

        // Toast function
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.animation = 'none';
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Global persistent lazy load observer
        const lazyObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const img = entry.target;
                const src = img.getAttribute('data-src');
                if (!src) return;
                img.src = src;
                img.onload  = () => { img.style.opacity = '1'; img.classList.add('loaded'); };
                img.onerror = () => {
                    const ph = document.createElement('div');
                    ph.className = 'card-poster-placeholder';
                    ph.innerHTML = '<i class="fas fa-film"></i><span>' + (img.alt || '') + '</span>';
                    img.replaceWith(ph);
                };
                lazyObserver.unobserve(img);
            });
        }, { rootMargin: '300px' });

        // observeLazy — call after appending new cards to DOM
        function observeLazy(container) {
            (container || document).querySelectorAll('img[data-src]').forEach(img => lazyObserver.observe(img));
        }

        document.addEventListener('DOMContentLoaded', () => {
            observeLazy(document);

            // ── Navbar Search Handler ──────────────────────────────
            // Jika di halaman index → intercept dan gunakan doSearch() langsung
            // Jika di halaman lain → biarkan redirect ke index dengan ?q=...
            const navForm  = document.getElementById('navSearchForm');
            const navInput = document.getElementById('navSearchInput');

            navForm.addEventListener('submit', function(e) {
                const q = navInput.value.trim();
                if (typeof doSearch === 'function') {
                    e.preventDefault();
                    const mainInput = document.getElementById('searchInput');
                    if (mainInput) mainInput.value = q;
                    doSearch();
                    const filterBar = document.querySelector('.filter-bar');
                    if (filterBar) filterBar.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                // Jika bukan halaman index → submit normal (redirect ke index)
            });

            // Autocomplete navbar — logika sama persis dengan search bawah
            const navAcBox = document.getElementById('navAcBox');
            let navAcTimer;

            navInput.addEventListener('input', () => {
                clearTimeout(navAcTimer);
                const q = navInput.value.trim();
                if (q.length < 2) { navAcBox.style.display = 'none'; return; }
                navAcTimer = setTimeout(async () => {
                    try {
                        const res  = await fetch('/movies/autocomplete?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } });
                        const data = await res.json();
                        if (!data.length) { navAcBox.style.display = 'none'; return; }
                        navAcBox.innerHTML = data.map(s => `
                            <div class="autocomplete-item" onclick="navAcSelect('${s.title.replace(/'/g, "\\'")}')">
                                <i class="fas fa-film" style="color:var(--text-muted);font-size:0.8rem;flex-shrink:0;"></i>
                                <span>${s.title}</span>
                                <span class="ac-year">${s.year}</span>
                            </div>
                        `).join('');
                        navAcBox.style.display = 'block';
                    } catch(e) {}
                }, 300);
            });

            // navAcSelect didaftarkan ke window agar bisa dipanggil dari onclick attribute
            window.navAcSelect = function(title) {
                document.getElementById('navAcBox').style.display = 'none';
                const mainInput = document.getElementById('searchInput');
                if (mainInput) {
                    mainInput.value = title;
                    selectAc(title);
                } else {
                    window.location.href = '/?' + new URLSearchParams({ q: title });
                }
            };

            navInput.addEventListener('keydown', e => {
                if (e.key === 'Enter') { navAcBox.style.display = 'none'; }
            });

            document.addEventListener('click', e => {
                if (!navInput.contains(e.target) && !navAcBox.contains(e.target)) {
                    navAcBox.style.display = 'none';
                }
            });

            // Jika dibuka dari redirect halaman lain dengan ?q=...
            const urlParams = new URLSearchParams(window.location.search);
            const qFromUrl  = urlParams.get('q');
            if (qFromUrl && typeof doSearch !== 'function') return;
            if (qFromUrl) {
                setTimeout(() => {
                    const mainInput = document.getElementById('searchInput');
                    if (mainInput && !mainInput.value) {
                        mainInput.value = qFromUrl;
                        navInput.value  = qFromUrl;
                        doSearch();
                    }
                }, 400);
            }
        });

        // Favorite toggle
        async function toggleFavorite(btn, imdbId, title, year, poster, type) {
            btn.style.pointerEvents = 'none';
            const isFav = btn.classList.contains('favorited');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            try {
                let response;
                if (isFav) {
                    response = await fetch(`/favorites/${imdbId}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                    });
                } else {
                    response = await fetch('/favorites', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ imdb_id: imdbId, title, year, poster, type })
                    });
                }

                const data = await response.json();
                if (data.success) {
                    btn.classList.toggle('favorited', data.favorited);
                    const icon = btn.querySelector('i');
                    if (icon) {
                        icon.className = data.favorited ? 'fas fa-heart' : 'far fa-heart';
                    }
                    showToast(data.message, 'success');
                }
            } catch (e) {
                showToast('{{ __("favorites.error") }}', 'error');
            } finally {
                btn.style.pointerEvents = '';
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
