@extends('layouts.app')
@section('title', 'My Bookings - Travelora')

@section('content')
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb"><a href="/">{{ t('nav.home', 'Home') }}</a><span class="sep">/</span> {{ t('nav.mybookings', 'My Bookings') }}</div>
        <h1>{{ t('bookings.title', 'My Trips') }}</h1>
        <p>{{ sprintf(t('bookings.sub', 'All your bookings in one place, %s.'), explode(' ', $user['name'])[0]) }}</p>
    </div>
</section>

<div class="container" style="margin-top:40px">
    <p class="demo-note" style="margin-bottom:24px">
        <i class="fa-solid fa-circle-info"></i>
        {{ t('bookings.demo_notice', 'All bookings here are demo requests. No payment is processed and no live availability is checked — dates and guest counts are illustrative.') }}
    </p>
    @if($bookings->isEmpty())
        <div class="empty">
            <i class="fa-solid fa-suitcase"></i>
            <h3>{{ t('bookings.empty.t', 'No bookings yet') }}</h3>
            <p>{{ t('bookings.empty.d', 'Your next adventure is one click away.') }}</p>
            <a href="/hotels" class="btn btn-primary" style="margin-top:18px">{{ t('bookings.start', 'Start Exploring') }}</a>
        </div>
    @else
        <div class="booking-list">
            @foreach($bookings as $b)
                <div class="booking-item">
                    <div class="booking-icon"><i class="{{ $icons[$b->booking_type] ?? 'fa-solid fa-suitcase' }}"></i></div>
                    <div class="booking-main">
                        <h3>{{ $b->item_name }}</h3>
                        <div class="meta">
                            <span><i class="fa-regular fa-calendar"></i>
                                {{ date('d M Y', strtotime($b->travel_date)) }}
                                @if(!empty($b->end_date)) &ndash; {{ date('d M Y', strtotime($b->end_date)) }}@endif
                            </span>
                            <span><i class="fa-regular fa-user"></i> {{ (int) $b->guests }} {{ t('bookings.travellers', 'travellers') }}</span>
                            <span><i class="fa-solid fa-hashtag"></i> TRV-{{ str_pad((string) $b->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                    <div class="booking-side">
                        <div class="price">{{ money($b->total_price) }}</div>
                        <span class="status status-{{ $b->status }}">{{ t('bookings.status_' . $b->status, ucfirst($b->status)) }}</span>
                        @if($b->status === 'confirmed')
                            <form action="/cancel-booking" method="post" onsubmit="return confirm('{{ t('bookings.confirm_cancel', 'Cancel this booking?') }}')" style="margin-top:8px">
                                @csrf
                                <input type="hidden" name="id" value="{{ $b->id }}">
                                <button type="submit" class="btn btn-outline btn-sm">{{ t('bookings.cancel', 'Cancel') }}</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
