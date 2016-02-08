<?php

namespace Test\Sainsburys\Gateway;

use Sainsburys\Gateway\HtmlGateway;

/**
 * Test the HtmlGateway.
 * The Gateway should be able to read a URL, parse the HTML and return product data.
 *
 * @package Sainsburys
 */
class HtmlGatewayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Stores a mock HttpScraper.
     *
     * @var HttpScraper $httpMock
     */
    protected $httpMock;

    /**
     * Set up the HttpScraper mock.
     */
    protected function setUp()
    {
        $this->httpMock = $this->getMock("\\Sainsburys\\Data\\HttpScraper");
    }

    /**
     * Test getting product data from valid HTML.
     *
     * Note: This is testing that HTML is parsed correctly.
     * Testing the actual scraper is something we will not implement
     * as to do so accurately, we need to run a local webserver.
     */
    public function testParseProducts()
    {
        $gateway = new HtmlGateway($this->httpMock);

        // Map return values to parameter values.
        // Note: for the unit test, we use the simplest possible HTML, to minimize
        // complexity when debugging.
        $map = array(
            array(
                "http://test.com/list",
                "<div class='product'>
                     <div class='productInfo'>
                         <a href='http://test.com/description'>
                             Title
                             <img src='test.png' alt=''>
                         </a>
                     </div>
                     <p class='pricePerUnit'>&pound;5.00</p>
                 </div>"
            ),
            array(
                "http://test.com/list2",
                "<div class='product'>
                     <div class='productInfo'>
                         <a href='http://test.com/description'>
                             Title
                             <img src='test.png' alt=''>
                         </a>
                     </div>
                     <p class='pricePerUnit'>&pound;5.00</p>
                 </div>
                 <div class='product'>
                     <div class='productInfo'>
                         <a href='http://test.com/description2'>
                             Another Title
                             <img src='test.png' alt=''>
                         </a>
                     </div>
                     <p class='pricePerUnit'>&pound;10.00</p>
                 </div>"
            ),
            array(
                "http://test.com/description",
                "<div id='information'>
                     <h3>Description</h3>
                     <div class='productText'>Description</div>
                     <h3>Stuff we don't want</h3>
                     <div class='productText'>Other stuff we don't want.</div>
                 </div>"
            ),
            array(
                "http://test.com/description2",
                "<div id='information'>
                     <h3>Description</h3>
                     <div class='productText'>Another Description</div>
                     <h3>Stuff we don't want</h3>
                     <div class='productText'>Other stuff we don't want.</div>
                 </div>"
            )
        );

        // This will be called twice, once to get list, once to get description.
        $this->httpMock
             ->expects($this->any())
             ->method('getHtml')
             ->will($this->returnValueMap($map));

        // Expected output from list1 is one product.
        // This should be an array within an array, ready to create Product objects.
        $expectedReturn = array(
            array(
                "title"       => "Title",
                "size"        => 281,
                "unit_price"  => 5.00,
                "description" => "Description"
            )
        );

        // Expected output from list2 is two products.
        // These should be in an array, ready to create Product objects.
        $expectedReturn2 = array(
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

        // Get both of the sets of product data.
        $productData  = $gateway->getProductData("http://test.com/list");
        $productData2 = $gateway->getProductData("http://test.com/list2");

        // Test the output.
        $this->assertEquals($expectedReturn, $productData);
        $this->assertEquals($expectedReturn2, $productData2);
    }

    /**
     * A source which isn't a URL should not be accepted.
     */
    public function testInvalidSource()
    {
        $this->setExpectedException("\\Sainsburys\\Exception\\InvalidDataSourceException");

        $gateway = new HtmlGateway($this->httpMock);

        $productData = $gateway->getProductData("not a url");
    }
}
