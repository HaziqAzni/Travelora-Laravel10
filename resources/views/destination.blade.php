@extends('layouts.app')
@section('title', $dest->name . ', ' . $dest->country . ' - Travelora')

@section('content')
<section class="detail-hero" style="background-image:url('/{{ $dest->image }}')">
    <div class="container">
        <div class="breadcrumb">
            <a href="/">{{ t('nav.home', 'Home') }}</a><span class="sep">/</span>
            <a href="/things-to-do">{{ t('nav.things', 'Things to Do') }}</a><span class="sep">/</span> {{ $dest->name }}
        </div>
        <p class="loc"><i class="fa-solid fa-location-dot"></i> {{ $dest->name }}, {{ $dest->country }}</p>
        <h1>{{ $dest->tagline }}</h1>
        <div class="stats">
            <span><i class="fa-solid fa-star"></i> {{ number_format((float) $dest->rating, 1) }} {{ t('dest.rating', 'rating') }}</span>
            <span><i class="fa-solid fa-hotel"></i> {{ $hotels->count() }} {{ t('dest.hotels', 'hotels') }}</span>
            <span><i class="fa-solid fa-ticket"></i> {{ $things->count() }} {{ t('dest.experiences', 'experiences') }}</span>
            <span><i class="fa-solid fa-tag"></i> {{ t('card.from', 'From') }} {{ money($dest->price_from) }}</span>
            @if($weather)
                <span><i class="fa-solid {{ weather_meta($weather['code'])[1] }}"></i> {{ (int) round($weather['temp']) }}&deg;C {{ t('misc.now', 'now') }}</span>
            @endif
        </div>
    </div>
</section>

