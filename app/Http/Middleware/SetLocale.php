<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = ['en', 'ms', 'zh'];
        $locale = $request->query('lang') ?: session('locale') ?: $request->cookie('locale');
        if (!in_array($locale, $supported, true)) {
            $locale = 'en';
        }
        App::setLocale($locale);
        return $next($request);
    }
}
