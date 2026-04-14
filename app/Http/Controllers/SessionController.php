<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SessionController extends Controller
{
    /**
     * Get all active sessions for the authenticated user.
     */
    public function index()
    {
        $sessions = $this->getSessions();
        return view('profile.partials.browser-sessions', compact('sessions'));
    }

    /**
     * Revoke all other sessions for the authenticated user.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        Auth::logoutOtherDevices($request->password);

        // Also delete the session records from the database for other sessions
        DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        return redirect()->route('profile.edit')
            ->with('success', 'Successfully logged out of all other browser sessions.');
    }

    /**
     * Revoke a single specific session by ID.
     */
    public function destroySingle(Request $request, string $sessionId)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        // Prevent terminating the current session via this endpoint
        if ($sessionId === $request->session()->getId()) {
            return redirect()->route('profile.edit')
                ->with('error', 'You cannot terminate your current session this way. Use Logout instead.');
        }

        // Ensure the session belongs to the authenticated user
        $deleted = DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', Auth::id())
            ->delete();

        if (!$deleted) {
            return redirect()->route('profile.edit')
                ->with('error', 'Session not found or already terminated.');
        }

        return redirect()->route('profile.edit')
            ->with('success', 'The selected session has been terminated successfully.');
    }

    /**
     * Get the sessions for the current user.
     */
    public function getSessions(): array
    {
        $currentSessionId = request()->session()->getId();

        return DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) use ($currentSessionId) {
                $agent = $this->parseUserAgent($session->user_agent ?? '');
                return (object) [
                    'id' => $session->id,
                    'agent' => $agent,
                    'ip_address' => $session->ip_address,
                    'is_current_device' => $session->id === $currentSessionId,
                    'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    'last_active_exact' => Carbon::createFromTimestamp($session->last_activity)->format('d M Y, h:i A'),
                ];
            })
            ->toArray();
    }

    /**
     * Parse user agent string into a human-readable format.
     */
    private function parseUserAgent(string $userAgent): object
    {
        $browser = 'Unknown Browser';
        $platform = 'Unknown Platform';
        $icon = 'bi-laptop';

        // Detect browser
        if (str_contains($userAgent, 'Edg/') || str_contains($userAgent, 'Edge/')) {
            $browser = 'Microsoft Edge';
        } elseif (str_contains($userAgent, 'Chrome') && !str_contains($userAgent, 'Chromium')) {
            $browser = 'Google Chrome';
        } elseif (str_contains($userAgent, 'Firefox')) {
            $browser = 'Mozilla Firefox';
        } elseif (str_contains($userAgent, 'Safari') && !str_contains($userAgent, 'Chrome')) {
            $browser = 'Apple Safari';
        } elseif (str_contains($userAgent, 'OPR') || str_contains($userAgent, 'Opera')) {
            $browser = 'Opera';
        }

        // Detect platform/device
        if (str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')) {
            $platform = str_contains($userAgent, 'iPad') ? 'iPad (iOS)' : 'iPhone (iOS)';
            $icon = 'bi-phone';
        } elseif (str_contains($userAgent, 'Android')) {
            $platform = 'Android';
            $icon = 'bi-phone';
        } elseif (str_contains($userAgent, 'Macintosh') || str_contains($userAgent, 'Mac OS')) {
            $platform = 'macOS';
            $icon = 'bi-laptop';
        } elseif (str_contains($userAgent, 'Windows')) {
            $platform = 'Windows';
            $icon = 'bi-windows';
        } elseif (str_contains($userAgent, 'Linux')) {
            $platform = 'Linux';
            $icon = 'bi-terminal';
        }

        return (object) [
            'browser' => $browser,
            'platform' => $platform,
            'icon' => $icon,
        ];
    }
}
