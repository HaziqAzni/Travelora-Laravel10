@extends('layouts.app')
@section('title', 'Privacy Policy - Travelora')

@section('content')
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb"><a href="/">{{ t('nav.home', 'Home') }}</a><span class="sep">/</span> {{ t('footer.privacy', 'Privacy Policy') }}</div>
        <h1>{{ t('footer.privacy', 'Privacy Policy') }}</h1>
        <p>{{ t('legal.privacy_sub', 'Placeholder privacy policy for a demonstration application.') }}</p>
    </div>
</section>
<div class="container">
    <section class="detail-section" style="padding-bottom:60px">
        <div class="demo-note" style="margin-bottom:24px"><i class="fa-solid fa-circle-info"></i>
            This is a demo. Do not enter real personal or payment data. Replace this page with a compliant policy before handling real user data.
        </div>
        <h2 style="font-size:22px;margin:18px 0 8px">1. What we store</h2>
        <p style="color:var(--slate)">Only the account details you provide (name, email, hashed password) and any demo booking requests you create.</p>
        <h2 style="font-size:22px;margin:18px 0 8px">2. Cookies</h2>
        <p style="color:var(--slate)">A session cookie for login and a language preference cookie. No advertising or third-party tracking cookies are used.</p>
        <h2 style="font-size:22px;margin:18px 0 8px">3. Third parties</h2>
        <p style="color:var(--slate)">Destination pages request public read-only data from OpenStreetMap, Wikipedia, Open-Meteo and Wikidata. No personal data is sent to them.</p>
    </section>
</div>
@endsection
