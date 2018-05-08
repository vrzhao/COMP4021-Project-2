<?php
header("content-type: application/json");

// Read the JSON file
$users = file_get_contents("users.json");
$users = json_decode($users, true);

// Check the username and make the output
if (array_key_exists($_GET["username"], $users))
    $output["available"] = "no";
else
    $output["available"] = "yes";

print json_encode($output);
?>

