<?php
header("content-type: text/xml");

// Read the XML file into a DOM structure
$xml = new DOMDocument();
$xml->preserveWhiteSpace = false; // remove whitespace nodes 
$xml->load("books.xml");

// Retrieve the GET request values
$title = $_GET["title"];

$list = $xml->getElementsByTagName("book");

$nodeToRemove = null;
foreach ($list as $domElement){
	$attribute = $domElement->getElementsByTagName("title");
	if ($attribute->nodeValue == $title) {
		$nodeToRemove = $domElement;
	}
}

$xml->removeChild($nodeToRemove);

echo $xml->saveXML();
?>