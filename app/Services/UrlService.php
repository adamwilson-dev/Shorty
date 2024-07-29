<?php

namespace App\Services;

use App\Models\Url;
use Carbon\Carbon;

class UrlService
{
    /**
     * Creates the short URL entry in the database
     *
     * @param string $longUrl
     * @param string|null $expiryDate
     * @return Url
     */
    public function createShortUrl(string $longUrl, ?string $expiryDate): Url
    {
        // Generate short url part
        $shortUrlPart = $this->generateShortUrl();

        // Create the short url entry
        return Url::create([
            'long_url' => $longUrl,
            'short_url' => $shortUrlPart,
            'expiry_date' => !empty($expiryDate) ? Carbon::createFromFormat('Y-m-d H:i', $expiryDate) :
                null,
        ]);
    }

    /**
     * Generates unique short URL
     *
     * @return string
     */
    private function generateShortUrl(): string
    {
        // Generate the shortest URL, this is nested like this to support other URL formats later
        return $this->generateShortestUrl($this->getLastShortUrl());
    }

    /**
     * Gets the most recent short url from the database
     *
     * @return string|null
     */
    private function getLastShortUrl(): ?string {
        return Url::latest()->value('short_url');
    }

    /**
     * Generates a short, case-sensitive, alphanumeric text identifier.
     *
     * @param string|null $lastShortUrl
     * @return string
     */
    public function generateShortestUrl(?string $lastShortUrl): string
    {
        // Define the character set: lowercase & uppercase letters followed by digits
        $chars = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));

        // If no previous identifier is provided, start with 'a'
        if ($lastShortUrl === '' || $lastShortUrl === null) {
            return $chars[0];
        }

        // Extract the last character and the prefix of the previous identifier
        $lastChar = substr($lastShortUrl, -1);
        $prefix = substr($lastShortUrl, 0, -1);

        // Find the position of the last character in our character set
        $pos = $this->caseSensitiveArraySearch($lastChar, $chars);

        // If the last character is not in our set, start over from 'a'
        if ($pos === false) {
            // Invalid character, start over
            return $chars[0];
        }

        // If we're not at the end of our character set, move to the next character
        if ($pos < count($chars) - 1) {
            // Increment the last character
            return $prefix . $chars[$pos + 1];
        }

        // If we're at the end of the set, increment the prefix and append 'a'
        $newPrefix = $this->generateShortestUrl($prefix);

        return $newPrefix . $chars[0];
    }

    function caseSensitiveArraySearch($needle, array $haystack) {
        foreach ($haystack as $key => $item) {
            if ($item === $needle) {
                return $key;
            }
        }

        return false;
    }

    function getUrlByPath(string $shortUrl): ?Url
    {
        return Url::where('short_url', $shortUrl)
            ->first();
    }
}
