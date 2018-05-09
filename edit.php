<?php
header("content-type: text/xml");

echo "<?xml version=\"1.0\"?>\n";

// Read the XML file into a DOM structure
$xml = new DOMDocument();
$xml->preserveWhiteSpace = false; // remove whitespace nodes 
$xml->load("books.xml");

// Retrieve the GET request values

$oldTitle    = $_GET["oldTitle"];

$language    = $_GET["editlanguage"];
$title       = $_GET["edittitle"];
$image       = $_GET["editimageaddress"];
$author      = $_GET["editauthor"];
$category    = $_GET["editcategory"];

function validateFields() {
    global $xml, $title;

    // Check if the title has been taken
    $titles = $xml->getElementsByTagName("title");
    foreach ($titles as $node) {
        if ($node->nodeValue == trim($title) &&  $node->nodeValue != trim($oldTitle)) {
            return "Name already exists!";
        }
    }

    return null;
}

// Validate the title
$error = validateFields();

// Show the error or edit the book
if ($error != null) {
    // Show the error
    echo "<error>" . $error . "</error>";
}
else {
	// delete the old book
	$removeTarget = null;
	$removeBooks = $xml->getElementsByTagName("book");
    foreach ($removeBooks as $removeBook) {
        if ($removeBook->firstChild->nodeValue == trim($oldTitle)) {
            $removeTarget = $removeBook;
            break;
        }
    }

	if ($removeTarget != null) {
		$removeTarget->parentNode->removeChild($removeTarget);
	}

	// add in the new one
    $addTarget = null;
    $addLanguages = $xml->getElementsByTagName("language");
    foreach ($addLanguages as $node) {
        if ($node->getAttribute("language") == $language) {
            $addTarget = $node;
            break;
        }
    }

    // Add the new book
    $addBook = $xml->createDocumentFragment();
    $addBook->appendXML("<book category=\"$category\"><title>$title</title><author>$author</author><image>$image</image></book>");
    $addTarget->appendChild($addBook);

    $xml->save("books.xml");

    // Show success
    echo "<success/>";
}
?>
