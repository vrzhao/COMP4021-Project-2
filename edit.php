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
    global $xml, $number, $name;

    // Check if the number has been taken
    $pokemons = $xml->getElementsByTagName("pokemon");
    foreach ($pokemons as $node) {
        if (intval($node->getAttribute("num")) == intval($number)) {
            return "National number already exists!";
        }
    }

    // Check if the name has been taken
    $names = $xml->getElementsByTagName("name");
    foreach ($names as $node) {
        if ($node->nodeValue == trim($name)) {
            return "Name already exists!";
        }
    }

    return null;
}

// Validate the two fields: national number and name
$error = validateFields();

// Show the error or add the new pokemon
if ($error != null) {
    // Show the error
    echo "<error>" . $error . "</error>";
}
else {
    // Get the correct generation
    $target = null;
    $generations = $xml->getElementsByTagName("generation");
    foreach ($generations as $node) {
        if ($node->getAttribute("num") == $generation) {
            $target = $node;
            break;
        }
    }

    // Add the new Pokemon
    $pokemon = $xml->createDocumentFragment();
    $typeNode = "<types>";
    foreach ($types as $type) $typeNode .= "<type>$type</type>";
    $typeNode .= "</types>";
    $pokemon->appendXML("<pokemon num=\"$number\"><name>$name</name><image>$image</image>$typeNode</pokemon>");
    $target->appendChild($pokemon);

    $xml->save("pokemon.xml");

    // Show success
    echo "<success/>";
}
?>
