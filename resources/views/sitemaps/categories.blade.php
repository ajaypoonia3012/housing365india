{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($purposes as $purpose)
    <url>
        <loc>{{ url('/' . $purpose) }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach
</urlset>
