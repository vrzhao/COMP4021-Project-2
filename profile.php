<?php
session_start();

// Check for the username session variable
if (!isset($_SESSION["username"])) {
    header("Location: signin.php");
    exit;
}

// Read the JSON file
$users = file_get_contents("users.json");
$users = json_decode($users, true);

// Validate the user
if (!array_key_exists($_SESSION["username"], $users)) {
    header("Location: signin.php");
    exit;
}

// autofill related stuff
$username = $_SESSION["username"];
//$fname = $_SESSION["firstname"];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <meta charset="utf-8">
    <meta name="viewport" 
          content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> 
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css">

    <script>
    $(document).ready(function() {

        // Validate and submit the form
        $("#regForm").on("submit", function() {
            // Check for the availability of the username
            if ($("#unavailError").css('display') != "none") {
                alert("Please try another username!");
                $("#username").focus();
                return false;
            }

            // Check the password and its confirmation
            if ($("#password").val() != $("#confirm").val()) {
                alert("Please enter a correct password!");
                $("#password").focus();
                return false;
            }

            // AJAX submit the form
            var query = $("#regForm").serialize();
            //console.log(query);
            $.post("registeruser.php", query, function(data) {

                if (data.error) {
                    $("#regError").text(data.error);
                    $("#regError").show();
                }
                else {
                    //$("#success").show();
                    //$("#regForm").hide();
                }
            }, "json");

            return false;
        });

        // Check for the username
        $("#username").on("change", function() {
            // Hide the error
            $("#unavailError").hide();

            // Show the error if it is not available
            if ($("#username").val() != "") {
                var query = "username=" + encodeURIComponent($("#username").val());
                $.getJSON("checkusername.php", query, function(data) {
                    if (data.available == "no")
                        $("#unavailError").show();
                    else 
                        $("#unavailError").hide();
                });
            }
        });

        // Go to the main page
        $("#mainButton").on("click", function() {
            window.location = "main.php";
        });
    });
    </script>

    <style type="text/css">
      .row-bordered {
        border-bottom: 1px solid #ccc;
        margin: 0 15px;
      }
      .upload {
        font-size: x-small;
      }

      #profilepic {
        border-radius: 50%;
      }

      p {
        margin-top: 1rem;
      }

      .alert {
        margin-top: 1rem;
      }
    </style>

</head>
<body class="bg-info p-5">
  <div class="container rounded bg-white" style="width: 60rem">
    <div class="row row-bordered">
      <div class="col p-3 text-left">
        <h3>Edit Profile</h3>
      </div>
    </div>

    <div class="row">

    <div class="col-3 p-3 text-center">
      <div class="row justify-content-center">
        <img height="100" width="100" id="profilepic" class="img-responsive center-block" 
        src="images/default_profile.png" alt="profile-image"/>
      </div>
      <div class="row justify-content-center">
        <p><small>Upload a different photo...</small></p>
      </div>
      <div class="row justify-content-center">
        <input class="upload" type="file" name="imgfile"/>
      </div>
    </div>

    <div class="col-9 p-3">
    <div class="row">
      <div class="col px-4">

        <form id="regForm">
          <div class="form-row">
            <div class="col-6">
              <label for="username">Username</label>
            </div>
          </div>
          <div class="form-row">
            <div class="col-6 form-group">
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-user"></i></div>
                </div>
                <input type="text" required class="form-control" id="username" name="username" placeholder="Username" value="<?php echo $username; ?>">
              </div>
            </div>
            <div id="unavailError" class="col-6 form-text text-danger" style="display: none">
              <small><i class="fas fa-times"></i> The username is not available.</small>
            </div>
          </div>
          <div class="form-row">
            <div class="col form-group">
              <label for="firstname">First Name</label>
              <input type="text" required class="form-control" id="firstname" name="firstname" placeholder="First Name" value="<?php echo $users[$username]["firstname"]; ?>">
            </div>
            <div class="col form-group">
              <label for="lastname">Last Name</label>
              <input type="text" required class="form-control" id="lastname" name="lastname" placeholder="Last Name" value="<?php echo $users[$username]["lastname"]; ?>">
            </div>
          </div>

          <div class="form-row">
            <div class="col-6 form-group">
              <label for="email">Email</label>
              <!--input type="email" required class="form-control" id="email" name="email" placeholder="Email"-->
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                </div>
                <input type="email" required class="form-control" id="email" name="email" placeholder="Email" value="<?php echo $users[$username]["email"]; ?>">
              </div>
            </div>
            <div class="col-6 form-group">
              <label for="company">Company/School</label>
              <input type="text" required class="form-control" id="company" name="company" placeholder="Company/School" value="<?php echo $users[$username]["company"]; ?>">
            </div>
          </div>

          <div class="alert alert-info alert-dismissible fade show" role="alert">
            Leave the password fields below blank if there's no change
            <!--button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button-->
          </div>

          <div class="form-row">
            <div class="col form-group">
              <label for="password">New Password</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-key"></i></div>
                </div>
                <input type="password" required class="form-control" id="password" name="password" placeholder="New Password">
              </div>
            </div>
            <div class="col form-group">
              <label for="confirm">Confirm New Password</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fas fa-key"></i></div>
                </div>
                <input type="password" required class="form-control" id="confirm" name="confirm" placeholder="Confirm Password">
              </div>
            </div>
          </div>

          <div class="form-row">
            <div class="col form-group text-right pt-2">
              <button type="submit" name="save" class="btn btn-primary"> Save Changes </button>
            </div>
            <div class="col form-group text-left pt-2">
              <button type="button" name="cancel" class="btn btn-secondary" onclick="window.location.replace('main.php')"> Cancel </button>
            </div>
          </div>

          <div class="form-row pb-2">
              <div id="regError" class="col form-group text-center text-danger" style="display: none">
                <i class="fas fa-times"></i> <span>Error: please recheck the above info</span>
              </div>
          </div>
        </form>        
      </div>
    </div>
    </div>
    </div>

  </div>

</body>
</html>
