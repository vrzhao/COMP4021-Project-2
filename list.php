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
        if ($node->getAttribute("lang") != $language) {
            $node->parentNode->removeChild($node);
        }
    }
}

// Remove the non-matching catagories
/*if ($category != null) {
    $categories = $xml->getElementsByTagName("book");
    for ($i = $categories->count() - 1; $i >= 0; $i--) {
        $node = $categories->item($i);
        if ($node->getAttribute("category") != $book) {
            $node->parentNode->removeChild($node);
        }
    }
}*/
if ($category != null) {
    $books = $xml->getElementsByTagName("book");
    for ($i = $books->count() - 1; $i >= 0; $i--) {
        $node = $books->item($i);

        $found = false;
        $categories = $node->getElementsByTagName("category");
        foreach ($categories as $categoryNode) {
            if ($categoryNode->nodeValue == $category) $found = true;
        }
        if (!$found) {
            $node->parentNode->removeChild($node);
        }
    }
}
echo $xml->saveXML();
?>
