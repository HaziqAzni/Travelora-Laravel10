@extends('layouts.app')
@section('title', 'Flights - Travelora')

@section('content')
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb"><a href="/">{{ t('nav.home', 'Home') }}</a><span class="sep">/</span> {{ t('nav.flights', 'Flights') }}</div>
        <h1>{{ t('page.flights.title', 'Fly Smarter, Fly Cheaper') }}</h1>
        <p>{{ t('page.flights.sub', "Compare fares from the world's leading airlines in one search.") }}</p>
    </div>
</section>

<div class="container">
    <div class="filter-bar">
        <form class="filter-form" action="/flights" method="get">
            <div class="input-group">
                <i class="fa-solid fa-plane-departure"></i>
                <input type="text" name="from" value="{{ $from }}" placeholder="{{ t('filter.from_ph', 'From city or airport') }}">
            </div>
            <div class="input-group">
                <i class="fa-solid fa-plane-arrival"></i>
                <input type="text" name="to" value="{{ $to }}" placeholder="{{ t('filter.to_ph', 'To city or airport') }}">
            </div>
            <div class="input-group">
                <i class="fa-regular fa-calendar"></i>
                <input type="date" name="depart_date" value="{{ $date }}">
            </div>
            <div class="input-group">
                <i class="fa-regular fa-user"></i>
                <select name="guests">
                    @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ $i === $guests ? 'selected' : '' }}>{{ $i }} {{ $i > 1 ? t('filter.passengers', 'passengers') : t('filter.passenger', 'passenger') }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> {{ t('filter.search', 'Search') }}</button>
        </form>
    </div>

    <p class="results-meta">
        <strong>{{ $flights->count() }}</strong> {{ t('results.flights', 'flights') }} {{ t('results.found', 'found') }}
        @if($from !== '' || $to !== '')
            {{ t('results.for', 'for') }} {{ $from !== '' ? $from : t('filter.anywhere', 'anywhere') }} &rarr; {{ $to !== '' ? $to : t('filter.anywhere', 'anywhere') }}
        @endif
    </p>

    <p class="demo-note"><i class="fa-solid fa-circle-info"></i> {{ t('demo.catalogue', 'Demo catalogue — prices, ratings and reviews are sample data, not live inventory or real offers.') }}</p>

    @if($flights->isEmpty())
        <div class="empty">
            <i class="fa-solid fa-plane-slash"></i>
            <h3>{{ t('empty.flights.t', 'No flights found') }}</h3>
            <p>{{ t('empty.flights.d', 'Try different cities or codes (e.g. KUL, NRT, DPS).') }}</p>
        </div>
    @else
        <div class="flight-list">
            @foreach($flights as $f)
                <div class="flight-card">
                    <div class="flight-airline">
                        <div class="airline-logo"><i class="fa-solid fa-plane"></i></div>
                        <div>
                            <h4>{{ $f->airline }}</h4>
                            <span>{{ $f->stops == 0 ? t('card.direct', 'Direct flight') : (int) $f->stops . ' ' . t('card.stop', 'stop') }}</span>
                        </div>
                    </div>

                    <div class="flight-route">
                        <div class="flight-point">
                            <div class="time">{{ date('H:i', strtotime($f->depart_time)) }}</div>
                            <div class="code">{{ $f->from_code }} &middot; {{ $f->from_city }}</div>
                        </div>
                        <div class="flight-line">
                            <div class="duration">{{ $f->duration }}</div>
                            <div class="bar"></div>
                            <div class="stops {{ $f->stops == 0 ? 'direct' : '' }}">
                                {{ $f->stops == 0 ? t('card.direct_short', 'Direct') : (int) $f->stops . ' ' . t('card.stop', 'stop') }}
                            </div>
                        </div>
                        <div class="flight-point">
                            <div class="time">{{ date('H:i', strtotime($f->arrive_time)) }}</div>
                            <div class="code">{{ $f->to_code }} &middot; {{ $f->to_city }}</div>
                        </div>
                    </div>

                    <div class="flight-side">
                        <div class="price">{{ money($f->price) }}</div>
                        <div class="seats ok">{{ t('card.sample_fare', 'Sample fare') }}</div>
                        <button type="button" class="btn btn-primary btn-sm" data-book="flight"
                                data-id="{{ $f->id }}"
                                data-name="{{ $f->airline }}: {{ $f->from_code }} to {{ $f->to_code }}"
                                data-price="{{ (float) $f->price }}" data-needs-end="0"
                                data-travel="{{ t('modal.departure_date', 'Departure Date') }}" data-unit="{{ t('card.person', '/ person') }}">
                            {{ t('card.select', 'Select Flight') }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
