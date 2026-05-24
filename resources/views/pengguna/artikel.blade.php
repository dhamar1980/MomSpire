@extends('pengguna.master')

@section('title', 'Artikel Edukasi - MomSpire')
@section('header_title', 'Artikel Edukasi')
@section('header_subtitle', '')

@push('head')
<script>
    window.__MOMSPIRE_HEADER_TITLE_LOCK = 'Artikel Edukasi';
</script>
<style>
    .pengguna-dashboard-shell {
        position: relative;
        isolation: isolate;
        background: linear-gradient(180deg, #ffffff 0%, #f0f4f8 100%);
        min-height: 100vh;
    }

    .pengguna-dashboard-shell::before {
        content: '';
        position: fixed;
        inset: 0;
        background:
            radial-gradient(circle at 12% 8%, rgba(230, 57, 128, 0.15), transparent 28%),
            radial-gradient(circle at 92% 14%, rgba(107, 66, 193, 0.12), transparent 26%),
            radial-gradient(circle at 20% 80%, rgba(0, 184, 148, 0.08), transparent 32%);
        z-index: -2;
        pointer-events: none;
    }

    .pengguna-dashboard-shell::after {
        content: '';
        position: fixed;
        inset: 0;
        background-image: linear-gradient(rgba(15, 23, 42, 0.015) 1px, transparent 1px), linear-gradient(90deg, rgba(15, 23, 42, 0.015) 1px, transparent 1px);
        background-size: 42px 42px;
        opacity: .3;
        pointer-events: none;
        z-index: -1;
    }

    .article-panel {
        background: #fff;
        border-radius: 20px;
        border: 1px solid rgba(148, 163, 184, 0.16);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        padding: 24px;
    }

    .article-panel-soft {
        background: linear-gradient(135deg, #fff 0%, #fff7fb 100%);
        border-radius: 22px;
        border: 1px solid rgba(148, 163, 184, 0.16);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.06);
        padding: 24px;
    }

    .article-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 20px;
    }

    .article-card {
        background: #fff;
        border: 1px solid rgba(148, 163, 184, 0.18);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        height: 100%;
    }

    .article-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.09);
        border-color: rgba(230, 57, 128, 0.28);
    }

    .article-card__image {
        width: 100%;
        height: 220px;
        object-fit: contain;
        object-position: center;
        background: #f8fafc;
        padding: 12px;
        display: block;
    }

    .article-card__body {
        padding: 18px;
    }

    .article-meta {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
        margin-bottom: .75rem;
    }

    .chip-soft {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: .28rem .7rem;
        font-size: .76rem;
        font-weight: 700;
        background: rgba(230, 57, 128, 0.09);
        color: #be185d;
    }

    .recommended-strip {
        display: flex;
        flex-wrap: wrap;
        gap: .55rem;
    }

    .recommended-pill {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .45rem .8rem;
        border-radius: 999px;
        border: 1px solid rgba(230, 57, 128, 0.16);
        background: rgba(230, 57, 128, 0.06);
        color: #9d174d;
        font-weight: 600;
        text-decoration: none;
    }

    .recommended-pill:hover {
        color: #9d174d;
        border-color: rgba(230, 57, 128, 0.28);
        background: rgba(230, 57, 128, 0.09);
    }

    .article-search-btn {
        border-radius: 999px;
        padding: 0.72rem 1.05rem;
        font-size: 0.95rem;
        font-weight: 700;
        box-shadow: 0 10px 20px rgba(230, 57, 128, 0.14);
    }

    .article-search-btn i {
        font-size: 0.95rem;
    }

    /* Article Modal Styles */
    .article-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .article-modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .article-modal-container {
        background: #fff;
        border-radius: 20px;
        max-width: 900px;
        width: 100%;
        max-height: 90vh;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(15, 23, 42, 0.25);
        transform: scale(0.95) translateY(20px);
        transition: transform 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .article-modal-overlay.active .article-modal-container {
        transform: scale(1) translateY(0);
    }

    .article-modal-header {
        background: linear-gradient(135deg, #e63980, #ff6b9d);
        color: #fff;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }

    .article-modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        flex: 1;
        margin-right: 16px;
    }

    .article-modal-close {
        background: rgba(255,255,255,0.2);
        border: none;
        color: #fff;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .article-modal-close:hover {
        background: rgba(255,255,255,0.3);
        transform: scale(1.1);
    }

    .article-modal-body {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
        position: relative;
    }

    .article-modal-image {
        width: 100%;
        max-height: 300px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .article-modal-summary {
        background: linear-gradient(135deg, rgba(230, 57, 128, 0.08), rgba(255, 107, 157, 0.04));
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
        border-left: 4px solid #e63980;
    }

    .article-modal-summary h5 {
        color: #e63980;
        font-size: 0.9rem;
        font-weight: 700;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .article-modal-summary p {
        color: #1a1a1a;
        margin: 0;
        line-height: 1.6;
    }

    .article-modal-content {
        line-height: 1.8;
        color: #1a1a1a;
        font-size: 1rem;
    }

    /* Headings */
    .article-modal-content h1,
    .article-modal-content h2,
    .article-modal-content h3,
    .article-modal-content h4,
    .article-modal-content h5,
    .article-modal-content h6 {
        color: #1a1a1a;
        margin-top: 32px;
        margin-bottom: 16px;
        font-weight: 700;
        line-height: 1.3;
    }

    .article-modal-content h1 { font-size: 1.75rem; }
    .article-modal-content h2 { font-size: 1.5rem; border-bottom: 2px solid rgba(230, 57, 128, 0.2); padding-bottom: 10px; }
    .article-modal-content h3 { font-size: 1.25rem; color: #e63980; }
    .article-modal-content h4 { font-size: 1.1rem; }
    .article-modal-content h5 { font-size: 1rem; }
    .article-modal-content h6 { font-size: 0.9rem; color: #666; }

    /* Paragraphs */
    .article-modal-content p {
        margin-bottom: 20px;
        text-align: justify;
        line-height: 1.9;
        color: #333;
    }

    /* First paragraph - no indent, others have text-indent */
    .article-modal-content p:first-of-type {
        font-size: 1.05rem;
        color: #1a1a1a;
    }

    /* Lists */
    .article-modal-content ul,
    .article-modal-content ol {
        margin-bottom: 20px;
        padding-left: 28px;
    }

    .article-modal-content ul { list-style-type: disc; }
    .article-modal-content ol { list-style-type: decimal; }
    .article-modal-content li {
        margin-bottom: 10px;
        line-height: 1.7;
        padding-left: 8px;
    }

    .article-modal-content li::marker {
        color: #e63980;
    }

    /* Nested lists */
    .article-modal-content ul ul,
    .article-modal-content ol ol,
    .article-modal-content ul ol,
    .article-modal-content ol ul {
        margin-top: 8px;
        margin-bottom: 8px;
    }

    /* Images */
    .article-modal-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 24px auto;
        display: block;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* Links */
    .article-modal-content a {
        color: #e63980;
        text-decoration: none;
        border-bottom: 1px dashed #e63980;
        transition: all 0.2s ease;
    }

    .article-modal-content a:hover {
        text-decoration: none;
        border-bottom: 1px solid #e63980;
        background: rgba(230, 57, 128, 0.08);
        padding: 2px 4px;
        border-radius: 4px;
    }

    /* Blockquote */
    .article-modal-content blockquote {
        background: linear-gradient(135deg, rgba(230, 57, 128, 0.05), rgba(107, 66, 193, 0.05));
        border-left: 5px solid #e63980;
        padding: 20px 24px;
        margin: 24px 0;
        border-radius: 0 12px 12px 0;
        font-style: italic;
        color: #555;
    }

    .article-modal-content blockquote p {
        margin-bottom: 0;
        text-align: left;
    }

    /* Tables */
    .article-modal-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 24px 0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .article-modal-content th,
    .article-modal-content td {
        padding: 14px 18px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .article-modal-content th {
        background: linear-gradient(135deg, #e63980, #ff6b9d);
        color: #fff;
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .article-modal-content tr:nth-child(even) {
        background: rgba(230, 57, 128, 0.03);
    }

    .article-modal-content tr:hover {
        background: rgba(230, 57, 128, 0.06);
    }

    /* Code blocks */
    .article-modal-content pre {
        background: #2d3748;
        color: #e2e8f0;
        padding: 20px;
        border-radius: 12px;
        overflow-x: auto;
        margin: 24px 0;
        font-family: 'Fira Code', 'Courier New', monospace;
        font-size: 0.9rem;
        line-height: 1.6;
    }

    .article-modal-content code {
        background: rgba(230, 57, 128, 0.1);
        color: #e63980;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.9em;
        font-family: 'Fira Code', 'Courier New', monospace;
    }

    .article-modal-content pre code {
        background: transparent;
        color: inherit;
        padding: 0;
        font-size: inherit;
    }

    /* Horizontal rule */
    .article-modal-content hr {
        border: none;
        height: 2px;
        background: linear-gradient(135deg, rgba(230, 57, 128, 0.3), rgba(107, 66, 193, 0.3));
        margin: 32px 0;
        border-radius: 2px;
    }

    /* Strong & emphasis */
    .article-modal-content strong,
    .article-modal-content b {
        font-weight: 700;
        color: #1a1a1a;
    }

    .article-modal-content em,
    .article-modal-content i {
        font-style: italic;
    }

    /* Figures and captions */
    .article-modal-content figure {
        margin: 24px 0;
    }

    .article-modal-content figcaption {
        text-align: center;
        font-size: 0.85rem;
        color: #888;
        margin-top: 10px;
        font-style: italic;
    }

    /* Highlight text */
    .article-modal-content mark {
        background: rgba(255, 235, 59, 0.4);
        padding: 2px 6px;
        border-radius: 4px;
    }

    /* Abbreviations */
    .article-modal-content abbr[title] {
        border-bottom: 1px dotted #888;
        cursor: help;
    }

    /* First element should not have top margin */
    .article-modal-content > *:first-child {
        margin-top: 0;
    }

    /* Last element should not have bottom margin */
    .article-modal-content > *:last-child {
        margin-bottom: 0;
    }

    .article-modal-loading {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        text-align: center;
    }

    .article-modal-loading .spinner {
        width: 50px;
        height: 50px;
        border: 4px solid rgba(230, 57, 128, 0.2);
        border-top-color: #e63980;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 16px;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .article-modal-loading p {
        color: #888;
        font-size: 0.95rem;
    }

    .article-modal-error {
        text-align: center;
        padding: 60px 20px;
    }

    .article-modal-error .icon {
        font-size: 64px;
        margin-bottom: 16px;
    }

    .article-modal-error h4 {
        color: #e63980;
        margin-bottom: 8px;
    }

    .article-modal-error p {
        color: #888;
        margin-bottom: 20px;
    }

    .article-modal-footer {
        background: #fafafa;
        padding: 16px 24px;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }

    .article-modal-meta {
        font-size: 0.8rem;
        color: #888;
    }

    .article-modal-refresh {
        background: linear-gradient(135deg, #6f42c1, #a855f7);
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 999px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .article-modal-refresh:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(111, 66, 193, 0.3);
    }
</style>
@endpush

@section('content')
    @php
        $categoryLabels = [
            'umum' => 'Umum',
            'trimester_1' => 'Trimester 1',
            'trimester_2' => 'Trimester 2',
            'trimester_3' => 'Trimester 3',
        ];

        $trimesterLabel = match ($selectedTrimester ?? null) {
            'trimester_1' => 'Trimester 1',
            'trimester_2' => 'Trimester 2',
            'trimester_3' => 'Trimester 3',
            default => null,
        };
    @endphp

    <div class="pengguna-dashboard-shell">
        <div class="article-panel-soft mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-lg-9 col-md-8">
                    <label for="articleSearch" class="form-label fw-semibold">Cari Artikel</label>
                    <input type="search" class="form-control form-control-lg" id="articleSearch" placeholder="Cari judul, ringkasan, atau kategori...">
                </div>
                <div class="col-lg-3 col-md-4 d-grid">
                    <button type="button" class="btn btn-primary-custom article-search-btn" id="applySearchFilter">
                        <i class="bi bi-search me-2"></i>
                        <span>Cari</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="article-panel mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                <div>
                    <h5 class="fw-bold mb-1">Rekomendasi Berdasarkan Trimester</h5>
                    @if($trimesterLabel)
                        <span class="text-muted small">Disesuaikan untuk {{ $trimesterLabel }} berdasarkan usia kehamilan pengguna.</span>
                    @else
                        <span class="text-muted small">Data usia kehamilan belum tersedia di profil pengguna.</span>
                    @endif
                </div>
                <span class="text-muted small">{{ $recommendedArticles->count() }} artikel rekomendasi</span>
            </div>

            @if($trimesterLabel && $recommendedArticles->isNotEmpty())
                <div class="recommended-strip">
                    @foreach($recommendedArticles->take(6) as $recommendedArticle)
                        @php
                            $categoryLabel = $categoryLabels[$recommendedArticle->category] ?? ucfirst(str_replace('_', ' ', $recommendedArticle->category));
                        @endphp
                        <a href="#artikel-{{ $recommendedArticle->id }}" class="recommended-pill">
                            <i class="bi bi-stars"></i>
                            <span>{{ $recommendedArticle->title }} - {{ $categoryLabel }}</span>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0">Rekomendasi akan muncul setelah usia kehamilan pengguna tersedia di profil.</p>
            @endif
        </div>

        <div class="article-panel" id="daftar-artikel">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-0">Daftar Artikel Edukasi</h5>
                    <span class="text-muted small">Sumber data: artikel yang diupload admin pada Manajemen Artikel.</span>
                </div>
                <span class="text-muted small">{{ $articles->count() }} artikel tersedia</span>
            </div>

            @if($articles->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-journal-x fs-1 text-muted d-block mb-2"></i>
                    <p class="text-muted mb-0">Belum ada artikel edukasi yang dipublikasikan admin.</p>
                </div>
            @else
                <div class="article-grid" id="articleGrid">
                    @foreach($articles as $article)
                        @php
                            $categoryLabel = $categoryLabels[$article->category] ?? ucfirst(str_replace('_', ' ', $article->category));
                            $weekLabel = $article->min_week && $article->max_week
                                ? $article->min_week . '-' . $article->max_week . ' minggu'
                                : 'Umum';
                            // Accept https/http, protocol-relative (//cdn...), absolute (/storage/...) or relative paths
                            $imageUrl = asset('foto/artikel.jpg');
                            if (!empty($article->image_url)) {
                                $img = $article->image_url;
                                if (preg_match('/^(?:https?:)?\\/\\//', $img)) {
                                    // http(s):// or //cdn...
                                    $imageUrl = $img;
                                } elseif (str_starts_with($img, '/')) {
                                    // absolute path on same host
                                    $imageUrl = url($img);
                                } else {
                                    // treat as project-relative asset path
                                    $imageUrl = asset($img);
                                }
                            }
                            $searchText = strtolower($article->title . ' ' . $article->summary . ' ' . $categoryLabel . ' ' . $weekLabel);
                        @endphp
                        <article class="article-card" id="artikel-{{ $article->id }}" data-article-card data-search="{{ $searchText }}">
                            <img src="{{ $imageUrl }}" alt="{{ $article->title }}" class="article-card__image" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('foto/artikel.jpg') }}';">
                            <div class="article-card__body">
                                <div class="article-meta">
                                    <span class="chip-soft">{{ $categoryLabel }}</span>
                                    <span class="chip-soft" style="background: rgba(15, 23, 42, 0.06); color: #334155;">{{ $weekLabel }}</span>
                                </div>
                                <h5 class="fw-bold mb-2">{{ $article->title }}</h5>
                                <p class="text-muted mb-3">{{ \Illuminate\Support\Str::limit($article->summary, 130) }}</p>
                                <div class="d-flex gap-2 flex-wrap">
                                    @if($article->article_url)
                                        <button type="button" class="btn btn-primary-custom btn-sm" onclick="openArticleModal({{ $article->id }})">
                                            <i class="bi bi-book-open me-1"></i>
                                            Baca Artikel
                                        </button>
                                    @else
                                        <span class="text-muted small align-self-center">Tautan artikel belum ditambahkan admin.</span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                @php
                    // No per-article detail modal: users read via external link or summary.
                @endphp
            @endif

        </div>
    </div>

    {{-- Article Modal for Web Scraping Content --}}
    <div class="article-modal-overlay" id="articleModalOverlay" onclick="closeArticleModalOnOverlay(event)">
        <div class="article-modal-container" onclick="event.stopPropagation()">
            <div class="article-modal-header">
                <h3 id="articleModalTitle">Memuat artikel...</h3>
                <button class="article-modal-close" onclick="closeArticleModal()" title="Tutup">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="article-modal-body" id="articleModalBody">
                {{-- Content will be loaded here dynamically --}}
            </div>
            <div class="article-modal-footer">
                <div class="article-modal-meta" id="articleModalMeta"></div>
                <button class="article-modal-refresh" onclick="refreshArticleContent()" title="Segarkan konten">
                    <i class="bi bi-arrow-clockwise"></i>
                    <span>Segarkan</span>
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let currentArticleId = null;

    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('articleSearch');
        const applySearchFilterButton = document.getElementById('applySearchFilter');
        const cards = Array.from(document.querySelectorAll('[data-article-card]'));

        function applySearchFilter() {
            const query = (searchInput?.value || '').trim().toLowerCase();

            cards.forEach(function (card) {
                const haystack = (card.getAttribute('data-search') || '').toLowerCase();
                const isVisible = query === '' || haystack.includes(query);
                card.classList.toggle('d-none', !isVisible);
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', applySearchFilter);
        }

        if (applySearchFilterButton) {
            applySearchFilterButton.addEventListener('click', applySearchFilter);
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeArticleModal();
            }
        });
    });

    /**
     * Open article in modal using web scraping
     */
    function openArticleModal(articleId) {
        currentArticleId = articleId;
        const overlay = document.getElementById('articleModalOverlay');
        const body = document.getElementById('articleModalBody');
        const title = document.getElementById('articleModalTitle');

        if (!overlay || !body) return;

        // Show modal with loading state
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';

        title.textContent = 'Memuat artikel...';
        body.innerHTML = `
            <div class="article-modal-loading">
                <div class="spinner"></div>
                <p>Mengambil konten artikel...</p>
            </div>
        `;

        // Fetch article content via web scraping
        console.log('[Artikel] Fetching from:', `/pengguna/artikel/${articleId}/content`);

        fetch(`/pengguna/artikel/${articleId}/content`)
            .then(response => {
                console.log('[Artikel] Response status:', response.status);
                console.log('[Artikel] Response headers:', response.headers.get('content-type'));
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('[Artikel] Response data:', data);
                if (data.success) {
                    displayArticleContent(data);
                } else {
                    showArticleError(data.message || 'Gagal memuat artikel');
                }
            })
            .catch(error => {
                console.error('[Artikel] Error loading article:', error);
                showArticleError('Koneksi gagal: ' + error.message + '. Silakan coba lagi.');
            });
    }

    /**
     * Display article content in modal
     */
    function displayArticleContent(data) {
        const title = document.getElementById('articleModalTitle');
        const body = document.getElementById('articleModalBody');
        const meta = document.getElementById('articleModalMeta');

        title.textContent = data.title || 'Artikel';

        let html = '';

        // Main image if available
        if (data.content && data.content.main_image) {
            html += `<img src="${data.content.main_image}" alt="${escapeHtml(data.title)}" class="article-modal-image" onerror="this.style.display='none'">`;
        }

        // Summary section
        if (data.summary) {
            html += `
                <div class="article-modal-summary">
                    <h5><i class="bi bi-info-circle me-1"></i>Ringkasan</h5>
                    <p>${escapeHtml(data.summary)}</p>
                </div>
            `;
        }

        // Content section
        if (data.scrapped && data.content && data.content.content) {
            html += `<div class="article-modal-content">${data.content.content}</div>`;
            meta.innerHTML = `<i class="bi bi-check-circle-fill text-success me-1"></i> Konten diambil dari sumber asli`;
        } else {
            // Fallback to summary if scraping failed
            html += `
                <div class="article-modal-error">
                    <div class="icon">📰</div>
                    <h4>Konten Tidak Tersedia</h4>
                    <p>Maaf, konten artikel tidak dapat diambil secara otomatis.<br>Silakan baca ringkasan di bawah atau buka artikel sumber.</p>
                    ${data.summary ? `<p class="mt-3"><strong>Ringkasan:</strong><br>${escapeHtml(data.summary)}</p>` : ''}
                </div>
            `;
            meta.innerHTML = `<i class="bi bi-exclamation-circle text-warning me-1"></i> Konten fallback (ringkasan saja)`;
        }

        body.innerHTML = html;
    }

    /**
     * Show error state in modal
     */
    function showArticleError(message) {
        const body = document.getElementById('articleModalBody');
        const meta = document.getElementById('articleModalMeta');

        body.innerHTML = `
            <div class="article-modal-error">
                <div class="icon">😔</div>
                <h4>Gagal Memuat Artikel</h4>
                <p>${escapeHtml(message)}</p>
            </div>
        `;
        meta.innerHTML = '';
    }

    /**
     * Close article modal
     */
    function closeArticleModal() {
        const overlay = document.getElementById('articleModalOverlay');
        if (overlay) {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
        currentArticleId = null;
    }

    /**
     * Close modal when clicking overlay (not modal container)
     */
    function closeArticleModalOnOverlay(event) {
        if (event.target === event.currentTarget) {
            closeArticleModal();
        }
    }

    /**
     * Refresh article content (clear cache and reload)
     */
    function refreshArticleContent() {
        if (!currentArticleId) return;

        const body = document.getElementById('articleModalBody');
        const title = document.getElementById('articleModalTitle');

        // Show loading state
        title.textContent = 'Memperbarui konten...';
        body.innerHTML = `
            <div class="article-modal-loading">
                <div class="spinner"></div>
                <p>Memuat ulang konten artikel...</p>
            </div>
        `;

        // Refresh via API
        fetch(`/pengguna/artikel/${currentArticleId}/refresh`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(() => {
            // Reload content after cache refresh
            return fetch(`/pengguna/artikel/${currentArticleId}/content`);
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayArticleContent(data);
            } else {
                showArticleError(data.message || 'Gagal memuat ulang artikel');
            }
        })
        .catch(error => {
            console.error('Error refreshing article:', error);
            showArticleError('Gagal memperbarui konten.');
        });
    }

    /**
     * Escape HTML to prevent XSS
     */
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
@endpush
