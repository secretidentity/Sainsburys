<?php

use Sainsburys\ProductFactory;
use Sainsburys\Data\HttpScraper;
use Sainsburys\Gateway\HtmlGateway;

define("APP_DIR", dirname(dirname(__FILE__)));

require_once APP_DIR."/vendor/autoload.php";

$scraper = new HttpScraper();
$gateway = new HtmlGateway($scraper);
$factory = new ProductFactory();

$products = $gateway->getProductData(
    "http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/5_products.html"
);

$list = $factory->createList($products);

echo json_encode($list->getProducts(true, true));
