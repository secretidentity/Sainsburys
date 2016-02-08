<?php

namespace Sainsburys\Data;

use Sainsburys\Exception\HttpErrorException;

/**
 * Simple HTTP scraper.
 *
 * @package Sainsburys
 */
class HttpScraper
{
    /**
     * Get the contents of a URL.
     * Cannot depend on allow_url_include being enabled, so using curl.
     *
     * @param string $url The URL to read from.
     *
     * @return string The contents.
     *
     * @todo Handle timeouts, failed certs.
     */
    public function getHtml($url)
    {
        // Create a curl request.
        $r = curl_init();

        // Set the URL for the request.
        curl_setopt($r, CURLOPT_URL, $url);
        // We want the return value to be the content.
        curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
        // We want the request to follow 3XX redirects.
        curl_setopt($r, CURLOPT_FOLLOWLOCATION, 1);

        $data   = curl_exec($r);
        $status = curl_getinfo($r);

        if (1 === preg_match('/^2/', $status['http_code'])) { // If the request was successful.
            return $data;
        } else { // If the request failed, throw exception.
            throw new HttpErrorException("Error: ".$status['http_code']);
        }
    }
}
