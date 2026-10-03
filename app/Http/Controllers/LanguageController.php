<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Hotel;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function set(Request $request, string $lang)
    {
        if (!array_key_exists($lang, supported_languages())) {
            $lang = 'en';
        }

        session(['locale' => $lang]);

        $redirect = safe_redirect((string) $request->query('redirect', '/'), '/');

        return redirect($redirect)->withCookie(cookie('locale', $lang, 60 * 24 * 365));
    }
}
