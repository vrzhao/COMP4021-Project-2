<?php
header("content-type: text/xml");

// Read the XML file into a DOM structure
$xml = new DOMDocument();
$xml->preserveWhiteSpace = false; // remove whitespace nodes 
$xml->load("books.xml");

// Retrieve the GET request values
$language = $_GET["language"];
$category = $_GET["category"];

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

// Remove the non-matching categories
if ($category != null) {
    $categories = $xml->getElementsByTagName("book");
    for ($i = $categories->count() - 1; $i >= 0; $i--) {
        $node = $categories->item($i);
        if ($node->getAttribute("category") != $category) {
            $node->parentNode->removeChild($node);
        }
    }
}

echo $xml->saveXML();
?>
