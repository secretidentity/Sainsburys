<?php

namespace Sainsburys\Gateway;

use FluentDOM\Document as DOMParser;
use Sainsburys\Data\HttpScraper;
use Sainsburys\Exception\InvalidDataSourceException;
use Sainsburys\Exception\MalformedDataException;
use Sainsburys\Gateway\GatewayInterface;

/**
 * Gateway to retrieve data from URLs.
 *
 * @package Sainsburys
 */
class HtmlGateway implements GatewayInterface
{
    /**
     * Errors from previous call.
     * We don't want to print errors to page, can use this to log.
     *
     * @var array $errors
     */
    protected $errors;

    /**
     * HttpScraper object to scrape web with.
     *
     * @var HttpScraper $scraper
     */
    protected $scraper;

    /**
     * Constructor.
     * Takes a HttpScraper object to read HTTP with.
     *
     * @param HttpScraper $scraper
     */
    public function __construct(HttpScraper $scraper)
    {
        $this->scraper = $scraper;
    }

    /**
     * Given a source (URL), retrieve raw data
     * and turn it into an array of arrays of product data.
     *
     * Each sub array should contain the following keys:
     *  - title (string)     - the title of the product
     *  - size (int)         - bytes of raw data
     *  - unit_price (float) - the price of the product
     *  - description        - description of the product
     *
     * @param string $source Information about where to load data from.
     *
     * @throws InvalidDataSourceException If the source is invalid.
     * @throws MalformedDataException     If the data is malformed.
     *
     * @return array
     */
    public function getProductData($source)
    {
        // We will save errors to an array.
        libxml_use_internal_errors(true);

        if (false === $this->looksLikeUrl($source)) {
            throw new InvalidDataSourceException("This isn't a URL.");
        }

        // Create a DOM parser & load the HTML.
        $dom      = new DOMParser();
        $listHtml = $this->scraper->getHtml($source);
        $return   = array();

        $dom->loadHTML($listHtml);

        foreach ($dom->querySelectorAll("div.product") as $product) {
            // Get the title element & price element
            $titleElement = $product->querySelector("div.productInfo")
                                    ->querySelector("a");
            $priceElement = $product->querySelector("p.pricePerUnit");

            // Load the product-specific page.
            $productUrl  = $titleElement->getAttributeNode("href")->textContent;
            $productHtml = $this->scraper->getHtml($productUrl);
            $productDom  = new DOMParser();
            $productDom->loadHTML($productHtml);

            // Get the product description.
            // Note: this does not have a unique ID, this is very volatile.
            $descriptionElement = $productDom->querySelector("div#information")
                                             ->querySelector("div.productText");

            // Set up the product data.
            $productData                = array();
            $productData['title']       = trim($titleElement->textContent);
            $productData['description'] = trim($descriptionElement->textContent);
            $productData['size']        = strlen($productHtml);
            $productData['unit_price']  = (float) preg_replace(
                '/[^0-9.]/',
                '',
                $priceElement->textContent
            );

            $return[] = $productData;
        }

        // Put errors in $this->errors.
        $this->errors = libxml_get_errors();
        libxml_clear_errors();

        return $return;
    }

    /**
     * Get LibXML errors.
     * This is useful for logging.
     *
     * @return array<LibXMLError>
     */
    public function getLibXMLErrors()
    {
        return $this->errors;
    }

    /**
     * Quick validation check to make sure this looks like a URL.
     * Avoids unnecessary expensive network requests.
     *
     * @param string $url The URL to validate.
     *
     * @return bool
     */
    protected function looksLikeUrl($url)
    {
        return (1 === preg_match(
            '/^https?\:\/\//',
            $url
        ));
    }
}
