<?php $__env->startSection('title', $movie['Title'] ?? 'Movie Detail'); ?>

<?php $__env->startSection('content'); ?>
<article>
    
    <div class="detail-hero">
        <div class="detail-bg">
            <?php if(isset($movie['Poster']) && $movie['Poster'] !== 'N/A'): ?>
            <img src="<?php echo e($movie['Poster']); ?>" alt="<?php echo e($movie['Title']); ?>">
            <?php else: ?>
            <div style="width:100%;height:100%;background:linear-gradient(135deg,#1a1a2e,#16213e,#0f3460)"></div>
            <?php endif; ?>
            <div class="detail-gradient"></div>
        </div>

        <div class="detail-content">
            <div class="detail-layout">
                
                <div class="detail-poster">
                    <?php if(isset($movie['Poster']) && $movie['Poster'] !== 'N/A'): ?>
                    <img src="<?php echo e($movie['Poster']); ?>" alt="<?php echo e($movie['Title']); ?>">
                    <?php else: ?>
                    <div style="aspect-ratio:2/3;background:linear-gradient(135deg,#1a1a2e,#16213e);display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-film" style="font-size:3rem;color:#555"></i>
                    </div>
                    <?php endif; ?>
                </div>

                
                <div class="detail-info">
                    
                    <?php if(isset($movie['Genre']) && $movie['Genre'] !== 'N/A'): ?>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;">
                        <?php $__currentLoopData = explode(',', $movie['Genre']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $genre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="genre-tag"><?php echo e(trim($genre)); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>

                    <h1 class="detail-title"><?php echo e($movie['Title'] ?? 'Unknown'); ?></h1>

                    <div class="detail-meta">
                        <?php if(isset($movie['Year']) && $movie['Year'] !== 'N/A'): ?>
                        <span class="year-badge"><?php echo e($movie['Year']); ?></span>
                        <?php endif; ?>
                        <?php if(isset($movie['Runtime']) && $movie['Runtime'] !== 'N/A'): ?>
                        <span><?php echo e($movie['Runtime']); ?></span>
                        <?php endif; ?>
                        <?php if(isset($movie['Rated']) && $movie['Rated'] !== 'N/A'): ?>
                        <span style="border:1px solid #aaa;padding:2px 7px;font-size:0.75rem;border-radius:3px;"><?php echo e($movie['Rated']); ?></span>
                        <?php endif; ?>
                        <?php if(isset($movie['imdbRating']) && $movie['imdbRating'] !== 'N/A'): ?>
                        <span class="detail-rating"><i class="fas fa-star" style="color:#f5c518"></i> <?php echo e($movie['imdbRating']); ?>/10</span>
                        <?php endif; ?>
                    </div>

                    
                    <?php if(isset($movie['Ratings']) && count($movie['Ratings']) > 0): ?>
                    <div class="ratings-row">
                        <?php $__currentLoopData = $movie['Ratings']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rating-badge">
                            <div class="source"><?php echo e($rating['Source']); ?></div>
                            <div class="value"><?php echo e($rating['Value']); ?></div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>

                    <p class="detail-plot"><?php echo e($movie['Plot'] ?? ''); ?></p>

                    <div class="detail-actions">
                        <a href="<?php echo e(route('movies.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('movies.back')); ?>

                        </a>
                        <button
                            id="favBtn"
                            class="btn <?php echo e($isFavorite ? 'btn-primary' : 'btn-outline'); ?>"
                            onclick="toggleFavDetail()"
                        >
                            <i class="<?php echo e($isFavorite ? 'fas' : 'far'); ?> fa-heart"></i>
                            <span id="favBtnText"><?php echo e($isFavorite ? __('favorites.remove') : __('favorites.add')); ?></span>
                        </button>
                    </div>

                    
                    <div class="detail-stats">
                        <?php if(isset($movie['Director']) && $movie['Director'] !== 'N/A'): ?>
                        <div class="stat-item">
                            <div class="stat-label"><?php echo e(__('movies.director')); ?></div>
                            <div class="stat-value"><?php echo e($movie['Director']); ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if(isset($movie['Actors']) && $movie['Actors'] !== 'N/A'): ?>
                        <div class="stat-item">
                            <div class="stat-label"><?php echo e(__('movies.cast')); ?></div>
                            <div class="stat-value"><?php echo e($movie['Actors']); ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if(isset($movie['Writer']) && $movie['Writer'] !== 'N/A'): ?>
                        <div class="stat-item">
                            <div class="stat-label"><?php echo e(__('movies.writer')); ?></div>
                            <div class="stat-value"><?php echo e($movie['Writer']); ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if(isset($movie['Language']) && $movie['Language'] !== 'N/A'): ?>
                        <div class="stat-item">
                            <div class="stat-label"><?php echo e(__('movies.language')); ?></div>
                            <div class="stat-value"><?php echo e($movie['Language']); ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if(isset($movie['Country']) && $movie['Country'] !== 'N/A'): ?>
                        <div class="stat-item">
                            <div class="stat-label"><?php echo e(__('movies.country')); ?></div>
                            <div class="stat-value"><?php echo e($movie['Country']); ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if(isset($movie['BoxOffice']) && $movie['BoxOffice'] !== 'N/A'): ?>
                        <div class="stat-item">
                            <div class="stat-label"><?php echo e(__('movies.box_office')); ?></div>
                            <div class="stat-value"><?php echo e($movie['BoxOffice']); ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if(isset($movie['Awards']) && $movie['Awards'] !== 'N/A'): ?>
                        <div class="stat-item">
                            <div class="stat-label"><?php echo e(__('movies.awards')); ?></div>
                            <div class="stat-value"><?php echo e($movie['Awards']); ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if(isset($movie['Released']) && $movie['Released'] !== 'N/A'): ?>
                        <div class="stat-item">
                            <div class="stat-label"><?php echo e(__('movies.released')); ?></div>
                            <div class="stat-value"><?php echo e($movie['Released']); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <section class="row-section" style="margin-top:40px;padding-bottom:60px;">
        <h2 class="row-title"><?php echo e(__('movies.more_like_this')); ?></h2>
        <div class="search-grid" id="similarGrid"></div>
        <div style="text-align:center;padding:32px 0;">
            <div class="spinner" id="similarSpinner" style="display:none;"><div class="spinner-ring"></div></div>
        </div>
        <div id="similarSentinel" style="height:10px;"></div>
    </section>
</article>

<?php $__env->startPush('scripts'); ?>
<script>
const imdbId = <?php echo json_encode($movie['imdbID'] ?? '', 15, 512) ?>;
const movieTitle = <?php echo json_encode($movie['Title'] ?? '', 15, 512) ?>;
const movieYear = <?php echo json_encode($movie['Year'] ?? '', 15, 512) ?>;
const moviePoster = <?php echo json_encode(($movie['Poster'] ?? '') !== 'N/A' ? ($movie['Poster'] ?? '') : '', 15, 512) ?>;
const movieType = <?php echo json_encode($movie['Type'] ?? 'movie', 15, 512) ?>;
let isFavorite = <?php echo e($isFavorite ? 'true' : 'false'); ?>;
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
            btnText.textContent = isFavorite ? '<?php echo e(__("favorites.remove")); ?>' : '<?php echo e(__("favorites.add")); ?>';
            showToast(data.message, 'success');
        }
    } catch(e) {
        showToast('<?php echo e(__("favorites.error")); ?>', 'error');
    } finally {
        btn.disabled = false;
    }
}

