{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($states as $state)
    <url>
        <loc>{{ url('/rent/' . $state->slug) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    @foreach($cities as $city)
    @php $stateSlug = $city->state_slug ?: ($city->state ? $city->state->slug : 'india'); @endphp
    <url>
        <loc>{{ url('/rent/' . $stateSlug . '/' . $city->slug) }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/locations/' . $stateSlug . '/' . $city->slug) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    @foreach($localities as $loc)
    @php
        $cSlug = $loc->city?->slug ?: 'city';
        $sSlug = $loc->city?->state_slug ?: ($loc->state?->slug ?: 'india');
    @endphp
    <url>
        <loc>{{ url('/rent/' . $sSlug . '/' . $cSlug . '/' . $loc->slug) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
</urlset>
