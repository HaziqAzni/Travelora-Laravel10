<!DOCTYPE html>
<html lang="{{ current_lang() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Travelora - Your Next Adventure Awaits')</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon-32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicon-180.png">
    <meta name="theme-color" content="#2563eb">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

@include('partials.header')

@if(session('flash'))
    @php $flash = session('flash'); @endphp
    <div class="container">
        <div class="flash flash-{{ $flash['type'] }}">
            <i class="fa-solid {{ $flash['type'] === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation' }}"></i>
            {{ $flash['message'] }}
        </div>
    </div>
@endif

<main>
@yield('content')
</main>

@include('partials.footer')
@include('partials.booking-modal')

@php
    $i18nJs = [
        'noHotels'    => t('live.no_hotels', 'No live hotel data available for this destination right now.'),
        'realFound'   => t('live.real_found', 'real hotels found near'),
        'updated'     => t('live.updated', 'updated'),
        'viewMap'     => t('live.viewmap', 'View map'),
        'website'     => t('live.website', 'Website'),
        'addrUnknown' => t('live.addr_unknown', 'Address not listed'),
        'hotelsError' => t('live.no_hotels', 'Could not load live hotels right now.'),
        'bookPrefix'  => t('modal.book', 'Book'),
    ];
@endphp
<script>
window.I18N = {!! json_encode($i18nJs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
</script>
<script src="/assets/js/main.js"></script>
</body>
</html>
