<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OneSignalService
{
    public static function sendNotification($userId, $message)
    {
        $appId = config('services.onesignal.app_id');
        $apiKey = config('services.onesignal.rest_api_key');

        if (!$appId || $appId === 'YOUR_APP_ID_HERE') {
            return;
        }

        return Http::timeout(5)->withHeaders([
            'Authorization' => 'Basic ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', [
            'app_id' => $appId,
            'include_external_user_ids' => [(string) $userId],
            'contents' => ['en' => $message],
            'headings' => ['en' => 'Waktunya Manjain Motor lo!'],
        ]);
    }
}
