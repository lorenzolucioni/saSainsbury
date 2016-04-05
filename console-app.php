<?php

require_once 'PageParserClass.php';
require_once 'ProductPageClass.php';
require_once 'ConsoleExceptionClass.php';

$url = 'http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/5_products.html';
try {
	$pageParser = new PageParserClass($url);
	$response = json_encode($pageParser->exec());
} catch (ConsoleExceptionClass $e) {
	echo "Console Error: ".$e->getCustomMessage()."\n";
	exit(-1);
} catch (Exception $e) {
	echo "Fatal Error: ".$e->getMessage()."\n";
	exit(-1);
}

echo $response;

?>
