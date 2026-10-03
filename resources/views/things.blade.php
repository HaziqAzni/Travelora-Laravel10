@extends('layouts.app')
@section('title', 'Things to Do - Travelora')

@section('content')
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb"><a href="/">{{ t('nav.home', 'Home') }}</a><span class="sep">/</span> {{ t('nav.things', 'Things to Do') }}</div>
        <h1>{{ t('page.things.title', 'Unforgettable Experiences') }}</h1>
        <p>{{ t('page.things.sub', 'Tours, tickets and activities hosted by locals who know best.') }}</p>
    </div>
</section>

<div class="container">
    <div class="filter-bar">
        <form class="filter-form" action="/things-to-do" method="get">
            <div class="input-group">
                <i class="fa-solid fa-location-dot"></i>
                <input type="text" name="q" value="{{ $q }}" placeholder="{{ t('search.activity', 'Destination or activity') }}">
            </div>
            <div class="input-group">
                <i class="fa-regular fa-calendar"></i>
                <input type="date" name="travel_date" value="{{ $date }}">
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
        <strong>{{ $things->count() }}</strong> {{ t('results.experiences', 'experiences') }} {{ t('results.found', 'found') }}
        @if($q !== '') {{ t('results.for', 'for') }} &ldquo;{{ $q }}&rdquo;@endif
    </p>

    <p class="demo-note"><i class="fa-solid fa-circle-info"></i> {{ t('demo.catalogue', 'Demo catalogue — prices, ratings and reviews are sample data, not live inventory or real offers.') }}</p>

    @if($things->isEmpty())
        <div class="empty">
            <i class="fa-solid fa-ticket"></i>
            <h3>{{ t('empty.things.t', 'No experiences found') }}</h3>
            <p>{{ t('empty.things.d', 'Try another destination or activity type.') }}</p>
        </div>
    @else
        <div class="cards-grid">
            @foreach($things as $thing)
                <article class="card">
                    <div class="card-media">
                        <img src="/{{ $thing->image }}" alt="{{ $thing->title }}">
                        <span class="card-badge">{{ $thing->category }}</span>
                        <span class="card-price-tag">{{ money($thing->price) }} <small>{{ t('card.person', '/ person') }}</small></span>
                    </div>
                    <div class="card-body">
                        <div class="card-top">
                            <h3>{{ $thing->title }}</h3>
                            <span class="card-rating"><i class="fa-solid fa-star"></i> {{ number_format((float) $thing->rating, 1) }}</span>
                        </div>
                        <p class="card-location">
                            <i class="fa-solid fa-location-dot"></i>
                            <a href="/destination/{{ $thing->destination_slug }}">{{ $thing->destination }}, {{ $thing->country }}</a>
                            &nbsp;&middot;&nbsp;<i class="fa-regular fa-clock"></i> {{ $thing->duration }}
                        </p>
                        <div class="card-meta">
                            <span class="chip">{{ t('card.sample_listing', 'Sample listing') }}</span>
                        </div>
                        <div class="card-footer">
                            <div>
                                <div class="price" style="font-size:18px">{{ money($thing->price) }} <small>{{ t('misc.per_person', 'per person') }}</small></div>
                                <div style="font-size:12px;color:var(--muted)">{{ (int) $thing->reviews }} {{ t('card.reviews', 'reviews') }}</div>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" data-book="activity"
                                    data-id="{{ $thing->id }}" data-name="{{ $thing->title }}"
                                    data-price="{{ (float) $thing->price }}" data-needs-end="0"
                                    data-travel="{{ t('modal.activity_date', 'Activity Date') }}" data-unit="{{ t('card.person', '/ person') }}">
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
