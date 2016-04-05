<?php
/**
 * PageParserClass.php
 * 
 * This file contains classes related to the Page Parser.
 *
 * @author Lorenzo Lucioni <lorenzo.lucioni@gmail.com>
 * @version 1.0
 * @package first
 */

/**
 * PageParserClass, main page parser.
 * @package first
 */
class PageParserClass {
	public $totalPrice = 0;
	public $url;	

    /**
     * Constructor sets up {@link $url}
     */
	function __construct($url) {
		$this->url = $url;
	}

	/**
	 * this function returns generic page dom-xpath.
	 * @return DOMXpath 
	 */
	protected function getPage($url) {
		$listPage = new ProductPageClass($url);
		$domXPath = $listPage->getDomXPath();
		return $domXPath;
	}

	/**
	 * this function returns the products list
	 * page dom-xpath.
	 * @return DOMXpath 
	 */
	protected function getListPage() {
		return $this->getPage($this->url);
	}

	/**
	 * this function returns the product page
	 * dom-xpath.
	 * @return DOMXpath 
	 */
	protected function getProductPage($url) {
		return $this->getPage($url);
	}

	/**
	 * this function parses specific product page
	 * and returns the product description.
	 * @return DOMElement 
	 */
	protected function getProductDescription($productPageUrl) {
		$domDetailXPath = $this->getProductPage($productPageUrl);
		$informationRoot = new DOMDocument();
		$informationCtx = $informationRoot->getElementById('information');
		return $domDetailXPath->query('//div[@class="productText"]/p', $informationCtx);
	}

	/**
	 * main parser method.
	 * This function parses the list page and follow 
     * any product item subpage. It returns the whole 
	 * data expected ('results' and 'total')
	 * @return array 
	 */
	public function exec() {
		// the whole response
		$responseData = array();
		// product-list page DOM x-path
		$domXPath = $this->getListPage();
		// pre-parsing selecting the productLister 'ul' tag
		$productNodes = $domXPath->query('//ul[contains(@class, "productLister")]/li');

		foreach($productNodes as $node) { // products loop
			// product (node) title and detail-page url
			$productData = $domXPath->query('.//div[contains(@class, "productInfoWrapper")]/div/h3/a', $node);
			$productTitle = trim($productData[0]->nodeValue);
			$productPageUrl = $productData[0]->getAttribute('href');

			// product price available
			$productPrice = $domXPath->query('.//p[@class="pricePerUnit"]', $node);
			// cleaning price of currency and other stuff
			preg_match('/([0-9]*\.?[0-9]+)\//' , $productPrice[0]->nodeValue, $productPrice);
			$productPrice = $productPrice[1];

			// increasing total price
			$this->totalPrice += (float)$productPrice;

			// product description found out by specific method
			$productDesc = $this->getProductDescription($productPageUrl);

			// last response headers, useful to get page size
			$headers = get_headers($productPageUrl);
			$size = substr(current(preg_grep('/Content-Length/', $headers)), strlen('Content-Length: '));

			// product item details
			$responseData[] = array(
				'title' => $productTitle,
				'size' => (floatval(sprintf("%.1f", $size/1024))).'Kb',
				'unit_price' => $productPrice,
				'description' => $productDesc[0]->nodeValue
			);
		}
		return array('results' => $responseData, 'total' => sprintf("%1.2f", $this->totalPrice));
	}
}

?>
