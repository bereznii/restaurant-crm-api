<?php

namespace App\Services\iiko;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class IikoClient
{
    public const API_URL = 'https://iiko.biz:9900/api/0';
    public const ORGANIZATION_ID_SMAKI = 'f445683a-adf7-11e9-80dd-d8d385655247';

    private const API_URL_ACCESS_TOKEN = '/auth/access_token';

    /** @var string|null */
    private ?string $accessToken;

    /** @var string|null  */
    private ?string $userId;

    /** @var string|null  */
    private ?string $userSecret;

    /**
     *
     */
    public function __construct()
    {
        $this->userId = config('iiko.smaki.user_id');
        $this->userSecret= config('iiko.smaki.user_secret');

        $this->accessToken = $this->requestAccessToken();
    }

    /**
     * @return string
     */
    private function requestAccessToken(): string
    {
        $response = Http::get(self::API_URL . self::API_URL_ACCESS_TOKEN, [
            'user_id' => $this->userId,
            'user_secret' => $this->userSecret,
        ]);

        return trim($response->body(), '"');
    }

    /**
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * @return string|null
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * @return string|null
     */
    public function getUserSecret(): ?string
    {
        return $this->userSecret;
    }
}