<div class="container">
    <p class="detail-desc">
        {{ $wiki['extract'] ?? $dest->description }}
        @if(!empty($wiki['url']))
            <a href="{{ $wiki['url'] }}" target="_blank" rel="noopener" class="wiki-link">{{ t('dest.readmore', 'Read more on Wikipedia') }} <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
        @endif
    </p>

    <!-- ============ Live data: weather + country facts ============ -->
    <div class="live-grid">
        @if($weather)
            <div class="live-card">
                <h3><i class="fa-solid fa-cloud-sun"></i> {{ t('dest.weather', 'Live Weather') }}
                    <span class="live-badge">Open-Meteo</span>
                </h3>
                <div class="weather-now">
                    <div class="weather-icon"><i class="fa-solid {{ weather_meta($weather['code'])[1] }}"></i></div>
                    <div>
                        <div class="weather-temp">{{ (int) round($weather['temp']) }}&deg;C</div>
                        <div class="weather-desc">{{ weather_meta($weather['code'])[0] }} &middot; {{ t('weather.feels', 'Feels like') }} {{ (int) round($weather['feels']) }}&deg;C</div>
                        <div class="weather-meta">{{ t('weather.humidity', 'Humidity') }} {{ (int) $weather['humidity'] }}% &middot; {{ t('weather.wind', 'Wind') }} {{ (int) round($weather['wind']) }} km/h</div>
                    </div>
                </div>
                <div class="forecast-row">
                    @foreach($weather['daily'] as $day)
                        <div class="forecast-day">
                            <span class="fc-day">{{ date('D', strtotime($day['date'])) }}</span>
                            <i class="fa-solid {{ weather_meta($day['code'])[1] }}"></i>
                            <span class="fc-temp">{{ (int) round($day['max']) }}&deg; <small>{{ (int) round($day['min']) }}&deg;</small></span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($country)
            <div class="live-card">
                <h3><i class="fa-solid fa-earth-asia"></i> {{ $country['name'] }} {{ t('dest.glance', 'at a Glance') }}
                    <span class="live-badge">Wikidata</span>
                </h3>
                <ul class="fact-list">
                    @if($country['capital'])<li><i class="fa-solid fa-landmark-flag"></i> {{ t('dest.capital', 'Capital') }} <strong>{{ $country['capital'] }}</strong></li>@endif
                    @if($country['region'])<li><i class="fa-solid fa-map"></i> {{ t('dest.region', 'Region') }} <strong>{{ $country['subregion'] ?: $country['region'] }}</strong></li>@endif
                    @if($country['currency'])<li><i class="fa-solid fa-coins"></i> {{ t('dest.currency', 'Currency') }} <strong>{{ $country['currency'] }}</strong></li>@endif
                    @if($country['languages'])<li><i class="fa-solid fa-language"></i> {{ t('dest.languages', 'Languages') }} <strong>{{ $country['languages'] }}</strong></li>@endif
                    @if($country['population'])<li><i class="fa-solid fa-users"></i> {{ t('dest.population', 'Population') }} <strong>{{ number_format($country['population']) }}</strong></li>@endif
                </ul>
            </div>
        @endif
    </div>

    @if($packs->isNotEmpty())
        <section class="detail-section">
            <h2>{{ t('dest.packages_in', 'Holiday Packages in') }} {{ $dest->name }}</h2>
            <div class="cards-grid">
                @foreach($packs as $p)
                    <article class="card">
                        <div class="card-media">
                            <img src="/{{ $p->image }}" alt="{{ $p->title }}">
                            @if(!empty($p->badge))<span class="card-badge">{{ $p->badge }}</span>@endif
                            <span class="card-price-tag">{{ money($p->price) }} <small>{{ t('card.person', '/ person') }}</small></span>
                        </div>
                        <div class="card-body">
                            <h3>{{ $p->title }}</h3>
                            <p class="card-location"><i class="fa-regular fa-clock"></i> {{ (int) $p->days }} {{ t('card.days', 'days') }} / {{ (int) $p->nights }} {{ t('card.nights', 'nights') }} &middot; <i class="fa-solid fa-star" style="color:#f59e0b"></i> {{ number_format((float) $p->rating, 1) }}</p>
                            <div class="card-footer">
                                <div class="price">{{ money($p->price) }}</div>
                                <button type="button" class="btn btn-primary btn-sm" data-book="package"
                                        data-id="{{ $p->id }}" data-name="{{ $p->title }}"
                                        data-price="{{ (float) $p->price }}" data-needs-end="0"
                                        data-travel="{{ t('modal.travel_date', 'Travel Date') }}" data-unit="{{ t('card.person', '/ person') }}">{{ t('card.book', 'Book Now') }}</button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <section class="detail-section">
        <h2>{{ t('dest.hotels_in', 'Hotels in') }} {{ $dest->name }}</h2>
        @if($hotels->isEmpty())
            <div class="empty"><i class="fa-solid fa-hotel"></i><h3>{{ t('dest.no_hotels.t', 'No hotels listed yet') }}</h3><p>{{ t('dest.no_hotels.d', 'Check back soon.') }}</p></div>
        @else
            <div class="cards-grid">
                @foreach($hotels as $h)
                    <article class="card">
                        <div class="card-media">
                            <img src="/{{ $h->image }}" alt="{{ $h->name }}">
                            <span class="card-price-tag">{{ money($h->price_per_night) }} <small>{{ t('card.night', '/ night') }}</small></span>
                        </div>
                        <div class="card-body">
                            <div class="card-top">
                                <h3>{{ $h->name }}</h3>
                                <span class="card-rating"><i class="fa-solid fa-star"></i> {{ number_format((float) $h->rating, 1) }}</span>
                            </div>
                            <p class="card-location"><i class="fa-solid fa-location-dot"></i> {{ $h->address }}</p>
                            <div class="card-meta">
                                @foreach(array_slice(explode(',', (string) $h->amenities), 0, 3) as $amenity)
                                    <span class="chip">{{ trim($amenity) }}</span>
                                @endforeach
                            </div>
                            @if($h->price_is_estimate)
                                <p class="estimate-note" title="{{ $h->data_note }}"><i class="fa-solid fa-circle-info"></i> {{ t('hotels.estimated', 'Sample price, rating & review count (estimated)') }}</p>
                            @endif
                            <div class="card-footer">
                                <span class="stars">{!! stars((int) $h->stars) !!}</span>
                                <button type="button" class="btn btn-primary btn-sm" data-book="hotel"
                                        data-id="{{ $h->id }}" data-name="{{ $h->name }}"
                                        data-price="{{ (float) $h->price_per_night }}" data-needs-end="1"
                                        data-travel="{{ t('modal.checkin', 'Check-in Date') }}" data-unit="{{ t('card.night', '/ night') }}">{{ t('card.book', 'Book Now') }}</button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <section class="detail-section">
        <h2>{{ t('dest.things_in', 'Things to Do in') }} {{ $dest->name }}</h2>
        @if($things->isEmpty())
            <div class="empty"><i class="fa-solid fa-ticket"></i><h3>{{ t('dest.no_things.t', 'No experiences listed yet') }}</h3><p>{{ t('dest.no_things.d', 'Check back soon.') }}</p></div>
        @else
            <div class="cards-grid cols-4">
                @foreach($things as $thing)
                    <article class="card">
                        <div class="card-media">
                            <img src="/{{ $thing->image }}" alt="{{ $thing->title }}">
                            <span class="card-badge">{{ $thing->category }}</span>
                        </div>
                        <div class="card-body">
                            <h3 style="font-size:15.5px">{{ $thing->title }}</h3>
                            <p class="card-location" style="margin-bottom:10px"><i class="fa-regular fa-clock"></i> {{ $thing->duration }} &middot; <i class="fa-solid fa-star" style="color:#f59e0b"></i> {{ number_format((float) $thing->rating, 1) }}</p>
                            <div class="card-footer">
                                <div class="price" style="font-size:17px">{{ money($thing->price) }} <small>{{ t('card.person', '/ person') }}</small></div>
                                <button type="button" class="btn btn-primary btn-sm" data-book="activity"
                                        data-id="{{ $thing->id }}" data-name="{{ $thing->title }}"
                                        data-price="{{ (float) $thing->price }}" data-needs-end="0"
                                        data-travel="{{ t('modal.activity_date', 'Activity Date') }}" data-unit="{{ t('card.person', '/ person') }}">{{ t('card.book', 'Book') }}</button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <!-- ============ Live hotels from OpenStreetMap ============ -->
    <section class="detail-section" id="live" style="padding-bottom:60px">
        <div class="section-head-row" style="margin-bottom:8px">
            <div>
                <h2>{{ t('live.hotels_title', 'More Hotels near') }} {{ $dest->name }}
                    <span class="live-badge">{{ t('live.live_word', 'Live') }} &middot; OpenStreetMap</span>
                </h2>
                <p class="section-sub">{{ sprintf(t('live.hotels_sub', 'Real hotels around %s, fetched live from the OpenStreetMap Overpass API.'), $dest->name) }}</p>
            </div>
            <form action="/sync-hotels" method="post">
                @csrf
                <input type="hidden" name="destination_id" value="{{ $dest->id }}">
                <button type="submit" class="btn btn-outline btn-sm" title="{{ t('live.import', 'Import to Database') }}">
                    <i class="fa-solid fa-download"></i> {{ t('live.import', 'Import to Database') }}
                </button>
            </form>
        </div>

        <div class="live-hotels" data-live-hotels data-destination-id="{{ $dest->id }}">
            <div class="live-loading"><i class="fa-solid fa-spinner fa-spin"></i> {{ t('live.loading_hotels', 'Loading live hotels from OpenStreetMap…') }}</div>
        </div>
        <p class="attribution"><i class="fa-solid fa-copyright"></i> OpenStreetMap contributors (ODbL) &middot; via Overpass API</p>
    </section>
</div>
@endsection
