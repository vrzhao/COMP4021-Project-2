<?php
// Read the JSON file
$users = file_get_contents("users.json");
$users = json_decode($users, true);

// Check the username and password
if (array_key_exists($_POST["username"], $users) &&
    $users[$_POST["username"]]["password"] == $_POST["password"]) {

    // Set up the session
    session_start();
    $_SESSION["username"] = $_POST["username"];

    $output["success"] = "";
}
else
    $output["error"] = "Username/password is not correct! Please input again.";

header("content-type: application/json");

print json_encode($output);
?>
