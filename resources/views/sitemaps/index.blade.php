{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ url('/sitemaps/properties.xml') }}</loc>
        <lastmod>{{ now()->toIso8601String() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('/sitemaps/locations.xml') }}</loc>
        <lastmod>{{ now()->toIso8601String() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('/sitemaps/categories.xml') }}</loc>
        <lastmod>{{ now()->toIso8601String() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('/sitemaps/services.xml') }}</loc>
        <lastmod>{{ now()->toIso8601String() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('/sitemaps/cms.xml') }}</loc>
        <lastmod>{{ now()->toIso8601String() }}</lastmod>
    </sitemap>
</sitemapindex>
