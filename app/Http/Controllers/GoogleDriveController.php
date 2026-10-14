<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Google\Client;

class GoogleDriveController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        abort_unless(app()->environment('local'), 403);

        $client = $this->createGoogleClient();

        $state = Str::random(40);

        $request->session()->put('google_drive_oauth_state', $state);

        $client->setState($state);

        $client->setScopes([
            'https://www.googleapis.com/auth/drive.file',
        ]);

        $client->setAccessType('offline');
        $client->setPrompt('consent');

        return redirect()->away($client->createAuthUrl());
    }

    public function callback(Request $request)
    {
        abort_unless(app()->environment('local'), 403);

        if ($request->filled('error')) {
            return response(
                'Otorisasi Google dibatalkan: ' .
                e($request->input('error')),
                400
            );
        }

        if (!$request->filled('code')) {
            return response(
                'Authorization code tidak ditemukan.',
                400
            );
        }

        $expectedState = $request->session()->pull(
            'google_drive_oauth_state'
        );

        if (
            !$expectedState ||
            !$request->filled('state') ||
            !hash_equals($expectedState, $request->input('state'))
        ) {
            return response(
                'State OAuth tidak valid. Silakan ulangi proses koneksi.',
                400
            );
        }

        $client = $this->createGoogleClient();

        try {
            $token = $client->fetchAccessTokenWithAuthCode(
                $request->input('code')
            );

            if (isset($token['error'])) {
                Log::error('Google Drive OAuth gagal', [
                    'error' => $token['error'],
                    'description' => $token['error_description'] ?? null,
                ]);

                return response(
                    'Gagal mendapatkan token Google. Periksa log Laravel.',
                    400
                );
            }

            if (empty($token['refresh_token'])) {
                return response(
                    'Google tidak mengembalikan refresh token. ' .
                    'Pastikan access_type=offline dan prompt=consent, ' .
                    'lalu ulangi otorisasi.',
                    400
                );
            }

            return response()->view('google-drive.token', [
                'refreshToken' => $token['refresh_token'],
            ]);

        } catch (\Throwable $e) {
            Log::error('Gagal mendapatkan token Google Drive', [
                'message' => $e->getMessage(),
            ]);

            return response(
                'Terjadi kesalahan saat mendapatkan token. ' .
                'Periksa storage/logs/laravel.log.',
                500
            );
        }
    }

    private function createGoogleClient(): Client
    {
        $client = new Client();

        $client->setClientId(
            config('services.google_drive.client_id')
        );

        $client->setClientSecret(
            config('services.google_drive.client_secret')
        );

        $client->setRedirectUri(
            route('google-drive.callback')
        );

        return $client;
    }
}
