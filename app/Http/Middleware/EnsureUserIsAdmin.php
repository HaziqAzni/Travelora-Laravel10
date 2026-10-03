<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restricts maintenance actions (data sync, cache clearing) to admins.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = current_user();

        if (!$user || ($user['role'] ?? 'user') !== 'admin') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Administrator access required.'], 403);
            }
            abort(403, 'Administrator access required.');
        }

        return $next($request);
    }
}
