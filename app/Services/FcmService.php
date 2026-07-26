<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FCMService
{
    /**
     * Your Firebase Project ID
     */
    private string $projectId = 'minijobhunt';

    /**
     * Service Account JSON path
     */
    private string $serviceAccountPath;

    public function __construct()
    {
        $this->serviceAccountPath = storage_path(
            'app/firebase/minijobhunt-firebase-adminsdk-fbsvc-39565011bc.json'
        );
    }

    /**
     * Get Firebase Access Token
     */
    private function getAccessToken(): ?string
    {
        if (!file_exists($this->serviceAccountPath)) {
            return null;
        }

        $serviceAccount = json_decode(
            file_get_contents($this->serviceAccountPath),
            true
        );

        if (!$serviceAccount) {
            return null;
        }

        $now = time();

        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT'
        ];

        $claims = [
            'iss'   => $serviceAccount['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud'   => $serviceAccount['token_uri'],
            'iat'   => $now,
            'exp'   => $now + 3600
        ];

        $jwtHeader = rtrim(
            strtr(base64_encode(json_encode($header)), '+/', '-_'),
            '='
        );

        $jwtClaims = rtrim(
            strtr(base64_encode(json_encode($claims)), '+/', '-_'),
            '='
        );

        $signatureInput = $jwtHeader . "." . $jwtClaims;

        openssl_sign(
            $signatureInput,
            $signature,
            $serviceAccount['private_key'],
            OPENSSL_ALGO_SHA256
        );

        $jwtSignature = rtrim(
            strtr(base64_encode($signature), '+/', '-_'),
            '='
        );

        $jwt = $signatureInput . "." . $jwtSignature;

        $response = Http::asForm()->post(
            $serviceAccount['token_uri'],
            [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt
            ]
        );

        if (!$response->successful()) {
            return null;
        }

        return $response->json()['access_token'] ?? null;
    }

    /**
     * Send notification to one device
     */
public function sendNotification(
    string $fcm_token,
    string $title,
    string $message,
    array $data = []
) {
    $accessToken = $this->getAccessToken();

    if (!$accessToken) {
        \Log::error("FCM: Unable to generate Firebase access token.");

        return [
            'success' => false,
            'message' => 'Unable to get Firebase Access Token'
        ];
    }

    $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

    $payload = [
        "message" => [
            "token" => $fcm_token,

            "notification" => [
                "title" => $title,
                "body"  => $message
            ],

            "android" => [
                "priority" => "high",
                "notification" => [
                    "sound" => "default",
                    "channel_id" => "mini_job_hunt_notifications"
                ]
            ],

            "data" => array_merge([
                "title" => $title,
                "message" => $message
            ], $data)
        ]
    ];

    // Log request
    // \Log::info("========== FCM REQUEST ==========");
    // \Log::info("URL: " . $url);
    // \Log::info($payload);

    $response = Http::withToken($accessToken)
        ->acceptJson()
        ->post($url, $payload);

    // Log response
    // \Log::info("========== FCM RESPONSE ==========");
    // \Log::info("HTTP Status: " . $response->status());
    // \Log::info("Response Body: " . $response->body());

    if ($response->successful()) {
        return [
            'success' => true,
            'response' => $response->json()
        ];
    }

    return [
        'success' => false,
        'status' => $response->status(),
        'response' => $response->json(),
        'raw' => $response->body()
    ];
}
    /**
     * Send notification to multiple devices
     */
    public function sendMultipleNotifications(
        array $fcm_token,
        string $title,
        string $message,
        array $data = []
    ) {

        $results = [];

        foreach ($fcm_token as $token) {

            $results[] = $this->sendNotification(
                $token,
                $title,
                $message,
                $data
            );

        }

        return $results;
    }
}