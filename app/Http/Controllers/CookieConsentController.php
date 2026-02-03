<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CookieConsentController extends Controller
{
    protected string $cookieName = 'cookie_consent';
    protected int $cookieDays = 180; // 6 months

    public function status(Request $request)
    {
        $consent = $this->getConsent($request);
        return response()->json([
            'consent' => $consent,
            'hasConsent' => !is_null($consent),
        ]);
    }

    public function acceptAll(Request $request)
    {
        $payload = [
            'necessary' => true,
            'functional' => true,
            'analytics' => true,
            'marketing' => true,
            'timestamp' => now()->toIso8601String(),
            'version' => '1.0',
        ];
        return $this->storeConsent($payload);
    }

    public function rejectAll(Request $request)
    {
        $payload = [
            'necessary' => true, // necessary cookies are always on
            'functional' => false,
            'analytics' => false,
            'marketing' => false,
            'timestamp' => now()->toIso8601String(),
            'version' => '1.0',
        ];
        return $this->storeConsent($payload);
    }

    public function save(Request $request)
    {
        $data = $request->validate([
            'functional' => 'required|boolean',
            'analytics' => 'required|boolean',
            'marketing' => 'required|boolean',
        ]);

        $payload = array_merge([
            'necessary' => true,
            'timestamp' => now()->toIso8601String(),
            'version' => '1.0',
        ], $data);

        return $this->storeConsent($payload);
    }

    protected function storeConsent(array $payload)
    {
        $minutes = $this->cookieDays * 24 * 60;
        $json = json_encode($payload, JSON_UNESCAPED_SLASHES);
        Cookie::queue(cookie(
            name: $this->cookieName,
            value: $json,
            minutes: $minutes,
            path: '/',
            domain: null,
            secure: request()->isSecure(),
            httpOnly: false,
            raw: false,
            sameSite: 'Lax'
        ));

        return response()->json(['message' => 'Consent saved', 'consent' => $payload]);
    }

    protected function getConsent(Request $request): ?array
    {
        $val = $request->cookie($this->cookieName);
        if (!$val) {
            return null;
        }
        $data = json_decode($val, true);
        return is_array($data) ? $data : null;
    }
}
