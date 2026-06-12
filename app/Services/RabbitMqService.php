<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RabbitMqService
{
    public function publishAccountEvent($account)
    {
        // Ambil M2M Token
        $authResponse = Http::post(
            'https://iae-sso.virtualfri.id/api/v1/auth/token',
            [
                'api_key' => 'KEY-MHS-334'
            ]
        );

        $authData = $authResponse->json();

        if (($authData['status'] ?? null) !== 'success') {
            return [
                'status' => 'error',
                'message' => 'Failed to get M2M token'
            ];
        }

        $m2mToken = $authData['token'];

        // Publish ke RabbitMQ
        $response = Http::withToken($m2mToken)
            ->post(
                'https://iae-sso.virtualfri.id/api/v1/messages/publish',
                [
                    'message' => [
                        'event' => 'account_created',
                        'user_email' => $account->email,
                        'account_number' => $account->account_number,
                        'name' => $account->name,
                        'status' => 'success',
                        'created_at' => now()->toDateTimeString()
                    ]
                ]
            );

        return $response->json();
    }
}