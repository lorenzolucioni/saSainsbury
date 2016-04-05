<?php

require_once 'PageParserClass.php';
require_once 'ProductPageClass.php';
require_once 'ConsoleExceptionClass.php';

class ConsoleAppTest extends PHPUnit_Framework_TestCase
{
    public function testGetDomXPath()
    {
		$page = new ProductPageClass("http://www.google.com");
		$domXPath = $page->getDomXPath();
        $this->assertEquals(true, !empty($domXPath));

		$body = $domXPath->query('//body');	
        $this->assertEquals(1, $body->length);
    }

    public function testExec()
    {
		// create a stub for 'PageParserClass'
        $PageParserObj = $this->getMockBuilder('PageParserClass')
			->setConstructorArgs(array('http://www.google.com'))
			->setMethods(array('getListPage', 'getProductPage'))
			->getMock();

        // configure the stub for 'getListPage' and 'getProductPage'
		$html = @file_get_contents('listMock.html');
		libxml_use_internal_errors(true);
		$domDoc = new DOMDocument();
		$domDoc->loadHTML($html);
        $PageParserObj->method('getListPage')->willReturn(new DOMXpath($domDoc));
		$html = @file_get_contents('productMock.html');
		libxml_use_internal_errors(true);
		$domDoc = new DOMDocument();
		$domDoc->loadHTML($html);
		$PageParserObj->method('getProductPage')->willReturn(new DOMXpath($domDoc));
		libxml_use_internal_errors(false);

		// test 'exec' method
		$response = json_encode($PageParserObj->exec());
		$this->assertEquals('{"results":[{"title":"Sainsbury\'s Apricot Ripe & Ready x5","size":"38.3Kb","unit_price":"3.50","description":"Apricots"},{"title":"Sainsbury\'s Avocado Ripe & Ready XL Loose 300g","size":"38.7Kb","unit_price":"1.50","description":"Apricots"},{"title":"Sainsbury\'s Avocado, Ripe & Ready x2","size":"43.4Kb","unit_price":"1.80","description":"Apricots"},{"title":"Sainsbury\'s Avocados, Ripe & Ready x4","size":"38.7Kb","unit_price":"3.20","description":"Apricots"},{"title":"Sainsbury\'s Conference Pears, Ripe & Ready x4 (minimum)","size":"38.5Kb","unit_price":"1.50","description":"Apricots"},{"title":"Sainsbury\'s Golden Kiwi x4","size":"38.6Kb","unit_price":"1.80","description":"Apricots"},{"title":"Sainsbury\'s Kiwi Fruit, Ripe & Ready x4","size":"39Kb","unit_price":"1.80","description":"Apricots"}],"total":"15.10"}',
			$response);
    }
}

?>
