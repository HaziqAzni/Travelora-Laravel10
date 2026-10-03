@extends('layouts.app')
@section('title', 'About Us - Travelora')

@section('content')
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb"><a href="/">{{ t('nav.home', 'Home') }}</a><span class="sep">/</span> {{ t('nav.about', 'About') }}</div>
        <h1>{{ t('about.title', 'We Make Travel Simple') }}</h1>
        <p>{{ t('about.sub', 'One place for hotels, flights, packages and experiences around the world.') }}</p>
    </div>
</section>

<div class="container">
    <p class="demo-note" style="margin-top:26px"><i class="fa-solid fa-circle-info"></i> {{ t('about.demo_note', 'Travelora is a demonstration project. Listings, prices, ratings, reviews, stats and team profiles on this page are illustrative sample content — not a real travel business.') }}</p>
    <section class="detail-section">
        <div class="about-grid">
            <div>
                <span class="section-label">{{ t('about.story_label', 'Our Story') }}</span>
                <h2 style="font-size:32px;margin-bottom:16px">{!! t('about.story_h', 'Built by travellers,<br>for travellers') !!}</h2>
                <p style="color:var(--slate);margin-bottom:14px">{{ t('about.p1', 'Travelora started with a simple frustration: planning a trip meant juggling ten tabs, five apps and endless price comparisons. We believed there had to be a better way.') }}</p>
                <p style="color:var(--slate);margin-bottom:14px">{{ t('about.p2', 'Today, Travelora brings hotels, flights, holiday packages and local experiences together into one clean, honest search. No hidden fees, no clutter - just great trips.') }}</p>
                <p style="color:var(--slate)">{{ t('about.p3', 'Whether you are chasing beaches in Langkawi, cherry blossoms in Tokyo or a city break in Seoul, our team is obsessed with getting you there for less.') }}</p>
            </div>
            <div class="about-img">
                <img src="/assets/img/destinations/bali.jpg" alt="Travelling with Travelora">
            </div>
        </div>

        <div class="stat-row">
            <div class="stat"><div class="num">{{ $totalDest }}+</div><div class="lbl">{{ t('about.stat.destinations', 'Destinations (demo)') }}</div></div>
            <div class="stat"><div class="num">{{ $totalHot }}+</div><div class="lbl">{{ t('about.stat.hotels', 'Hotels (demo)') }}</div></div>
            <div class="stat"><div class="num">{{ $totalFl }}+</div><div class="lbl">{{ t('about.stat.flights', 'Sample Routes') }}</div></div>
            <div class="stat"><div class="num">{{ $totalUser }}+</div><div class="lbl">{{ t('about.stat.travellers', 'Demo Accounts') }}</div></div>
        </div>
    </section>

    <section class="detail-section" style="padding-bottom:20px">
        <span class="section-label">{{ t('about.values_label', 'Our Values') }}</span>
        <h2 style="font-size:30px;margin-bottom:26px">{{ t('about.values_h', 'What we stand for') }}</h2>
        <div class="trust-strip" style="padding:0">
            <div class="trust-item">
                <div class="ico"><i class="fa-solid fa-eye"></i></div>
                <div><h4>{{ t('about.v1t', 'Open & Transparent') }}</h4><p>{{ t('about.v1d', 'Data sources and sample estimates are labelled, never hidden.') }}</p></div>
            </div>
            <div class="trust-item">
                <div class="ico"><i class="fa-solid fa-code-branch"></i></div>
                <div><h4>{{ t('about.v2t', 'Open-Source First') }}</h4><p>{{ t('about.v2d', 'Built on open, keyless APIs with local caching.') }}</p></div>
            </div>
            <div class="trust-item">
                <div class="ico"><i class="fa-solid fa-user-shield"></i></div>
                <div><h4>{{ t('about.v3t', 'Privacy-Conscious') }}</h4><p>{{ t('about.v3d', 'We store only the account and booking details you provide.') }}</p></div>
            </div>
            <div class="trust-item">
                <div class="ico"><i class="fa-solid fa-screwdriver-wrench"></i></div>
                <div><h4>{{ t('about.v4t', 'Maintainable') }}</h4><p>{{ t('about.v4d', 'Laravel MVC with migrations, seeders and tests.') }}</p></div>
            </div>
        </div>
    </section>

    <section class="detail-section">
        <span class="section-label">{{ t('about.team_label', 'Example Team') }}</span>
        <h2 style="font-size:30px;margin-bottom:10px">{{ t('about.team_h', 'Example profiles (illustrative)') }}</h2>
        <p style="color:var(--muted);margin-bottom:26px">{{ t('about.team_note', 'These are placeholder profiles, not real staff.') }}</p>
        <div class="team-grid">
            <div class="team-card"><div class="avatar-lg">A</div><h3>Aiman Zulkifli</h3><p>Co-founder &amp; CEO</p></div>
            <div class="team-card"><div class="avatar-lg">S</div><h3>Sarah Lim</h3><p>Head of Product</p></div>
            <div class="team-card"><div class="avatar-lg">R</div><h3>Raj Kumar</h3><p>Head of Partnerships</p></div>
        </div>
    </section>

    <section class="detail-section">
        <span class="section-label">{{ t('about.live_label', 'Live Data') }}</span>
        <h2 style="font-size:30px;margin-bottom:10px">{{ t('about.live_h', 'Powered by open APIs') }}</h2>
        <p style="color:var(--muted);max-width:640px;margin-bottom:26px">{{ t('about.live_p', 'Travelora blends its own curated catalogue with live data from open, keyless APIs. Everything is cached locally, so pages stay fast.') }}</p>
        <div class="api-grid">
            <div class="live-card">
                <h3><i class="fa-solid fa-hotel"></i> OpenStreetMap <span class="live-badge">ODbL</span></h3>
                <p class="api-desc">Real hotel names, addresses and locations via the Overpass API, and geocoding via Nominatim.</p>
                <a class="api-link" href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">openstreetmap.org <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
            </div>
            <div class="live-card">
                <h3><i class="fa-brands fa-wikipedia-w"></i> Wikipedia <span class="live-badge">CC BY-SA</span></h3>
                <p class="api-desc">Destination descriptions pulled live from the Wikipedia REST API.</p>
                <a class="api-link" href="https://www.mediawiki.org/wiki/API:REST_API" target="_blank" rel="noopener">mediawiki.org <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
            </div>
            <div class="live-card">
                <h3><i class="fa-solid fa-cloud-sun"></i> Open-Meteo <span class="live-badge">CC BY 4.0</span></h3>
                <p class="api-desc">Current weather and 4-day forecasts for every destination, refreshed every 30 minutes.</p>
                <a class="api-link" href="https://open-meteo.com" target="_blank" rel="noopener">open-meteo.com <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
            </div>
            <div class="live-card">
                <h3><i class="fa-solid fa-earth-asia"></i> Wikidata <span class="live-badge">CC0</span></h3>
                <p class="api-desc">Country facts such as capital, currency, languages and population via the Wikidata SPARQL endpoint.</p>
                <a class="api-link" href="https://query.wikidata.org" target="_blank" rel="noopener">query.wikidata.org <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
            </div>
            <div class="live-card">
                <h3><i class="fa-solid fa-gauge-high"></i> Integration Status <span class="live-badge">Live</span></h3>
                <p class="api-desc">See response times, cache stats and sync controls for every integration.</p>
                <a class="api-link" href="/api-status">Open API Status <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <section class="detail-section" style="text-align:center;padding-bottom:60px">
        <h2 style="font-size:30px;margin-bottom:10px">{{ t('about.cta_h', 'Ready to explore?') }}</h2>
        <p style="color:var(--muted);margin-bottom:22px">{{ t('about.cta_p', 'Your next adventure is waiting for you.') }}</p>
        <a href="/hotels" class="btn btn-primary btn-lg"><i class="fa-solid fa-magnifying-glass"></i> {{ t('about.cta_btn', 'Find a Hotel') }}</a>
    </section>
</div>
@endsection
