<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $back  = safe_redirect($request->headers->get('referer'), '/');
        $email = trim((string) $request->input('email'));

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            set_flash('error', t('err.email_invalid', 'Please enter a valid email address.'));
            return redirect($back);
        }

        Subscriber::firstOrCreate(['email' => $email]);
        set_flash('success', t('newsletter.ok', 'You are subscribed! Travel deals are on their way.'));

        return redirect($back);
    }
}
