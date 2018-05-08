<?php
// Set functionality for 'Remember Me'
if($_POST['remember']) {
    setcookie('user_memory', $_POST['username'], time()+31540000);
}
elseif(!$_POST['remember']) {
	if(isset($_COOKIE['user_memory'])) {
		setcookie(user_memory, '', time()-100);
	}
}

// Read the JSON file
$users = file_get_contents("users.json");
$users = json_decode($users, true);

// reCAPTCHA verification
$response =  $_POST["g-recaptcha-response"];
$secret = "6LdslVcUAAAAAC7HGIHBiLLhwySw5EK_o7Yaauy3";
$url = "https://www.google.com/recaptcha/api/siteverify";
$verify = file_get_contents($url."?secret=".$secret."&response=".$response);
$result = json_decode($verify);

// Check the username and password
if (array_key_exists($_POST["username"], $users) &&
    $users[$_POST["username"]]["password"] == $_POST["password"] && $result->success) {

    // Set up the session
    session_start();
    $_SESSION["username"] = $_POST["username"];

    $output["success"] = "";
} elseif (!$result->success) {
    $output["error"] = "reCAPTCHA verification error!";
} else
    $output["error"] = "Username/password is not correct! Please input again.";

header("content-type: application/json");

print json_encode($output);
?>
