@extends('layouts.app')
@section('title', 'API Status - Travelora')

@section('content')
<section class="page-hero">
    <div class="container">
        <div class="breadcrumb"><a href="/">{{ t('nav.home', 'Home') }}</a><span class="sep">/</span> {{ t('footer.apistatus', 'API Status') }}</div>
        <h1>{{ t('api.title', 'API Integrations') }}</h1>
        <p>{{ t('api.sub', 'Live health checks, sync controls and cache stats for every data source.') }}</p>
    </div>
</section>

<div class="container" style="margin-top:40px">
    @unless(current_user())
        <div class="flash flash-error" style="margin-bottom:26px">
            <i class="fa-solid fa-lock"></i>
            {{ t('api.login_notice', 'Sync actions require an account.') }} <a href="/login?redirect=/api-status" style="font-weight:600;color:inherit;text-decoration:underline">{{ t('api.login_link', 'Log in') }}</a> {{ t('api.login_notice2', 'to sync data.') }}
        </div>
    @endunless

    <h2 style="font-size:24px;margin-bottom:6px">{{ t('api.health_h', 'Provider health') }}</h2>
    <p style="color:var(--muted);font-size:14px;margin-bottom:22px">{{ t('api.health_p', 'All checks are keyless and free. First run populates the local cache; after that they return in milliseconds.') }}</p>

    <div class="api-status-grid">
        @foreach($checks as $c)
            <div class="live-card">
                <h3>
                    <i class="{{ $c['icon'] }}"></i>
                    <span><span class="status-dot {{ $c['ok'] ? 'status-ok' : 'status-fail' }}"></span>{{ $c['provider'] }}</span>
                </h3>
                <p class="api-desc" style="margin-bottom:10px">{{ $c['use'] }}</p>
                <div class="api-meta-row"><span>{{ $c['endpoint'] }}</span><span>{{ $c['ok'] ? $c['ms'] . ' ms' : 'FAILED' }}</span></div>
                <div class="api-meta-row" style="border-top:none;padding-top:4px;margin-top:0">
                    <span>{{ $c['license'] }}</span>
                    <span>{{ $c['detail'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <h2 style="font-size:24px;margin-bottom:6px">{{ t('api.sync_h', 'Sync & maintenance') }}</h2>
    <p style="color:var(--muted);font-size:14px;margin-bottom:20px">
        Database: <strong>{{ $db['destinations'] }}</strong> destinations ({{ $db['destinations_geo'] }} geocoded) &middot;
        <strong>{{ $db['hotels_seed'] }}</strong> curated hotels &middot;
        <strong>{{ $db['hotels_osm'] }}</strong> imported from OSM.
        Cache: <strong>{{ $cache['files'] }}</strong> responses ({{ number_format($cache['bytes'] / 1024, 1) }} KB).
    </p>

    <div class="sync-actions" style="margin-bottom:50px">
        <form action="/sync-destinations" method="post">
            @csrf
            <button class="btn btn-primary" {{ current_user() ? '' : 'disabled' }}>
                <i class="fa-solid fa-location-crosshairs"></i> {{ t('api.btn.dest', 'Sync Destination Coordinates') }}
            </button>
        </form>
        <form action="/sync-hotels" method="post">
            @csrf
            <input type="hidden" name="all" value="1">
            <button class="btn btn-primary" {{ current_user() ? '' : 'disabled' }}>
                <i class="fa-solid fa-hotel"></i> {{ t('api.btn.hotels', 'Import OSM Hotels (all destinations)') }}
            </button>
        </form>
        <form action="/clear-cache" method="post">
            @csrf
            <button class="btn btn-outline" {{ current_user() ? '' : 'disabled' }}>
                <i class="fa-solid fa-broom"></i> {{ t('api.btn.cache', 'Clear API Cache') }}
            </button>
        </form>
    </div>

    <h2 style="font-size:24px;margin-bottom:16px">Destinations &amp; live data</h2>
    <div class="table-wrap">
        <table class="flight-live-table">
            <thead>
                <tr>
                    <th>{{ t('api.tbl.destination', 'Destination') }}</th>
                    <th>{{ t('api.tbl.country', 'Country Code') }}</th>
                    <th>{{ t('api.tbl.coords', 'Coordinates') }}</th>
                    <th>{{ t('api.tbl.osm', 'OSM Hotels') }}</th>
                    <th>{{ t('api.tbl.actions', 'Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dests as $d)
                    <tr>
                        <td class="cs">{{ $d->name }}, {{ $d->country }}</td>
                        <td>{!! $d->country_code ? '<span class="chip">' . e($d->country_code) . '</span>' : '<span style="color:var(--muted)">-</span>' !!}</td>
                        <td>
                            @if($d->lat !== null)
                                <i class="fa-solid fa-circle-check" style="color:#10b981"></i>
                                <span style="font-size:12.5px;color:var(--muted)">{{ number_format((float) $d->lat, 3) }}, {{ number_format((float) $d->lng, 3) }}</span>
                            @else
                                <i class="fa-solid fa-circle-xmark" style="color:#ef4444"></i>
                                <span style="font-size:12.5px;color:var(--muted)">{{ t('api.not_geocoded', 'not geocoded') }}</span>
                            @endif
                        </td>
                        <td>{!! $d->osm_hotels > 0 ? '<strong>' . (int) $d->osm_hotels . '</strong> ' . t('api.in_db', 'in database') : '<span style="color:var(--muted)">' . t('api.none_yet', 'none yet') . '</span>' !!}</td>
                        <td>
                            <div style="display:flex;gap:8px">
                                <a class="btn btn-outline btn-sm" href="/destination/{{ $d->slug }}#live">{{ t('api.view', 'View') }}</a>
                                <form action="/sync-hotels" method="post">
                                    @csrf
                                    <input type="hidden" name="destination_id" value="{{ $d->id }}">
                                    <button class="btn btn-primary btn-sm" {{ current_user() ? '' : 'disabled' }}>{{ t('api.sync_hotels', 'Sync hotels') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <p class="attribution" style="margin-bottom:60px">
        Data sources are cached locally: weather 30 min &middot; OSM hotels 24 h &middot; Wikipedia 7 days &middot; geocoding 30 days.
    </p>
</div>
@endsection
