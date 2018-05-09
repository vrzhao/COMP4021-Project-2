<?php
header("content-type: text/xml");

// Read the XML file into a DOM structure
$xml = new DOMDocument();
$xml->preserveWhiteSpace = false; // remove whitespace nodes 
$xml->load("books.xml");

// Retrieve the GET request values
$title = $_GET["title"];


function validateFields() {
    global $xml, $title;

    // Check if the title exists
    $titles = $xml->getElementsByTagName("title");
    foreach ($titles as $node) {
        if ($node->nodeValue == trim($title)) {
            return True;
        }
    }

    return "Book doesn't exist!";
}

// Validate the title
$error = validateFields();

// Show the error or delete the book
if ($error != True) {
    // Show the error
    echo "<error>" . $error . "</error>";
}
else {
	$books = $xml->getElementsByTagName("book");
    foreach ($books as $book) {
        if ($book[0]->firstChild->nodeValue == $title) {
            $target = $book;
            break;
        }
    }

	$xml->removeChild($book);

	$xml->saveXML("books.xml");

    // Show success
    echo "<success/>";
}
?>