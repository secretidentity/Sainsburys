<?php

namespace Test\Sainsburys;

use Sainsburys\Product;

/**
 * Test functions of Product.
 *
 * @package Sainsburys
 */
class ProductTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Product is a simple class.
     * Just test constructor for regressions.
     */
    public function testCreateProduct()
    {
        $product = new Product(
            'Title',
            'Description',
            5.00,
            200
        );

        // Should be obvious.
        $this->assertInstanceOf("\\Sainsburys\\Product", $product);

        // Make sure constructor order remains intact.
        $this->assertEquals($product->getTitle(), 'Title');
        $this->assertEquals($product->getDescription(), 'Description');
        $this->assertEquals($product->getPrice(), 5.00);
        $this->assertEquals($product->getSize(), 200);
    }
}
