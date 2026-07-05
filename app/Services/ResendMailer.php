<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ResendMailer
{
    public static function send(string $to, string $subject, string $htmlBody): bool
    {
        $response = Http::withToken(config('services.resend.key'))
            ->post('https://api.resend.com/emails', [
                'from' => config('mail.from.name') . ' <' . config('mail.from.address') . '>',
                'to' => [$to],
                'subject' => $subject,
                'html' => $htmlBody,
            ]);

        if ($response->failed()) {
            Log::error('Resend email gagal terkirim', [
                'to' => $to,
                'subject' => $subject,
                'response' => $response->body(),
            ]);
        }

        return $response->successful();
    }
}