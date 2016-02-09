<?php

use Sainsburys\ProductFactory;
use Sainsburys\Data\HttpScraper;
use Sainsburys\Gateway\HtmlGateway;

define("APP_DIR", dirname(dirname(__FILE__)));

require_once APP_DIR."/vendor/autoload.php";

// Setup required objects.
$scraper = new HttpScraper();
$gateway = new HtmlGateway($scraper);
$factory = new ProductFactory();

// Load product data.
$products = $gateway->getProductData(
    "http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/5_products.html"
);

// Put it in a ProductList.
$list = $factory->createList($products);

$products = $list->getProducts(true);
$total    = 0;

// Calculate the total.
array_walk($products, function ($product) use (&$total) {
    $total += $product['unit_price'];
});

// Format the output.
$output = array(
    'results' => $products,
    'total'   => $total
);

// Print the output as JSON.
echo json_encode($output);
