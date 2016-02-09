<?php

namespace Test\Sainsburys;

use Sainsburys\ProductFactory;

/**
 * Test the ProductFactory creating Product & ProductList objects.
 *
 * @todo Add failure tests for all possible failure cases.
 *
 * @package Sainsburys
 */
class ProductFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * ProductFactory instance.
     *
     * @var ProductFactory $pf
     */
    protected $pf;

    /**
     * Create a ProductFactory.
     */
    protected function setUp()
    {
        $this->pf = new ProductFactory();
    }

    /**
     * Test creating a single product.
     */
    public function testCreateProduct()
    {
        $product = $this->pf->create("Title", "Description", 5.00, 1000);

        $this->assertInstanceOf("\\Sainsburys\\Product", $product);
        $this->assertEquals($product->getTitle(), "Title");
        $this->assertEquals($product->getDescription(), "Description");
        $this->assertEquals($product->getPrice(), 5.00);
        $this->assertEquals($product->getSize(), 1000);
    }

    /**
     * Test creating a product with wrong-typed title.
     */
    public function testCreateProductWrongTypedTitle()
    {
        $this->setExpectedException("\\Sainsburys\\Exception\\MalformedDataException");

        $this->pf->create(array(2), "Description", 4.00, 1000);
    }

    /**
     * Test creating a ProductList from Gateway-esque output.
     */
    public function testCreateProductList()
    {
        $input = array(
            array(
                "title"       => "Title",
                "size"        => 281,
                "unit_price"  => 5.00,
                "description" => "Description"
            ),
            array(
                "title"       => "Another Title",
                "size"        => 289,
                "unit_price"  => 10.00,
                "description" => "Another Description"
            )
        );

        $productList = $this->pf->createList($input);

        $this->assertInstanceOf("\\Sainsburys\\ProductList", $productList);
        $this->assertEquals($productList->getQty(), 2);
    }
}
