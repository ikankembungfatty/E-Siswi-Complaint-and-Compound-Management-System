<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Show the 2FA setup page (QR code).
     */
    public function showSetup()
    {
        $user = Auth::user();

        // Generate a new secret if the user doesn't have one yet
        if (!$user->two_factor_secret) {
            $secret = $this->google2fa->generateSecretKey();
            $user->update(['two_factor_secret' => $secret]);
        }

        $secret = $user->two_factor_secret;

        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        // Generate QR code as inline SVG
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCodeUrl);

        return view('profile.two-factor', compact('secret', 'qrCodeSvg', 'user'));
    }

    /**
     * Enable 2FA after the user confirms with a valid OTP.
     */
    public function enable(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|digits:6',
        ]);

        $user = Auth::user();

        $valid = $this->google2fa->verifyKey($user->two_factor_secret, $request->one_time_password);

        if (!$valid) {
            return back()->withErrors(['one_time_password' => 'The code you entered is invalid. Please try again.']);
        }

        $user->update(['two_factor_enabled' => true]);

        return redirect()->route('profile.edit')
            ->with('success', '2FA has been enabled successfully. Your account is now more secure!');
    }

    /**
     * Disable 2FA and clear the secret.
     */
    public function disable(Request $request)
    {
        $user = Auth::user();
        $user->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Two-factor authentication has been disabled.');
    }

    /**
     * Show the OTP challenge page (shown after login if 2FA is enabled).
     */
    public function showChallenge()
    {
        // If no pending user in session, redirect to login
        if (!session()->has('2fa:user:id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    /**
     * Verify the OTP challenge and complete the login.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|digits:6',
        ]);

        $userId = session('2fa:user:id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::find($userId);

        if (!$user) {
            session()->forget('2fa:user:id');
            return redirect()->route('login');
        }

        $valid = $this->google2fa->verifyKey($user->two_factor_secret, $request->one_time_password);

        if (!$valid) {
            return back()->withErrors(['one_time_password' => 'The code you entered is invalid. Please check your Google Authenticator app and try again.']);
        }

        // Complete the login
        session()->forget('2fa:user:id');
        session(['2fa:verified' => true]);
        Auth::loginUsingId($userId);
        $request->session()->regenerate();

        return redirect()->intended(\App\Providers\RouteServiceProvider::HOME);
    }
}
