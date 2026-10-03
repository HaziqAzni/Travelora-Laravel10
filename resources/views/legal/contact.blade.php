@extends('layouts.app')
@section('title', 'Contact Us - Travelora')

@section('content')
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb"><a href="/">{{ t('nav.home', 'Home') }}</a><span class="sep">/</span> {{ t('footer.contact', 'Contact Us') }}</div>
        <h1>{{ t('footer.contact', 'Contact Us') }}</h1>
        <p>{{ t('legal.contact_sub', 'This is a demo app, so there is no real support team.') }}</p>
    </div>
</section>
<div class="container">
    <section class="detail-section" style="padding-bottom:60px">
        <div class="demo-note" style="margin-bottom:24px"><i class="fa-solid fa-circle-info"></i>
            No contact form or human support is provided in this demonstration. For a real product, add a working contact channel here.
        </div>
        <p style="color:var(--slate)">If you are evaluating this project, please reach out through the channel you received it from.</p>
    </section>
</div>
@endsection
