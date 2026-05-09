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
                <h5 class="fw-bold mb-0">Daftar Artikel Edukasi</h5>
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
                            $imageUrl = $article->image_url ?: asset('foto/artikel.jpg');
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
                                        <a href="{{ $article->article_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary-custom btn-sm">
                                            Baca Artikel
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
@endsection

@push('scripts')
<script>
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
    });
</script>
@endpush
