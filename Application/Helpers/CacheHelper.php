<?php

namespace Application\Helpers;

use System\Core\Config;

class CacheHelper
{
    public const USER_PROFILE_KEY = 'user%s_profile_info';

    public static function forget(string $key, ?int $id = null): void
    {
        $key = sprintf($key, $id);
        $config = Config::get('Application');
        $apiKey = $config->API['api_key'];
        $baseURL = $config->api_base_url;
        $url = "{$baseURL}/api/cache/{$key}/forget";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => [
                'api-key: ' . $apiKey
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
    }
}