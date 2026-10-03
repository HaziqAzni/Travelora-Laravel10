@extends('layouts.app')
@section('title', 'Holiday Packages - Travelora')

@section('content')
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb"><a href="/">{{ t('nav.home', 'Home') }}</a><span class="sep">/</span> {{ t('nav.packages', 'Packages') }}</div>
        <h1>{{ t('page.packages.title', 'All-Inclusive Holiday Packages') }}</h1>
        <p>{{ t('page.packages.sub', 'Flights, stays and experiences bundled into one great price.') }}</p>
    </div>
</section>

<div class="container">
    <div class="filter-bar">
        <form class="filter-form" action="/packages" method="get">
            <div class="input-group">
                <i class="fa-solid fa-location-dot"></i>
                <input type="text" name="q" value="{{ $q }}" placeholder="{{ t('search.pkg_ph', 'Destination or package name') }}">
            </div>
            <div class="input-group">
                <i class="fa-regular fa-calendar"></i>
                <input type="date" name="travel_date" value="{{ $date }}">
            </div>
            <div class="input-group">
                <i class="fa-regular fa-user"></i>
                <select name="guests">
                    @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ $i === $guests ? 'selected' : '' }}>{{ $i }} {{ $i > 1 ? t('filter.travellers', 'travellers') : t('filter.traveller', 'traveller') }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> {{ t('filter.search', 'Search') }}</button>
        </form>
    </div>

    <p class="results-meta">
        <strong>{{ $packages->count() }}</strong> {{ t('results.packages', 'packages') }} {{ t('results.available', 'available') }}
        @if($q !== '') {{ t('results.for', 'for') }} &ldquo;{{ $q }}&rdquo;@endif
    </p>

    <p class="demo-note"><i class="fa-solid fa-circle-info"></i> {{ t('demo.catalogue', 'Demo catalogue — prices, ratings and reviews are sample data, not live inventory or real offers.') }}</p>

    @if($packages->isEmpty())
        <div class="empty">
            <i class="fa-solid fa-suitcase-rolling"></i>
            <h3>{{ t('empty.packages.t', 'No packages found') }}</h3>
            <p>{{ t('empty.packages.d', 'Try another destination or browse all packages.') }}</p>
        </div>
    @else
        <div class="cards-grid">
            @foreach($packages as $p)
                <article class="card">
                    <div class="card-media">
                        <img src="/{{ $p->image }}" alt="{{ $p->title }}">
                        @if(!empty($p->badge))
                            <span class="card-badge {{ $p->badge === 'Luxury' ? 'warn' : '' }}">{{ $p->badge }}</span>
                        @endif
                        <span class="card-price-tag">{{ money($p->price) }} <small>{{ t('card.person', '/ person') }}</small></span>
                    </div>
                    <div class="card-body">
                        <div class="card-top">
                            <h3>{{ $p->title }}</h3>
                            <span class="card-rating"><i class="fa-solid fa-star"></i> {{ number_format((float) $p->rating, 1) }}</span>
                        </div>
                        <p class="card-location">
                            <i class="fa-solid fa-location-dot"></i>
                            <a href="/destination/{{ $p->destination_slug }}">{{ $p->destination }}, {{ $p->country }}</a>
                            &nbsp;&middot;&nbsp;<i class="fa-regular fa-clock"></i> {{ (int) $p->days }}D / {{ (int) $p->nights }}N
                        </p>
                        <div class="card-meta">
                            @foreach(array_slice(explode(',', (string) $p->includes), 0, 3) as $inc)
                                <span class="chip"><i class="fa-solid fa-check" style="color:#059669;font-size:10px"></i> {{ trim($inc) }}</span>
                            @endforeach
                        </div>
                        <div class="card-footer">
                            <div class="price">{{ money($p->price) }}</div>
                            <button type="button" class="btn btn-primary btn-sm" data-book="package"
                                    data-id="{{ $p->id }}" data-name="{{ $p->title }}"
                                    data-price="{{ (float) $p->price }}" data-needs-end="0"
                                    data-travel="{{ t('modal.travel_date', 'Travel Date') }}" data-unit="{{ t('card.person', '/ person') }}">
                                {{ t('card.book', 'Book Now') }}
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection
