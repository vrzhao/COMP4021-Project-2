<?php
header("content-type: text/xml");

// Read the XML file into a DOM structure
$xml = new DOMDocument();
$xml->preserveWhiteSpace = false; // remove whitespace nodes 
$xml->load("books.xml");

// Retrieve the GET request values
$language = $_GET["language"];
$catagory = $_GET["catagory"];

// Remove the non-matching languages
if ($language != null) {
    $languages = $xml->getElementsByTagName("language");
    for ($i = $languages->count() - 1; $i >= 0; $i--) {
        $node = $languages->item($i);
        if ($node->getAttribute("language") != $language) {
            $node->parentNode->removeChild($node);
        }
    }
}

// Remove the non-matching catagories
if ($catagory != null) {
    $catagories = $xml->getElementsByTagName("book");
    for ($i = $catagories->count() - 1; $i >= 0; $i--) {
        $node = $catagories->item($i);
        if ($node->getAttribute("catagory") != $catagory) {
            $node->parentNode->removeChild($node);
        }
    }
}

echo $xml->saveXML();
?>
