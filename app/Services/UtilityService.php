<?php

namespace App\Services;

use App\Models\Url;
use Illuminate\Support\Facades\Request;

class UtilityService
{
    /**
     * Gets the full hostname, including the protocol and any port
     *
     * @return Url
     */
    public function getHost(): string
    {
        // Construct the URL with scheme, host, and port if it exists
        $scheme = Request::getScheme();
        $host = Request::getHost();
        $port = Request::getPort();

        $url = $scheme . '://' . $host;
        if (($scheme === 'http' && $port !== 80) || ($scheme === 'https' && $port !== 443)) {
            $url .= ':' . $port;
        }

        return $url;
    }

    /**
     * Gets the path part of the current URL
     *
     * @return string
     */
    public function getUrlPath(): string
    {
        $path = trim(Request::getPathInfo(), '/');
        $query = Request::getQueryString();

        return $query ? $path . '?' . $query : $path;
    }
}
