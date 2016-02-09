<?php

namespace Test\Sainsburys;

use Sainsburys\Product;
use Sainsburys\ProductList;

/**
 * Test functions of ProductList.
 *
 * @todo Use Mockery to make these tests independent of working Product.
 *       PHPUnit mocks are less than ideal for this & add complexity.
 *
 * @package Sainsburys
 */
class ProductListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * ProductList.
     *
     * @var ProductList $pl
     */
    protected $pl;

    /**
     * Create the ProductList.
     * Add some products.
     */
    protected function setUp()
    {
        $this->pl = new ProductList();

        $this->pl->addProduct(new Product("First", "First Product", 2.50, 1000));
        $this->pl->addProduct(new Product("Second", "Second Product", 0.50, 2000));
        $this->pl->addProduct(new Product("Third", "Third Product", 10.00, 3550));
    }

    /**
     * Test getting products.
     */
    public function testGetProducts()
    {
        $products = $this->pl->getProducts();

        $this->assertEquals(
            $products,
            array(
                array(
                    "title"       => "First",
                    "description" => "First Product",
                    "unit_price"  => 2.50,
                    "size"        => 1000
                ),
                array(
                    "title"       => "Second",
                    "description" => "Second Product",
                    "unit_price"  => 0.50,
                    "size"        => 2000
                ),
                array(
                    "title"       => "Third",
                    "description" => "Third Product",
                    "unit_price"  => 10.00,
                    "size"        => 3550
                )
            )
        );
    }

    /**
     * Test getting products with doc size in kb.
     */
    public function testGetProductsSizeInKb()
    {
        $products = $this->pl->getProducts(true, false);

        $this->assertEquals(
            $products,
            array(
                array(
                    "title"       => "First",
                    "description" => "First Product",
                    "unit_price"  => 2.50,
                    "size"        => "1.00kb"
                ),
                array(
                    "title"       => "Second",
                    "description" => "Second Product",
                    "unit_price"  => 0.50,
                    "size"        => "2.00kb"
                ),
                array(
                    "title"       => "Third",
                    "description" => "Third Product",
                    "unit_price"  => 10.00,
                    "size"        => "3.55kb"
                )
            )
        );
    }

    /**
     * Test getting products with currency symbols.
     */
    public function testGetProductsPriceWithSigil()
    {
        $products = $this->pl->getProducts(false, true);

        $this->assertEquals(
            $products,
            array(
                array(
                    "title"       => "First",
                    "description" => "First Product",
                    "unit_price"  => "£2.50",
                    "size"        => 1000
                ),
                array(
                    "title"       => "Second",
                    "description" => "Second Product",
                    "unit_price"  => "£0.50",
                    "size"        => 2000
                ),
                array(
                    "title"       => "Third",
                    "description" => "Third Product",
                    "unit_price"  => "£10.00",
                    "size"        => 3550
                )
            )
        );
    }

    /**
     * Test getting quantity.
     */
    public function testGetQty()
    {
        $this->assertEquals($this->pl->getQty(), 3);
    }
}
