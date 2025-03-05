<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class UrlHelper
{
    /**
     * Ensure the URL has a protocol (http:// if missing).
     *
     * @param string $url
     * @return string
     */
    protected static function normalizeUrl($url)
    {
        if (!preg_match('/^https?:\/\//i', $url)) {
            return 'http://' . $url;
        }
        return $url;
    }

    /**
     * Check if a single URL is active using fsockopen.
     * It attempts to open a connection to the host.
     * Returns true if connection is successful, false otherwise.
     *
     * @param string $url
     * @return bool
     */
    public static function isLinkActive($url)
    {
        $normalized = self::normalizeUrl($url);
        $cacheKey = 'url_status_' . md5($normalized);
        $status = Cache::get($cacheKey);

        if (is_null($status)) {
            $parsed = parse_url($normalized);
            if (!$parsed || !isset($parsed['host'])) {
                $status = false;
            } else {
                $host = $parsed['host'];
                // Determine port: use 443 for https, otherwise 80.
                $port = isset($parsed['port']) ? $parsed['port'] : ((isset($parsed['scheme']) && strtolower($parsed['scheme']) === 'https') ? 443 : 80);
                // Try to open a socket connection (5 seconds timeout)
                $fp = @fsockopen($host, $port, $errno, $errstr, 5);
                $status = ($fp !== false);
                if ($fp) {
                    fclose($fp);
                }
            }
            Cache::put($cacheKey, $status, now()->addMinutes(10));
        }

        return $status;
    }

    /**
     * Batch check multiple URLs.
     * Loops through each URL and returns an associative array mapping original URL => bool status.
     *
     * @param array $urls
     * @return array
     */
    public static function checkUrls(array $urls)
    {
        $statuses = [];
        foreach ($urls as $url) {
            $statuses[$url] = self::isLinkActive($url);
        }
        return $statuses;
    }
}