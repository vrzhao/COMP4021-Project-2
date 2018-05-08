<?php
// Declare that you output JSON content
header("Content-Type: application/json");

// Read the JSON file
$users = file_get_contents("users.json");
$users = json_decode($users, true);

// Get and trim the input fields if necessary
$username = trim($_POST["username"]);
$firstname = trim($_POST["firstname"]);
$lastname = trim($_POST["lastname"]);
$password = $_POST["password"];
$confirm = $_POST["confirm"];

// reCAPTCHA verification
$response =  $_POST["g-recaptcha-response"];
$secret = "6LdslVcUAAAAAC7HGIHBiLLhwySw5EK_o7Yaauy3";
$url = "https://www.google.com/recaptcha/api/siteverify";
$verify = file_get_contents($url."?secret=".$secret."&response=".$response);
$result = json_decode($verify);

// Check the username
if (array_key_exists($username, $users)) {
    $output["error"] = "Duplicate username exists!";
}

// Check all fields
elseif (empty($username) || empty($firstname) || empty($lastname) || empty($password)) {
    $output["error"] = "Not all data has been submitted!";
}

// Check all fields
elseif ($password != $confirm) {
    $output["error"] = "Passwords do not match!";
}

// check recaptcha
elseif (!$result->success) {
    $output["error"] = "reCAPTCHA verification error!";
} 

// Add the user
else {
    // Add the user to the JSON object and save it
    $users[$username]["firstname"] = $firstname;
    $users[$username]["lastname"] = $lastname;
    $users[$username]["password"] = $password;

    file_put_contents("users.json", json_encode($users, JSON_PRETTY_PRINT));

    // Set up the session
    session_start();
    $_SESSION["username"] = $username;

    $output["success"] = "";
}

print json_encode($output);
?>