// ─── Infinite Scroll "Mungkin Anda Suka" ─────────────────────────────────────
// Ambil genre pertama dari film ini untuk seed query awal,
// lalu rotasi ke keyword populer agar hasilnya terus bervariasi
const SIMILAR_QUERIES = (() => {
    const genre = <?php echo json_encode(isset($movie['Genre']) ? trim(explode(', ', $movie['Genre'])[0]) : '') ?>;
    const base = [
        genre,
        'action', 'drama', 'comedy', 'thriller', 'adventure',
        'science fiction', 'horror', 'romance', 'animation', 'crime',
        'fantasy', 'mystery', 'biography', 'history', 'family',
    ].filter(Boolean);
    return base;
})();

let simPage        = 1;
let simQueryIndex  = 0;
let simLoading     = false;
let simExhausted   = false;
const simGrid      = document.getElementById('similarGrid');
const simSpinner   = document.getElementById('similarSpinner');
const simSeenIds   = new Set([imdbId]); // jangan tampilkan film yang sedang dibuka

function buildSimCard(movie) {
    const hasPoster = movie.Poster && movie.Poster !== 'N/A';
    const poster    = hasPoster ? movie.Poster : '';
    const safeTitle = (movie.Title || '').replace(/"/g, '&quot;');

    const a = document.createElement('a');
    a.href      = '/movies/' + movie.imdbID;
    a.className = 'movie-card';
    a.style.textDecoration = 'none';

    a.innerHTML = `
        ${ hasPoster
            ? `<img data-src="${poster}" alt="${safeTitle}" class="card-poster" style="opacity:0;transition:opacity 0.4s;">`
            : `<div class="card-poster-placeholder"><i class="fas fa-film"></i><span>${movie.Title || ''}</span></div>`
        }
        <div class="card-overlay">
            <div class="card-title">${movie.Title || ''}</div>
            <div class="card-meta">
                <span class="year-badge">${movie.Year || ''}</span>
                <span class="card-type-badge badge-${movie.Type === 'series' ? 'series' : 'movie'}">${movie.Type || 'movie'}</span>
            </div>
        </div>
    `;
    return a;
}

async function loadSimilar() {
    if (simLoading || simExhausted) return;
    simLoading = true;
    simSpinner.style.display = 'flex';

    try {
        const query = SIMILAR_QUERIES[simQueryIndex % SIMILAR_QUERIES.length];
        const params = new URLSearchParams({ q: query, type: 'movie', page: simPage });
        const res    = await fetch('/movies/search?' + params, { headers: { 'Accept': 'application/json' } });
        const data   = await res.json();
        const movies = (data.Search || []).filter(m => !simSeenIds.has(m.imdbID));

        if (!movies.length) {
            // Query ini habis → coba query berikutnya dari page 1
            simQueryIndex++;
            simPage = 1;
            if (simQueryIndex >= SIMILAR_QUERIES.length) {
                simExhausted = true;
            }
        } else {
            const fragment = document.createDocumentFragment();
            movies.forEach(m => {
                simSeenIds.add(m.imdbID);
                fragment.appendChild(buildSimCard(m));
            });
            simGrid.appendChild(fragment);
            observeLazy(simGrid);

            // Cek apakah masih ada halaman berikutnya
            const total  = parseInt(data.totalResults || 0);
            const loaded = simGrid.querySelectorAll('.movie-card').length;
            simPage++;
            if (simPage > Math.ceil(total / 10)) {
                // Pindah ke query berikutnya
                simQueryIndex++;
                simPage = 1;
            }
        }
    } catch(e) {
        console.error('Similar load error:', e);
    } finally {
        simLoading = false;
        simSpinner.style.display = 'none';
        // Cek lagi setelah load selesai — jika sentinel masih di viewport, lanjut load
        checkAndLoad();
    }
}

// Scroll-based infinite scroll — lebih reliable dari IntersectionObserver
function checkAndLoad() {
    if (simLoading || simExhausted) return;
    const sentinel = document.getElementById('similarSentinel');
    const rect = sentinel.getBoundingClientRect();
    // Trigger jika sentinel dalam 800px dari bawah viewport
    if (rect.top < window.innerHeight + 800) {
        loadSimilar();
    }
}

// Listen scroll di window
window.addEventListener('scroll', checkAndLoad, { passive: true });

// Polling sebagai fallback — cek setiap 500ms kalau scroll tidak trigger
setInterval(checkAndLoad, 500);

// Load pertama langsung
document.addEventListener('DOMContentLoaded', () => loadSimilar());
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\bubub-movie-baru\resources\views/movies/show.blade.php ENDPATH**/ ?>