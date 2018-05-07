<?php
header("content-type: text/xml");

echo "<?xml version=\"1.0\"?>\n";

// Read the XML file into a DOM structure
$xml = new DOMDocument();
$xml->preserveWhiteSpace = false; // remove whitespace nodes 
$xml->load("book.xml");

// Retrieve the GET request values
$language    = $_GET["language"];
$title       = $_GET["title"];
$image       = $_GET["imageAddress"];
$author      = $_GET["author"];
$catagory    = $_GET["catagory"];


// Show the error or add the new book
if ($error != null) {
    // Show the error
    echo "<error>" . $error . "</error>";
}
else {
    // Get the correct language
    $target = null;
    $languages = $xml->getElementsByTagName("language");
    foreach ($languages as $node) {
        if ($node->getAttribute("language") == $language) {
            $target = $node;
            break;
        }
    }

    // Add the new book
    $book = $xml->createDocumentFragment();
    $book->appendXML("<book catagory=\"$catagory\"><title>$title</title><author>$author</author><image>$image</image></book>");
    $target->appendChild($pokemon);

    $xml->save("book.xml");

    // Show success
    echo "<success/>";
}
?>
