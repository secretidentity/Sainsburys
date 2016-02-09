<?php

namespace Sainsburys;

use Sainsburys\Product;

/**
 * ProductList holds Product objects, allows export to various formats (JSON).
 *
 * @package Sainsburys
 */
class ProductList
{
    /**
     * Products array
     *
     * @var array $products
     */
    protected $products = array();

    /**
     * Add a Product to the ProductList.
     *
     * @param Product $product
     */
    public function addProduct(Product $product)
    {
        $this->products[] = $product;
    }

    /**
     * Get the products in a formatted array.
     *
     * @param bool $sizeInKb       Format size in kilobytes instead of bytes.
     * @param bool $priceWithSigil Add a £ sign before the prices (string return)/
     *
     * @return array
     */
    public function getProducts($sizeInKb = false, $priceWithSigil = false)
    {
        $products = array();

        array_walk($this->products, function($product) use ($sizeInKb, $priceWithSigil, &$products) {
            $p = array();

            $p['title']       = $product->getTitle();
            $p['description'] = $product->getDescription();

            if (true === $priceWithSigil) {
                $p['unit_price'] = "£".number_format($product->getPrice(), 2);
            } else {
                $p['unit_price'] = $product->getPrice();
            }

            if (true === $sizeInKb) {
                $p['size'] = number_format($product->getSize() / 1000, 2)."kb";
            } else {
                $p['size'] = $product->getSize();
            }

            $products[] = $p;
        });

        return $products;
    }

    /**
     * Get the quantity of products stored in the list.
     *
     * @return int
     */
    public function getQty()
    {
        return count($this->products);
    }
}
