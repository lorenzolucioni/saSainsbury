<?php
/**
 * ProductPageClass.php
 * 
 * This file contains Business Objects Classes.
 *
 * @author Lorenzo Lucioni <lorenzo.lucioni@gmail.com>
 * @version 1.0
 * @package first
 */

require_once 'ConsoleExceptionClass.php';

/**
 * ProductPageClass, the Business Class involved
 * @package first
 */
class ProductPageClass {
	protected $streamCtx;
	public $html;

    /**
     * Constructor loads the page ({@link $url}) and sets up {@link $html}
     */
	function __construct($url, $timeout = 30) {
		$this->streamCtx = stream_context_create(array('http' => array('timeout' => $timeout)));
		$this->html = $this->load($url);
	}

	/**
	 * this function loads the whole page and returns its html source
	 * @param string $url main page url to load
	 * @return string 
	 */
	protected function load($url) {
		try {
			$html = @file_get_contents($url, 0, $this->streamCtx);
		} catch (Exception $e) {
			// TODO custom exception class for better exception handling
			// get contents error handling
			throw new ConsoleExceptionClass("Page loading failed");
		}
		if ($html === false) {
			// TODO custom exception class for better exception handling
			// get contents error handling
			throw new ConsoleExceptionClass("Page loading failed");
		}
		return $html;
	}

	/**
	 * this function creates and returns the dom-x-path
	 * related to this product page.
	 * @return DOMXpath 
	 */
	public function getDomXPath() {
		$domDoc = new DOMDocument();
		// to suppress malformed html warnings, not critical for this job
		libxml_use_internal_errors(true);
		if (empty($this->html)) {
			// TODO custom exception class for better exception handling
			throw new ConsoleExceptionClass("Empty page source");
		}
		$domDoc->loadHTML($this->html);
		libxml_use_internal_errors(false);
		return new DOMXpath($domDoc);
	}
} 

?>
