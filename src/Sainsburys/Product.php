<?php

namespace Sainsburys;

/**
 * Product class.
 * Enforces nothing as it should always be created with the ProductFactory.
 *
 * @package Sainsburys
 */
class Product
{
    /**
     * The product title.
     *
     * @var string $title
     */
    protected $title;

    /**
     * The product description.
     *
     * @var string $description
     */
    protected $description;

    /**
     * The product price.
     *
     * @var float $price
     */
    protected $price;

    /**
     * The size of the product page, in bytes.
     *
     * @var int $size
     */
    protected $size;

    /**
     * Constructor.
     *
     * @param string $title       The product title.
     * @param string $description The product description.
     * @param float  $price       The product price.
     * @param int    $size        The size of the product page, in bytes.
     */
    public function __construct($title, $description, $price, $size)
    {
        $this->title       = (string) $title;
        $this->description = (string) $description;
        $this->price       = (float)  $price;
        $this->size        = (int)    $size;
    }

    /**
     * Get the product title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the product description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the product price.
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get the size of the product page, in bytes.
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }
}
