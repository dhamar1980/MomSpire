<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArtikelEdukasi;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ArticleScraperController extends Controller
{
    /**
     * Menampilkan konten artikel dalam modal menggunakan web scraping
     */
    public function show($id)
    {
        $article = ArtikelEdukasi::findOrFail($id);
        $url = $article->article_url;

        if (empty($url)) {
            return response()->json([
                'success' => false,
                'message' => 'URL artikel tidak tersedia',
            ], 404);
        }

        // Gunakan cache untuk menghindari scraping berulang (1 jam)
        $cacheKey = 'article_content_' . $id;
        $content = Cache::get($cacheKey);

        if (!$content) {
            try {
                $content = $this->scrapeArticle($url);

                if ($content) {
                    // Simpan ke cache selama 1 jam
                    Cache::put($cacheKey, $content, 3600);
                }
            } catch (\Exception $e) {
                // Jika scraping gagal, kirim data dasar
                $content = null;
                \Log::error('Article scrape failed', [
                    'article_id' => $id,
                    'url' => $url,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'title' => $article->title,
            'category' => $article->category,
            'summary' => $article->summary,
            'content' => $content,
            'scrapped' => $content !== null,
        ]);
    }

    /**
     * Refresh cache konten artikel
     */
    public function refresh($id)
    {
        $article = ArtikelEdukasi::findOrFail($id);
        $url = $article->article_url;

        if (empty($url)) {
            return response()->json([
                'success' => false,
                'message' => 'URL artikel tidak tersedia',
            ], 404);
        }

        // Hapus cache lama
        Cache::forget('article_content_' . $id);

        // Scrape ulang
        try {
            $content = $this->scrapeArticle($url);

            if ($content) {
                Cache::put('article_content_' . $id, $content, 3600);
            }

            return response()->json([
                'success' => true,
                'message' => $content ? 'Cache berhasil diperbarui' : 'Gagal mengambil konten',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Fungsi utama untuk scraping artikel dari URL
     */
    private function scrapeArticle($url)
    {
        try {
            // Set timeout dan user agent
            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
                ])
                ->get($url);

            if (!$response->successful()) {
                throw new \Exception('HTTP request failed: ' . $response->status());
            }

            $html = $response->body();

            // Parse HTML dengan DomCrawler
            $crawler = new Crawler($html);

            // Ambil judul artikel
            $title = $this->extractTitle($crawler);

            // Ambil konten utama artikel
            $content = $this->extractContent($crawler);

            // Ambil gambar utama
            $mainImage = $this->extractMainImage($crawler, $url);

            return [
                'title' => $title,
                'content' => $content,
                'main_image' => $mainImage,
                'scraped_at' => now()->toIso8601String(),
            ];
        } catch (\Exception $e) {
            \Log::error('Scraping error', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Ekstrak judul artikel
     */
    private function extractTitle(Crawler $crawler)
    {
        // Coba berbagai selector untuk judul
        $selectors = [
            'article h1',
            '.article-title',
            '.post-title',
            '.entry-title',
            'h1.title',
            '.story-body h1',
            'h1',
            '[itemprop="headline"]',
        ];

        foreach ($selectors as $selector) {
            $title = $crawler->filter($selector)->first();
            if ($title->count() > 0) {
                $text = trim($title->text());
                if (!empty($text)) {
                    return $text;
                }
            }
        }

        // Fallback ke tag title HTML
        return $crawler->filter('title')->first()->text() ?? '';
    }

    /**
     * Ekstrak konten utama artikel
     */
    private function extractContent(Crawler $crawler)
    {
        // Hapus elemen yang tidak diperlukan SEBELUM extract
        $removeSelectors = [
            'script', 'style', 'noscript', 'iframe', 'svg',
            'nav', 'header', 'footer', 'aside',
            '.sidebar', '.ads', '.advertisement', '.social-share', '.comments',
            '.related-posts', '.newsletter', '.popup', '.modal', '.banner',
            '.promo', '.placeholder', '.skeleton',
            '[class*="sidebar"]', '[class*="nav"]', '[class*="menu"]',
            '[class*="advertisement"]', '[class*="ads"]', '[class*="promo"]',
            '[class*="social"]', '[class*="share"]', '[class*="search"]',
            '[data-testid*="ad"]', '[data-testid*="promo"]',
        ];

        foreach ($removeSelectors as $selector) {
            try {
                $crawler->filter($selector)->each(function (Crawler $node) {
                    try {
                        $node->getNode(0)->parentNode->removeChild($node->getNode(0));
                    } catch (\Exception $e) {
                        // Ignore
                    }
                });
            } catch (\Exception $e) {
                // Ignore invalid selectors
            }
        }

        // Selector untuk konten utama - prioritaskan yang lebih spesifik
        $contentSelectors = [
            // Indonesian health sites (hellosehat, halodoc, klikdokter)
            '.unique-content-wrapper',
            '.article-content-wrapper',
            '.article-body-content',
            '.article-body',
            '.article-content',
            '.daftarcari',
            '.table-of-contents',
            '.content-article',
            // WordPress
            'article .entry-content',
            'article .post-content',
            'article .article-content',
            'article .content',
            '.post-body .entry-content',
            '.post-body .post-content',
            '.entry-content',
            '.post-content',
            // Berita Indonesia
            '.story-body',
            '.story-content',
            '.isi-berita',
            '.berita-content',
            // Content editors
            '[itemprop="articleBody"]',
            '.editor-content',
            '.rich-text-content',
            // Generic
            'article',
            '.article',
            '.post',
            'main article',
            'main .content',
            'main',
            '.content',
        ];

        foreach ($contentSelectors as $selector) {
            try {
                $content = $crawler->filter($selector)->first();
                if ($content->count() > 0) {
                    $html = trim($content->html());
                    // Minimal panjang konten 200 karakter
                    if (strlen(strip_tags($html)) > 200) {
                        return $this->cleanAndFormatHtml($html);
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // Fallback: cari paragraf dan heading yang relevan (bukan navigasi/search/UI)
        $allElements = $crawler->filter('body *');
        if ($allElements->count() > 0) {
            $content = [];
            $skipElements = ['script', 'style', 'nav', 'header', 'footer', 'aside', 'form', 'noscript'];

            foreach ($allElements as $element) {
                $tagName = strtolower($element->tagName);

                // Skip non-content elements
                if (in_array($tagName, $skipElements)) {
                    continue;
                }

                $nodeCrawler = new Crawler($element);
                $text = trim($nodeCrawler->text());
                $html = trim($nodeCrawler->html());

                // Skip elements with little text
                if (strlen(preg_replace('/\s+/', '', $text)) < 10) {
                    continue;
                }

                // Skip UI/navigation elements
                $classId = strtolower(($element->getAttribute('class') ?? '') . ' ' . ($element->getAttribute('id') ?? ''));
                $skipPatterns = ['nav', 'menu', 'search', 'sidebar', 'header', 'aside',
                               'advertisement', 'ads', 'promo', 'banner', 'social', 'share',
                               'comment', 'related', 'popular', 'trending', 'placeholder', 'skeleton',
                               'dropdown', 'modal', 'popup', 'overlay', 'drawer', 'toolbar', 'tabbar'];

                $isUIElement = false;
                foreach ($skipPatterns as $pattern) {
                    if (strpos($classId, $pattern) !== false) {
                        $isUIElement = true;
                        break;
                    }
                }
                if ($isUIElement) {
                    continue;
                }

                // Extract content based on tag type
                if (in_array($tagName, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'])) {
                    $content[] = '<' . $tagName . '>' . $this->escapeHtml($text) . '</' . $tagName . '>';
                } elseif ($tagName === 'p' && strlen(strip_tags($html)) > 30) {
                    $content[] = '<p>' . $html . '</p>';
                } elseif (in_array($tagName, ['ul', 'ol']) && strlen(strip_tags($html)) > 50) {
                    $content[] = $html;
                }
            }

            $result = implode("\n", $content);
            if (!empty($result) && strlen(strip_tags($result)) > 200) {
                return $this->cleanAndFormatHtml($result);
            }
        }

        return null;
    }

    /**
     * Ekstrak gambar utama artikel
     */
    private function extractMainImage(Crawler $crawler, $baseUrl)
    {
        $selectors = [
            'article img',
            '.article-content img',
            '.post-content img',
            '[itemprop="image"] img',
            'meta[property="og:image"]',
            'meta[name="twitter:image"]',
        ];

        foreach ($selectors as $selector) {
            $element = $crawler->filter($selector)->first();
            if ($element->count() > 0) {
                // Untuk meta tags
                if ($element->matches('meta')) {
                    $content = $element->attr('content');
                    if ($content) {
                        return $this->makeAbsoluteUrl($content, $baseUrl);
                    }
                }
                // Untuk img tags
                $src = $element->attr('src') ?? $element->attr('data-src');
                if ($src) {
                    return $this->makeAbsoluteUrl($src, $baseUrl);
                }
            }
        }

        return null;
    }

    /**
     * Bersihkan dan format HTML untuk tampilan artikel
     */
    private function cleanAndFormatHtml($html)
    {
        try {
            $crawler = new Crawler($html);

            // Atribut yang diizinkan untuk dipertahankan
            $allowedAttrs = ['href', 'src', 'alt', 'title', 'target', 'rel'];

            // Hapus elemen yang tidak berguna dulu
            $crawler->filter('style, script, noscript, iframe, svg')->each(function (Crawler $node) {
                try {
                    $node->getNode(0)->parentNode->removeChild($node->getNode(0));
                } catch (\Exception $e) {
                    // Ignore
                }
            });

            // Bersihkan atribut yang tidak diperlukan dari semua elemen
            $crawler->filter('*')->each(function (Crawler $node) use ($allowedAttrs) {
                try {
                    $element = $node->getNode(0);
                    if ($element && $element->attributes) {
                        $attrsToRemove = [];
                        foreach ($element->attributes as $attr) {
                            if (!in_array($attr->name, $allowedAttrs)) {
                                $attrsToRemove[] = $attr->name;
                            }
                        }
                        foreach ($attrsToRemove as $attrName) {
                            $element->removeAttribute($attrName);
                        }
                    }
                } catch (\Exception $e) {
                    // Ignore
                }
            });

            // Pastikan gambar memiliki URL absolut
            $crawler->filter('img')->each(function (Crawler $node) {
                try {
                    $src = $node->attr('src');
                    if ($src && !str_starts_with($src, 'http')) {
                        // Gambar tidak memiliki URL absolut, hapus src
                        $node->getNode(0)->removeAttribute('src');
                    }
                } catch (\Exception $e) {
                    // Ignore
                }
            });

            // Pastikan link membuka di tab baru (kecuali link internal)
            $crawler->filter('a')->each(function (Crawler $node) {
                try {
                    $href = $node->attr('href');
                    if ($href && str_starts_with($href, 'http')) {
                        $node->attr('target', '_blank');
                        $node->attr('rel', 'noopener noreferrer');
                    }
                } catch (\Exception $e) {
                    // Ignore
                }
            });

            // Hapus elemen UI yang tidak termasuk konten artikel
            $uiSelectors = [
                'nav', 'header', 'footer', 'aside',
                '[class*="sidebar"]', '[class*="nav"]', '[class*="menu"]',
                '[class*="header"]', '[class*="footer"]', '[class*="navbar"]',
                '[class*="advertisement"]', '[class*="ads"]', '[class*="promo"]', '[class*="banner"]',
                '[class*="social"]', '[class*="share"]', '[class*="comment"]',
                '[class*="search"]', '[class*="filter"]',
                '[class*="placeholder"]', '[class*="skeleton"]',
                '[id*="sidebar"]', '[id*="nav"]', '[id*="menu"]',
                '[role="navigation"]', '[role="banner"]', '[role="complementary"]',
                '.ad', '.ads', '.advertisement', '.promo',
                '[data-testid*="ad"]', '[data-testid*="promo"]',
                'input:not([type="hidden"])',
            ];

            foreach ($uiSelectors as $selector) {
                try {
                    $crawler->filter($selector)->each(function (Crawler $node) {
                        try {
                            $node->getNode(0)->parentNode->removeChild($node->getNode(0));
                        } catch (\Exception $e) {
                            // Ignore
                        }
                    });
                } catch (\Exception $e) {
                    // Ignore invalid selectors
                }
            }

            // Hapus elemen dengan teks UI/navigasi yang spesifik
            $crawler->filter('p, div, span, li')->each(function (Crawler $node) {
                try {
                    $text = trim($node->text());
                    $cleanText = preg_replace('/\s+/', '', $text);
                    // Replace non-breaking space with regular space for comparison
                    $cleanText = str_replace(chr(160), ' ', $cleanText);

                    // Skip jika kosong atau kurang dari 3 karakter
                    if (empty($cleanText) || strlen($cleanText) < 3) {
                        $node->getNode(0)->parentNode->removeChild($node->getNode(0));
                        return;
                    }

                    // Skip elemen dengan kata-kata navigasi saja
                    $navOnlyWords = ['Home', 'Kesehatan', 'search', 'close', 'Masuk', 'Beranda',
                                    'Cek', 'Kamus', 'Toko', 'Homecare', 'Asuransiku',
                                    'Haloskin', 'Halofit', 'Layanan', 'Kulit', 'Seksual',
                                    'Mental', 'Hewan', 'Diabetes', 'Jantung', 'Parenting',
                                    'Bidang', 'Stres', 'Risiko', 'Kalender', 'Menstruasi',
                                    'BMI', 'Coba', 'Lihat', 'FAQ', 'TRENDING', 'Trending',
                                    'Perawatan'];

                    // Abaikan jika paragraf hanya terdiri dari nav keywords (1-3 kata)
                    $words = preg_split('/\s+/', $cleanText);
                    if (count($words) <= 3) {
                        $isNav = true;
                        foreach ($words as $word) {
                            if (!in_array($word, $navOnlyWords)) {
                                $isNav = false;
                                break;
                            }
                        }
                        if ($isNav) {
                            $node->getNode(0)->parentNode->removeChild($node->getNode(0));
                            return;
                        }
                    }

                    // Skip jika HANYA attribution (REVIEWED_BY, Diperbarui pada, REFERENSI)
                    // Bukan parent element yang mengandung attribution
                    $ownText = trim($node->text()); // direct text only
                    if (preg_match('/^\s*(REVIEWED_BY|Diperbarui pada|Referensi|Sumber)/i', $ownText)) {
                        $node->getNode(0)->parentNode->removeChild($node->getNode(0));
                        return;
                    }

                    // Skip jika hampir semuanya adalah non-breaking space
                    $pureNbsText = preg_replace('/[^\xC2\xA0]/', '', $cleanText);
                    if (strlen($pureNbsText) > strlen($cleanText) * 0.7) {
                        $node->getNode(0)->parentNode->removeChild($node->getNode(0));
                        return;
                    }
                } catch (\Exception $e) {
                    // Ignore
                }
            });

            return $crawler->html();
        } catch (\Exception $e) {
            return $html;
        }
    }

    /**
     * @deprecated Use cleanAndFormatHtml instead
     * Bersihkan HTML dari tag dan atribut yang tidak diperlukan
     */
    private function cleanHtml($html)
    {
        return $this->cleanAndFormatHtml($html);
    }

    /**
     * Escape HTML entities
     */
    private function escapeHtml($text)
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Konversi URL relatif menjadi absolut
     */
    private function makeAbsoluteUrl($url, $baseUrl)
    {
        if (empty($url)) return null;

        // Jika sudah absolute
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        // Jika protocol-relative (//example.com)
        if (str_starts_with($url, '//')) {
            return 'https:' . $url;
        }

        // Parse base URL
        $parsed = parse_url($baseUrl);
        $scheme = $parsed['scheme'] ?? 'https';
        $host = $parsed['host'] ?? '';
        $basePath = dirname($parsed['path'] ?? '/');

        // Jika URL absolut dari root
        if (str_starts_with($url, '/')) {
            return $scheme . '://' . $host . $url;
        }

        // Jika URL relatif
        return $scheme . '://' . $host . '/' . ltrim($url, '/');
    }
}