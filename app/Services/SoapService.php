<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SoapService
{
    public function sendAuditLog($account)
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

        $token = $authData['token'];

        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"
xmlns:iae="http://iae.central/audit">
    <soap:Body>
        <iae:AuditRequest>
            <iae:TeamID>TEAM-04</iae:TeamID>
            <iae:ActivityName>AccountCreated</iae:ActivityName>
            <iae:LogContent><![CDATA[
            {
                "email":"{$account->email}",
                "account_number":"{$account->account_number}",
                "name":"{$account->name}",
                "activity":"Account Created"
            }
            ]]></iae:LogContent>
        </iae:AuditRequest>
    </soap:Body>
</soap:Envelope>
XML;

        $response = Http::withToken($token)
            ->withHeaders([
                'Content-Type' => 'text/xml'
            ])
            ->send(
                'POST',
                'https://iae-sso.virtualfri.id/soap/v1/audit',
                [
                    'body' => $xml
                ]
            );

        return $response->body();
    }
}