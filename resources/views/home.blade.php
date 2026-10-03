@extends('layouts.app')
@section('title', 'Travelora - Your Next Adventure Awaits')

@section('content')

<!-- ================= HERO ================= -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-eyebrow">
                {{ t('hero.w1', 'Explore') }} <span class="dot"></span> {{ t('hero.w2', 'Relax') }} <span class="dot"></span> {{ t('hero.w3', 'Discover') }}
            </div>
            <h1>{{ t('hero.title1', 'Your Next') }}<br>{{ t('hero.title2', 'Adventure Awaits') }}</h1>
            <p>{{ t('hero.sub', 'Find the best hotels, flights and travel experiences around the world, all in one place.') }}</p>
        </div>
    </div>
</section>

<!-- ================= SEARCH CARD ================= -->
<div class="search-wrap">
    <div class="container">
        <div class="search-card">
            <div class="search-tabs">
                <button type="button" class="search-tab active" data-target="panel-hotels"><i class="fa-solid fa-bed"></i> {{ t('search.tab_hotels', 'Hotels') }}</button>
                <button type="button" class="search-tab" data-target="panel-flights"><i class="fa-solid fa-plane"></i> {{ t('search.tab_flights', 'Flights') }}</button>
                <button type="button" class="search-tab" data-target="panel-packages"><i class="fa-solid fa-suitcase-rolling"></i> {{ t('search.tab_packages', 'Packages') }}</button>
                <button type="button" class="search-tab" data-target="panel-things"><i class="fa-solid fa-ticket"></i> {{ t('search.tab_things', 'Things to Do') }}</button>
            </div>

            <div class="search-panels">
                <!-- Hotels -->
                <form class="search-panel active" id="panel-hotels" action="/hotels" method="get">
                    <div class="search-fields">
                        <div class="field">
                            <i class="fa-solid fa-location-dot"></i>
                            <div class="field-body">
                                <label for="hotel-q">{{ t('search.where', 'Where are you going?') }}</label>
                                <input type="text" id="hotel-q" name="q" placeholder="{{ t('search.where_ph', 'Search destination, city or hotel') }}">
                            </div>
                        </div>
                        <div class="field">
                            <i class="fa-regular fa-calendar"></i>
                            <div class="field-body">
                                <label for="hotel-in">{{ t('search.checkin', 'Check-in') }}</label>
                                <input type="date" id="hotel-in" name="check_in">
                            </div>
                        </div>
                        <div class="field">
                            <i class="fa-regular fa-calendar"></i>
                            <div class="field-body">
                                <label for="hotel-out">{{ t('search.checkout', 'Check-out') }}</label>
                                <input type="date" id="hotel-out" name="check_out">
                            </div>
                        </div>
                        <div class="field">
                            <i class="fa-regular fa-user"></i>
                            <div class="field-body">
                                <label for="hotel-guests">{{ t('search.guests', 'Guests') }}</label>
                                <select id="hotel-guests" name="guests">
                                    <option value="1">{{ t('gopt.1', '1 adult · 1 room') }}</option>
                                    <option value="2" selected>{{ t('gopt.2', '2 adults · 1 room') }}</option>
                                    <option value="2">{{ t('gopt.3', '2 adults · 2 rooms') }}</option>
                                    <option value="3">{{ t('gopt.4', '3 adults · 1 room') }}</option>
                                    <option value="4">{{ t('gopt.5', '4 adults · 2 rooms') }}</option>
                                </select>
                            </div>
                            <i class="fa-solid fa-chevron-down chev"></i>
                        </div>
                        <div class="search-submit">
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> {{ t('search.search', 'Search') }}</button>
                        </div>
                    </div>
                </form>

                <!-- Flights -->
                <form class="search-panel" id="panel-flights" action="/flights" method="get">
                    <div class="search-fields">
                        <div class="field">
                            <i class="fa-solid fa-plane-departure"></i>
                            <div class="field-body">
                                <label for="fly-from">{{ t('search.from', 'From') }}</label>
                                <input type="text" id="fly-from" name="from" placeholder="{{ t('search.city_airport', 'City or airport') }}">
                            </div>
                        </div>
                        <div class="field">
                            <i class="fa-solid fa-plane-arrival"></i>
                            <div class="field-body">
                                <label for="fly-to">{{ t('search.to', 'To') }}</label>
                                <input type="text" id="fly-to" name="to" placeholder="{{ t('search.city_airport', 'City or airport') }}">
                            </div>
                        </div>
                        <div class="field">
                            <i class="fa-regular fa-calendar"></i>
                            <div class="field-body">
                                <label for="fly-date">{{ t('search.departure', 'Departure') }}</label>
                                <input type="date" id="fly-date" name="depart_date">
                            </div>
                        </div>
                        <div class="field">
                            <i class="fa-regular fa-user"></i>
                            <div class="field-body">
                                <label for="fly-guests">{{ t('search.passengers', 'Passengers') }}</label>
                                <select id="fly-guests" name="guests">
                                    <option value="1" selected>{{ t('popt.1', '1 passenger') }}</option>
                                    <option value="2">{{ t('popt.2', '2 passengers') }}</option>
                                    <option value="3">{{ t('popt.3', '3 passengers') }}</option>
                                    <option value="4">{{ t('popt.4', '4 passengers') }}</option>
                                </select>
                            </div>
                            <i class="fa-solid fa-chevron-down chev"></i>
                        </div>
                        <div class="search-submit">
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> {{ t('search.search', 'Search') }}</button>
                        </div>
                    </div>
                </form>

                <!-- Packages -->
                <form class="search-panel" id="panel-packages" action="/packages" method="get">
                    <div class="search-fields">
                        <div class="field">
                            <i class="fa-solid fa-location-dot"></i>
                            <div class="field-body">
                                <label for="pack-q">{{ t('search.destination', 'Destination') }}</label>
                                <input type="text" id="pack-q" name="q" placeholder="{{ t('search.where_go', 'Where do you want to go?') }}">
                            </div>
                        </div>
                        <div class="field">
                            <i class="fa-regular fa-calendar"></i>
                            <div class="field-body">
                                <label for="pack-date">{{ t('search.travel_date', 'Travel Date') }}</label>
                                <input type="date" id="pack-date" name="travel_date">
                            </div>
                        </div>
                        <div class="field">
                            <i class="fa-regular fa-user"></i>
                            <div class="field-body">
                                <label for="pack-guests">{{ t('search.guests', 'Guests') }}</label>
                                <select id="pack-guests" name="guests">
                                    <option value="1">{{ t('topt.1', '1 traveller') }}</option>
                                    <option value="2" selected>{{ t('topt.2', '2 travellers') }}</option>
                                    <option value="3">{{ t('topt.3', '3 travellers') }}</option>
                                    <option value="4">{{ t('topt.4', '4 travellers') }}</option>
                                </select>
                            </div>
                            <i class="fa-solid fa-chevron-down chev"></i>
                        </div>
                        <div class="search-submit">
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> {{ t('search.search', 'Search') }}</button>
                        </div>
                    </div>
                </form>

                <!-- Things to do -->
                <form class="search-panel" id="panel-things" action="/things-to-do" method="get">
                    <div class="search-fields">
                        <div class="field">
                            <i class="fa-solid fa-location-dot"></i>
                            <div class="field-body">
                                <label for="thing-q">{{ t('search.destination', 'Destination') }}</label>
                                <input type="text" id="thing-q" name="q" placeholder="{{ t('search.where', 'Where are you going?') }}">
                            </div>
                        </div>
                        <div class="field">
                            <i class="fa-regular fa-calendar"></i>
                            <div class="field-body">
                                <label for="thing-date">{{ t('search.date', 'Date') }}</label>
                                <input type="date" id="thing-date" name="travel_date">
                            </div>
                        </div>
                        <div class="field">
                            <i class="fa-regular fa-user"></i>
                            <div class="field-body">
                                <label for="thing-guests">{{ t('search.guests', 'Guests') }}</label>
                                <select id="thing-guests" name="guests">
                                    <option value="1">{{ t('aopt.1', '1 person') }}</option>
                                    <option value="2" selected>{{ t('aopt.2', '2 people') }}</option>
                                    <option value="3">{{ t('aopt.3', '3 people') }}</option>
                                    <option value="4">{{ t('aopt.4', '4 people') }}</option>
                                </select>
                            </div>
                            <i class="fa-solid fa-chevron-down chev"></i>
                        </div>
                        <div class="search-submit">
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> {{ t('search.search', 'Search') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ================= LIVE WEATHER ================= -->
@if(count($liveWeather))
    <div class="container">
        <div class="live-strip">
            <div class="live-strip-label">
                <i class="fa-solid fa-tower-broadcast"></i>
                <div>{{ t('live.live_now', 'Live now') }}<span>Open-Meteo</span></div>
            </div>
            @foreach($featured as $i => $d)
                @php $w = $liveWeather[$i] ?? null; @endphp
                @if($w)
                    @php $meta = weather_meta($w['code']); @endphp
                    <div class="stripe-item">
                        <i class="fa-solid {{ $meta[1] }}"></i>
                        <div>
                            <strong>{{ $d->name }}</strong>
                            <span>{{ (int) round($w['temp']) }}&deg;C &middot; {{ $meta[0] }}</span>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endif

<!-- ================= POPULAR DESTINATIONS ================= -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <div class="section-head-row">
                <div>
                    <span class="section-label">{{ t('pop.label', 'Popular Destinations') }}</span>
                    <h2>{{ t('pop.title', 'Discover Amazing Places') }}</h2>
                    <p class="section-sub">{{ t('pop.sub', 'Explore top destinations loved by travellers from around the world.') }}</p>
                </div>
                <a href="/things-to-do" class="view-all">{{ t('pop.viewall', 'View All') }} <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="destinations-grid">
            @foreach($featured as $d)
                <a class="dest-card" href="/destination/{{ $d->slug }}">
                    <img src="/{{ $d->image }}" alt="{{ $d->name }}, {{ $d->country }}">
                    <div class="dest-info">
                        <h3>{{ $d->name }}</h3>
                        <span>{{ $d->country }}</span>
                    </div>
                    <span class="dest-arrow"><i class="fa-solid fa-arrow-right"></i></span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- ================= HOW IT WORKS ================= -->
<section class="section how-section">
    <div class="container">
        <div class="how-grid">
            <div>
                <span class="section-label">{{ t('how.label', 'How It Works') }}</span>
                <h2>{{ t('how.title', 'Plan Your Trip in 3 Easy Steps') }}</h2>
                <p class="section-sub">{{ t('how.sub', "From search to takeoff, we've got you covered.") }}</p>
            </div>
            <div class="how-steps">
                <div class="step">
                    <div class="step-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                    <div>
                        <h3>{{ t('how.s1t', '1. Search') }}</h3>
                        <p>{{ t('how.s1d', 'Find hotels, flights and activities that fit your plans.') }}</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-icon"><i class="fa-regular fa-calendar-check"></i></div>
                    <div>
                        <h3>{{ t('how.s2t', '2. Book') }}</h3>
                        <p>{{ t('how.s2d', 'Secure your booking in minutes with flexible options.') }}</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-icon"><i class="fa-solid fa-plane"></i></div>
                    <div>
                        <h3>{{ t('how.s3t', '3. Enjoy') }}</h3>
                        <p>{{ t('how.s3d', 'Experience amazing destinations and create unforgettable memories.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= TRUST STRIP ================= -->
<div class="container">
    <div class="trust-strip">
        <div class="trust-item">
            <div class="ico"><i class="fa-solid fa-database"></i></div>
            <div>
                <h4>{{ t('trust.s1t', 'Open Data Sources') }}</h4>
                <p>{{ t('trust.s1d', 'Hotel, weather and country data comes from open, keyless APIs.') }}</p>
            </div>
        </div>
        <div class="trust-item">
            <div class="ico"><i class="fa-solid fa-eye"></i></div>
            <div>
                <h4>{{ t('trust.s2t', 'Transparent Demo Data') }}</h4>
                <p>{{ t('trust.s2d', 'Sample prices and bookings are clearly labelled as demo data.') }}</p>
            </div>
        </div>
        <div class="trust-item">
            <div class="ico"><i class="fa-solid fa-bolt"></i></div>
            <div>
                <h4>{{ t('trust.s3t', 'Locally Cached') }}</h4>
                <p>{{ t('trust.s3d', 'Responses are cached to keep pages fast and APIs lightly loaded.') }}</p>
            </div>
        </div>
        <div class="trust-item">
            <div class="ico"><i class="fa-solid fa-earth-asia"></i></div>
            <div>
                <h4>{{ $totalDest }}+ {{ t('trust.destinations', 'Destinations') }}</h4>
                <p>{{ sprintf(t('trust.hotels_note', 'And %s hotels to choose from.'), number_format($totalHot)) }}</p>
            </div>
        </div>
    </div>
</div>

@endsection
