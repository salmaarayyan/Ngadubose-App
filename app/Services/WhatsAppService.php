<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public function kirimNotifikasi($nomor_hp, $pesan)
    {
        $nomor_hp = str_replace([' ', '-', '+'], '', $nomor_hp);
        if (substr($nomor_hp, 0, 2) === '08') {
            $nomor_hp = '628' . substr($nomor_hp, 2);
        }
        $token = config('services.fonnte.token');
        $url = config('services.fonnte.url');

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->post($url, [
            'target' => $nomor_hp,
            'message' => $pesan,
        ]);

        return $response->body();
    }
}