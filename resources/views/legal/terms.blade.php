@extends('layouts.app')
@section('title', 'Terms of Service - Travelora')

@section('content')
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb"><a href="/">{{ t('nav.home', 'Home') }}</a><span class="sep">/</span> {{ t('footer.terms', 'Terms of Service') }}</div>
        <h1>{{ t('footer.terms', 'Terms of Service') }}</h1>
        <p>{{ t('legal.terms_sub', 'Placeholder terms for a demonstration application.') }}</p>
    </div>
</section>
<div class="container">
    <section class="detail-section" style="padding-bottom:60px">
        <div class="demo-note" style="margin-bottom:24px"><i class="fa-solid fa-circle-info"></i>
            Travelora is a portfolio / demonstration project. It does not sell travel, take payments, or provide real bookings. Replace this page with your own legal terms before any commercial use.
        </div>
        <h2 style="font-size:22px;margin:18px 0 8px">1. Demo service</h2>
        <p style="color:var(--slate)">All listings, prices, ratings, reviews and bookings shown are sample data for illustration only and are not offers of service.</p>
        <h2 style="font-size:22px;margin:18px 0 8px">2. No payments</h2>
        <p style="color:var(--slate)">No payment is collected. Bookings are stored as demo requests with status "pending".</p>
        <h2 style="font-size:22px;margin:18px 0 8px">3. Third-party data</h2>
        <p style="color:var(--slate)">Data is sourced from open APIs (OpenStreetMap, Wikipedia, Open-Meteo, Wikidata) under their respective licences and terms.</p>
    </section>
</div>
@endsection
