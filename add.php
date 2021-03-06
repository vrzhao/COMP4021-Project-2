<?php
header("content-type: text/xml");

echo "<?xml version=\"1.0\"?>\n";

// Read the XML file into a DOM structure
$xml = new DOMDocument();
$xml->preserveWhiteSpace = false; // remove whitespace nodes 
$xml->load("books.xml");

// Retrieve the GET request values
$language    = $_GET["language"];
$title       = $_GET["title"];
$image       = $_GET["imageAddress"];
$author      = $_GET["author"];
$category    = $_GET["category"];

function validateFields() {
    global $xml, $title;

    // Check if the title has been taken
    $titles = $xml->getElementsByTagName("title");
    foreach ($titles as $node) {
        if ($node->nodeValue == trim($title)) {
            return "Name already exists!";
        }
    }

    return null;
}

// Validate the title
$error = validateFields();


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
    $book->appendXML("<book category=\"$category\"><title>$title</title><author>$author</author><image>$image</image></book>");
    $target->appendChild($book);

    $xml->save("books.xml");

    // Show success
    echo "<success/>";
}
?>