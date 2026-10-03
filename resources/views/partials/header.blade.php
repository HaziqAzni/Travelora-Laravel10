@php
    $user         = current_user();
    $currentLang  = current_lang();
    $langRedirect = request()->getRequestUri();
    $path         = trim(request()->path(), '/');
    $active       = $path === '' ? 'home' : ($path === 'things-to-do' ? 'things' : $path);

    $nav_items = [
        'home'     => ['label' => t('nav.home', 'Home'),           'href' => '/'],
        'hotels'   => ['label' => t('nav.hotels', 'Hotels'),       'href' => '/hotels'],
        'flights'  => ['label' => t('nav.flights', 'Flights'),     'href' => '/flights'],
        'packages' => ['label' => t('nav.packages', 'Packages'),   'href' => '/packages'],
        'things'   => ['label' => t('nav.things', 'Things to Do'), 'href' => '/things-to-do'],
        'about'    => ['label' => t('nav.about', 'About'),         'href' => '/about'],
    ];
@endphp

<header class="site-header">
    <div class="container header-inner">
        <a class="logo" href="/">
            <img class="logo-emblem" src="/assets/img/logo-travelora.png" alt="Travelora">
        </a>

        <nav class="main-nav" id="mainNav">
            @foreach($nav_items as $key => $item)
                <a href="{{ $item['href'] }}" class="nav-link {{ $active === $key ? 'active' : '' }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="header-actions">
            <div class="lang-dropdown">
                <button type="button" class="lang-btn" aria-haspopup="true" aria-expanded="false">
                    <i class="fa-solid fa-globe"></i> {{ strtoupper($currentLang) }} <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="lang-menu">
                    @foreach(supported_languages() as $code => $name)
                        <a href="/set-language/{{ $code }}?redirect={{ urlencode($langRedirect) }}"
                           class="{{ $code === $currentLang ? 'active' : '' }}">
                            <span class="flag">{{ strtoupper($code) }}</span> {{ $name }}
                            @if($code === $currentLang)<i class="fa-solid fa-check lang-check"></i>@endif
                        </a>
                    @endforeach
                </div>
            </div>

            @if($user)
                <div class="user-dropdown">
                    <button type="button" class="user-btn">
                        <span class="avatar">{{ strtoupper(substr($user['name'], 0, 1)) }}</span>
                        {{ explode(' ', $user['name'])[0] }}
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <div class="user-menu">
                        <a href="/my-bookings"><i class="fa-solid fa-suitcase-rolling"></i> {{ t('nav.mybookings', 'My Bookings') }}</a>
                        <form action="/logout" method="post">
                            @csrf
                            <button type="submit"><i class="fa-solid fa-right-from-bracket"></i> {{ t('nav.logout', 'Log Out') }}</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="/login" class="btn btn-outline">{{ t('nav.login', 'Log In') }}</a>
                <a href="/signup" class="btn btn-primary">{{ t('nav.signup', 'Sign Up') }}</a>
            @endif

            <button type="button" class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>
</header>
