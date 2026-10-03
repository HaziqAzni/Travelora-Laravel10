@extends('layouts.app')
@section('title', 'Destination not found - Travelora')

@section('content')
<div class="container">
    <div class="empty" style="margin:60px 0">
        <i class="fa-solid fa-map-location-dot"></i>
        <h3>{{ t('dest.not_found_t', 'Destination not found') }}</h3>
        <p>{{ t('dest.not_found_d', 'It may have been removed or the link is wrong.') }}</p>
        <a class="btn btn-primary" style="margin-top:16px" href="/">{{ t('dest.back_home', 'Back to Home') }}</a>
    </div>
</div>
@endsection
