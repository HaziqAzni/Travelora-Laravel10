<?php

use Illuminate\Support\Facades\Session;

/**
 * Travelora - global helpers (i18n + view helpers)
 */

function supported_languages(): array
{
    return [
        'en' => 'English',
        'ms' => 'Bahasa Melayu',
        'zh' => '中文',
    ];
}

function current_lang(): string
{
    $supported = supported_languages();
    foreach ([request()->query('lang'), Session::get('locale'), request()->cookie('locale')] as $candidate) {
        if (is_string($candidate) && isset($supported[$candidate])) {
            return $candidate;
        }
    }
    return 'en';
}

function lang_dict(): array
{
    static $dict = null;
    static $loaded = null;
    $lang = current_lang();
    if ($loaded !== $lang) {
        $loaded = $lang;
        $dict   = [];
        $file   = base_path('lang/' . $lang . '.php');
        if (is_file($file)) {
            $data = include $file;
            if (is_array($data)) {
                $dict = $data;
            }
        }
    }
    return $dict;
}

/** Translate a key, falling back to the given English string. */
function t(string $key, string $en): string
{
    $dict = lang_dict();
    return (isset($dict[$key]) && $dict[$key] !== '') ? $dict[$key] : $en;
}

function money($amount): string
{
    return '$' . number_format((float) $amount, 0);
}

function stars(int $count): string
{
    return str_repeat('<i class="fa-solid fa-star"></i>', max(1, min(5, $count)));
}

function current_user(): ?array
{
    return Session::get('user');
}

function set_flash(string $type, string $message): void
{
    session()->flash('flash', ['type' => $type, 'message' => $message]);
}

function get_flash(): ?array
{
    return session('flash');
}

function api_cache_dir(): string
{
    return storage_path('app/api-cache');
}

function api_log_dir(): string
{
    return storage_path('logs');
}

/**
 * Only allow redirects that stay on this site.
 * Accepts local paths ("/hotels?q=x") and absolute URLs whose host matches
 * the current request host. Anything else falls back to $fallback.
 */
function safe_redirect(?string $url, string $fallback = '/'): string
{
    if (!is_string($url)) {
        return $fallback;
    }
    $url = trim($url);
    if ($url === '' || preg_match('/[\r\n]/', $url)) {
        return $fallback;
    }
    // Block protocol-relative and backslash tricks
    if (str_starts_with($url, '//') || str_starts_with($url, '/\\') || str_contains($url, '\\')) {
        return $fallback;
    }
    // Local path
    if (str_starts_with($url, '/')) {
        return $url;
    }
    // Absolute URL: allow only same host over http(s)
    $parts = parse_url($url);
    if ($parts === false || empty($parts['host'])) {
        return $fallback;
    }
    $scheme = strtolower($parts['scheme'] ?? '');
    if (!in_array($scheme, ['http', 'https'], true)) {
        return $fallback;
    }
    if (strcasecmp($parts['host'], request()->getHost()) !== 0) {
        return $fallback;
    }
    $path = $parts['path'] ?? '/';
    if ($path === '' || $path[0] !== '/') {
        $path = '/' . $path;
    }
    $query = isset($parts['query']) && $parts['query'] !== '' ? '?' . $parts['query'] : '';

    return $path . $query;
}
