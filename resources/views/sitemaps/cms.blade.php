{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @php
        $staticUrls = [
            '/' => ['freq' => 'daily', 'prio' => '1.0'],
            '/owners' => ['freq' => 'weekly', 'prio' => '0.8'],
            '/tenants' => ['freq' => 'weekly', 'prio' => '0.8'],
            '/property-managers' => ['freq' => 'weekly', 'prio' => '0.8'],
            '/portal-and-technology' => ['freq' => 'monthly', 'prio' => '0.6'],
            '/about' => ['freq' => 'monthly', 'prio' => '0.6'],
            '/contact' => ['freq' => 'monthly', 'prio' => '0.6'],
            '/pricing' => ['freq' => 'monthly', 'prio' => '0.7'],
            '/resources' => ['freq' => 'weekly', 'prio' => '0.7'],
            '/franchising' => ['freq' => 'monthly', 'prio' => '0.7'],
            '/reviews' => ['freq' => 'weekly', 'prio' => '0.7'],
            '/locations' => ['freq' => 'weekly', 'prio' => '0.8'],
            '/blog' => ['freq' => 'daily', 'prio' => '0.7'],
            '/refer-and-earn' => ['freq' => 'weekly', 'prio' => '0.8'],
            '/refer-and-earn/terms' => ['freq' => 'monthly', 'prio' => '0.5'],
            '/privacy-policy' => ['freq' => 'monthly', 'prio' => '0.5'],
            '/terms' => ['freq' => 'monthly', 'prio' => '0.5'],
        ];
    @endphp

    @foreach($staticUrls as $path => $meta)
        @if(!isset($nonIndexablePaths[$path]))
        <url>
            <loc>{{ url($path) }}</loc>
            <changefreq>{{ $meta['freq'] }}</changefreq>
            <priority>{{ $meta['prio'] }}</priority>
        </url>
        @endif
    @endforeach

    @if(isset($pages))
        @foreach($pages as $page)
            @if(!empty($page->slug) && !isset($staticUrls['/' . $page->slug]) && !isset($nonIndexablePaths['/' . $page->slug]))
            <url>
                <loc>{{ url('/' . $page->slug) }}</loc>
                <lastmod>{{ $page->updated_at ? $page->updated_at->toIso8601String() : now()->toIso8601String() }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.7</priority>
            </url>
            @endif
        @endforeach
    @endif

    @foreach($posts as $post)
    @if(!empty($post->slug))
    <url>
        <loc>{{ url('/blog/' . $post->slug) }}</loc>
        <lastmod>{{ $post->updated_at ? $post->updated_at->toIso8601String() : now()->toIso8601String() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endif
    @endforeach
</urlset>
