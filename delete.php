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
            return null;
        }
    }

    return "Book doesn't exist!";
}

// Validate the title
$error = validateFields();

// Show the error or delete the book
if ($error != null) {
    // Show the error
    echo "<error>" . $error . "</error>";
}
else {
	$books = $xml->getElementsByTagName("book");
    foreach ($books as $book) {
        if ($book->firstChild->nodeValue == trim($title)) {
            $target = $book;
            break;
        }
    }

	$target->parentNode->removeChild($book);

	$xml->save("books.xml");

    // Show success
    echo "<success/>";
}
?>