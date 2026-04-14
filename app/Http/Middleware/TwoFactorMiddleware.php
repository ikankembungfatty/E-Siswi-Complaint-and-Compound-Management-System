<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->hasTwoFactorEnabled()) {
            // If NOT yet 2FA verified in this session, redirect to challenge
            if (!session('2fa:verified')) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')
                    ->with('error', 'Please complete two-factor authentication.');
            }
        }

        return $next($request);
    }
}
