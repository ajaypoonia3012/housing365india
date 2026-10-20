{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @if(!isset($nonIndexablePaths['/services']))
    <url>
        <loc>{{ url('/services') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endif

    @if(!isset($nonIndexablePaths['/services/care']))
    <url>
        <loc>{{ url('/services/care') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endif

    @foreach($services as $service)
    <url>
        <loc>{{ url('/services/' . $service->slug) }}</loc>
        <lastmod>{{ $service->updated_at ? $service->updated_at->toIso8601String() : now()->toIso8601String() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    @foreach($cities as $city)
    @php
        $stateSlug = $city->state_slug ?: ($city->province_or_state ? \Illuminate\Support\Str::slug($city->province_or_state) : 'india');
    @endphp
    <url>
        <loc>{{ url('/housing365-care/' . $stateSlug . '/' . $city->slug) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ url('/property-management/' . $stateSlug . '/' . $city->slug) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
</urlset>
