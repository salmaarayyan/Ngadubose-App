<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Recaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $message = 'Verifikasi reCAPTCHA gagal. Silakan centang kotak verifikasi bahwa Anda bukan robot.';

        try {
            $response = Http::asForm()->timeout(10)->post(config('services.recaptcha.verify_url'), [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Verifikasi reCAPTCHA gagal dihubungi', ['error' => $e->getMessage()]);
            $fail($message);

            return;
        }

        if (! $response->json('success')) {
            $fail($message);
        }
    }
}
