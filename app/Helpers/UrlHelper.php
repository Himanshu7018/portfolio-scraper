<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class UrlHelper
{
    public static function isLinkActive($url)
    {
        $cacheKey = 'url_status_' . md5($url);

        $status = Cache::get($cacheKey);

        if (is_null($status)) {
            $headers = @get_headers($url);
            $status = $headers && strpos($headers[0], '200') !== false;
            Cache::put($cacheKey, $status, now()->addMinutes(10));
        }

        return $status;
    }

    public static function checkUrls(array $urls)
    {
        $client = new Client(['timeout' => 5]); // Timeout set to 5 seconds per request
        $promises = [];

        foreach ($urls as $url) {
            $promises[$url] = $client->headAsync($url);
        }

        $results = \GuzzleHttp\Promise\Utils::settle($promises)->wait();

        $statuses = [];
        foreach ($results as $url => $result) {
            $statuses[$url] = $result['state'] === 'fulfilled' && $result['value']->getStatusCode() === 200;
        }

        return $statuses;
    }
}
