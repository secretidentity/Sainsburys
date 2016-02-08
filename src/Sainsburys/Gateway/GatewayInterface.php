<?php

namespace Sainsburys\Gateway;

/**
 * Gateway interface.
 * The purpose of a Gateway is to access raw data and turn it into something PHP
 * can parse, in this case, an array.
 *
 * @package Sainsburys
 */
interface GatewayInterface
{
    /**
     * Given a source (can be a URL, DSN, file location etc.), retrieve raw data
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
    public function getProductData($source);
}
