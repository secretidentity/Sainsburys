<?php

namespace Sainsburys;

use Sainsburys\Product;
use Sainsburys\ProductList;
use Sainsburys\Exception\MalformedDataException;

/**
 * Factory to create Product & ProductList objects.
 *
 * @package Sainsburys
 */
class ProductFactory
{
    /**
     * Create a new Product.
     *
     * @param string $title       The Product title.
     * @param string $description The Product description.
     * @param float  $price       The Product price.
     * @param int    $size        Size of page in bytes.
     *
     * @throws MalformedDataException If data is wrongly typed.
     *
     * @return Product
     */
    public function create($title, $description, $price, $size)
    {
        if (false === is_string($title)) {
            throw new MalformedDataException("Title must be a string.");
        }

        if (false === is_string($description)) {
            throw new MalformedDataException("Description must be a string.");
        }

        if (false === is_int($size)) {
            throw new MalformedDataException("Size must be an integer.");
        }

        if (false === is_numeric($price)) { // An int should be accepted here.
            throw new MalformedDataException("Price must be numeric.");
        }

        return new Product($title, $description, $price, $size);
    }

    /**
     * Create a ProductList from input data retrieved from a Gateway.
     *
     * @param array $input
     *
     * @throws MalformedDataException If data is malformed.
     *
     * @return ProductList
     */
    public function createList(array $data)
    {
        $pl = new ProductList();

        array_walk($data, function($product) use ($pl) {
            if (false === array_key_exists('title', $product)) {
                throw new MalformedDataException("Product must have a title.");
            }

            if (false === array_key_exists('description', $product)) {
                throw new MalformedDataException("Product must have a description.");
            }

            if (false === array_key_exists('unit_price', $product)) {
                throw new MalformedDataException("Product must have a price.");
            }

            if (false === array_key_exists('size', $product)) {
                throw new MalformedDataException("Product must have a size.");
            }

            $product = $this->create(
                $product['title'],
                $product['description'],
                $product['unit_price'],
                $product['size']
            );

            $pl->addProduct($product);
        });

        return $pl;
    }
}
