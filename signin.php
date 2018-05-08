<?php
session_start();

// Check for the username session variable
// temporarily commenting out due to "localhost redirected you too many times."
//if (isset($_SESSION["username"])) {
//    header("Location: main.php");
//    exit;
//}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Project 2: Sign-In</title>
    <meta charset="utf-8">
    <meta name="viewport" 
          content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> 
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css">
    <script>
    $(document).ready(function() {
        // Submit the form
        $("#signinForm").on("submit", function() {
            // AJAX submit the form
            var query = $("#signinForm").serialize();
            $.post("signinuser.php", query, function(data) {
                if (data.error) {
                    $("#signinError").text(data.error);
                    $("#signinError").show();
                }
                else
                    window.location = "main.php";
            }, "json");

            return false;
        });

        // Go to the register page
        $("#registerButton").on("click", function() {
            window.location = "register.html";
        });
    });
    </script>
</head>
<body class="bg-primary p-5">
  <div class="container rounded bg-white" style="width: 20rem">
    <div class="row">
      <div class="col p-3 text-center">
        <h4>Sign In</h4>
      </div>
    </div>
    <div class="row">
      <div class="col px-4">
        <form id="signinForm">
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-user"></i></div>
              </div>
              <input type="text" required class="form-control" id="username" name="username" placeholder="Username">
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-key"></i></div>
              </div>
              <input type="password" required class="form-control" id="password" name="password" placeholder="Password">
            </div>
          </div>
          <div class="g-recaptcha" data-sitekey="6LdslVcUAAAAAKkrGaqQT_y3E-HIpTa_dSNh4IF_" style="transform:scale(0.9);transform-origin:0;-webkit-transform:scale(0.9);
transform:scale(0.9);-webkit-transform-origin:0 0;transform-origin:0 0;"></div>
          <div class="form-group text-center">
            <button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt mr-2"></i> Sign In</button>
          </div>
          <div id="signinError" class="form-group text-center text-danger" style="display: none">
            <i class="fas fa-times"></i> <span>Sign in error!</span>
          </div>
          <hr>
          <div class="form-text text-center mb-2">
            Don't have an account with us?
          </div>
          <div class="form-group text-center pb-2">
            <button id="registerButton"
                    type="button" class="btn btn-info"><i class="fas fa-clipboard-list mr-2"></i> Register</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
