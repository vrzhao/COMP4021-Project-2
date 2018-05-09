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

// additional data
$email = trim($_POST["email"]);
$company = trim($_POST["company"]);
$oldusername = trim($_POST["oldusername"]);

// Check the username
if ($username != $oldusername && array_key_exists($username, $users)) {
    $output["error"] = "Duplicate username exists!";
}

// Check all fields
elseif (empty($username) || empty($firstname) || empty($lastname) || empty($email) || empty($company)) {
    $output["error"] = "Not all data has been submitted!";
}

// Check all fields
elseif ($password != $confirm) {
    $output["error"] = "Passwords do not match!";
}

// Add the user
else {

    // change username only if it's been modified
    if ($username != $oldusername) {
        $users[$username] = $users[$oldusername];
        unset($users[$oldusername]);
    }

    // FILE UPLOAD, ignore if no file upload
    if (file_exists($_FILES["imgfile"]["tmp_name"])) {
        if ($_FILES && 
            is_uploaded_file($_FILES["imgfile"]["tmp_name"])) {
            if (!move_uploaded_file(
                    $_FILES["imgfile"]["tmp_name"],
                    "images/".$_FILES["imgfile"]["name"]
                )) {
                $output["error"] = "Failed to move uploaded file successfully."; 
            }

            // ADD TO USER JSON
            $users[$username]["image"] = "images/".$_FILES["imgfile"]["name"];
        }
        else {
            $output["error"] = "File is not properly uploaded.";
        }
    }

    // Add the user to the JSON object and save it
    $users[$username]["firstname"] = $firstname;
    $users[$username]["lastname"] = $lastname;

    // change password only if fields were not empty
    if (!empty($password)) {
        $users[$username]["password"] = $password;
    }

    // additional data
    $users[$username]["email"] = $email;
    $users[$username]["company"] = $company;

    file_put_contents("users.json", json_encode($users, JSON_PRETTY_PRINT));

    // Set up the session
    session_start();
    $_SESSION["username"] = $username;

    $output["success"] = "";
}

print json_encode($output);
?>
