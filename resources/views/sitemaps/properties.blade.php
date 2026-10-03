{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($properties as $property)
    <url>
        <loc>{{ url('/properties/' . $property->slug) }}</loc>
        <lastmod>{{ $property->updated_at ? $property->updated_at->toIso8601String() : now()->toIso8601String() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
</urlset>
