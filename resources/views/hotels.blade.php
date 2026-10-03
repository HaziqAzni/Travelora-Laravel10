@extends('layouts.app')
@section('title', 'Hotels - Travelora')

@section('content')
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb"><a href="/">{{ t('nav.home', 'Home') }}</a><span class="sep">/</span> {{ t('nav.hotels', 'Hotels') }}</div>
        <h1>{{ t('page.hotels.title', 'Find Your Perfect Stay') }}</h1>
        <p>{{ t('page.hotels.sub', "Hand-picked hotels and resorts in the world's best destinations.") }}</p>
    </div>
</section>

<div class="container">
    <div class="filter-bar">
        <form class="filter-form" action="/hotels" method="get">
            <div class="input-group">
                <i class="fa-solid fa-location-dot"></i>
                <input type="text" name="q" value="{{ $q }}" placeholder="{{ t('filter.hotel_ph', 'Destination, city or hotel') }}">
            </div>
            <div class="input-group">
                <i class="fa-regular fa-calendar"></i>
                <input type="date" name="check_in" value="{{ $checkIn }}">
            </div>
            <div class="input-group">
                <i class="fa-regular fa-calendar"></i>
                <input type="date" name="check_out" value="{{ $checkOut }}">
            </div>
            <div class="input-group">
                <i class="fa-regular fa-user"></i>
                <select name="guests">
                    @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ $i === $guests ? 'selected' : '' }}>{{ $i }} {{ $i > 1 ? t('filter.guests', 'guests') : t('filter.guest', 'guest') }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> {{ t('filter.search', 'Search') }}</button>
        </form>
    </div>

    <p class="results-meta">
        {{ t('results.showing', 'Showing') }} <strong>{{ $total === 0 ? 0 : $offset + 1 }}&ndash;{{ min($offset + $perPage, $total) }}</strong>
        {{ t('results.of', 'of') }} <strong>{{ $total }}</strong> {{ t('results.hotels', 'hotels') }}
        @if($q !== '') {{ t('results.for', 'for') }} &ldquo;{{ $q }}&rdquo;@endif
        @if($checkIn && $checkOut) &middot; {{ date('d M', strtotime($checkIn)) }} &ndash; {{ date('d M Y', strtotime($checkOut)) }}@endif
        &middot; {{ $guests }} {{ t('filter.guests', 'guests') }}
        @php $osmCount = $hotels->where('source', 'osm')->count(); @endphp
        @if($osmCount) &middot; <i class="fa-solid fa-satellite-dish" style="color:#059669"></i> {{ $osmCount }} {{ t('live.live_from_osm', 'live from OpenStreetMap') }} ({{ t('hotels.sample_prices', 'sample prices') }})@endif
    </p>

    <p class="demo-note"><i class="fa-solid fa-circle-info"></i> {{ t('demo.catalogue', 'Demo catalogue — prices, ratings and reviews are sample data, not live inventory or real offers.') }}</p>

    @if($hotels->isEmpty())
        <div class="empty">
            <i class="fa-solid fa-hotel"></i>
            <h3>{{ t('empty.hotels.t', 'No hotels found') }}</h3>
            <p>{{ t('empty.hotels.d', 'Try a different destination or search term.') }}</p>
        </div>
    @else
        <div class="cards-grid">
            @foreach($hotels as $h)
                <article class="card">
                    <div class="card-media">
                        <img src="/{{ $h->image }}" alt="{{ $h->name }}">
                        @if($h->source === 'osm')
                            <span class="card-badge osm-badge"><i class="fa-solid fa-satellite-dish"></i> {{ t('live.live_word', 'Live') }} &middot; OSM</span>
                        @endif
                        <span class="card-price-tag">{{ money($h->price_per_night) }} <small>{{ t('card.night', '/ night') }}</small></span>
                    </div>
                    <div class="card-body">
                        <div class="card-top">
                            <h3>{{ $h->name }}</h3>
                            <span class="card-rating"><i class="fa-solid fa-star"></i> {{ number_format((float) $h->rating, 1) }}</span>
                        </div>
                        <p class="card-location">
                            <i class="fa-solid fa-location-dot"></i>
                            <a href="/destination/{{ $h->destination_slug }}">{{ $h->destination }}, {{ $h->country }}</a>
                        </p>
                        <div class="card-meta">
                            @foreach(array_slice(explode(',', (string) $h->amenities), 0, 3) as $amenity)
                                <span class="chip">{{ trim($amenity) }}</span>
                            @endforeach
                        </div>
                        @if($h->price_is_estimate)
                            <p class="estimate-note" title="{{ $h->data_note }}"><i class="fa-solid fa-circle-info"></i> {{ t('hotels.estimated', 'Sample price, rating & review count (estimated)') }}</p>
                        @endif
                        <div class="card-footer">
                            <div>
                                <span class="stars">{!! stars((int) $h->stars) !!}</span>
                                <div style="font-size:12px;color:var(--muted)">{{ (int) $h->reviews }} {{ t('card.reviews', 'reviews') }}</div>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" data-book="hotel"
                                    data-id="{{ $h->id }}" data-name="{{ $h->name }}"
                                    data-price="{{ (float) $h->price_per_night }}" data-needs-end="1"
                                    data-travel="{{ t('modal.checkin', 'Check-in Date') }}" data-unit="{{ t('card.night', '/ night') }}">
                                {{ t('card.book', 'Book Now') }}
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif

    @if($pages > 1)
        @php
            $qs = request()->query();
            $link = function (int $p) use ($qs) {
                $qs['page'] = $p;
                return '/hotels?' . http_build_query($qs);
            };
            $windowStart = max(1, min($page - 2, $pages - 4));
            $windowEnd   = min($pages, $windowStart + 4);
        @endphp
        <nav class="pagination" aria-label="Hotel pages">
            <a class="page-btn {{ $page <= 1 ? 'disabled' : '' }}" href="{{ $page <= 1 ? '#' : $link($page - 1) }}"><i class="fa-solid fa-chevron-left"></i></a>
            @if($windowStart > 1)
                <a class="page-btn" href="{{ $link(1) }}">1</a>
                @if($windowStart > 2)<span class="page-dots">&hellip;</span>@endif
            @endif
            @for($p = $windowStart; $p <= $windowEnd; $p++)
                <a class="page-btn {{ $p === $page ? 'active' : '' }}" href="{{ $link($p) }}">{{ $p }}</a>
            @endfor
            @if($windowEnd < $pages)
                @if($windowEnd < $pages - 1)<span class="page-dots">&hellip;</span>@endif
                <a class="page-btn" href="{{ $link($pages) }}">{{ $pages }}</a>
            @endif
            <a class="page-btn {{ $page >= $pages ? 'disabled' : '' }}" href="{{ $page >= $pages ? '#' : $link($page + 1) }}"><i class="fa-solid fa-chevron-right"></i></a>
        </nav>
    @endif
</div>
@endsection
