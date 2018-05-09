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

// custom data related stuff
$username = $_SESSION["username"];
$profile_image = "images/default_profile.png";
if (array_key_exists("image", $users[$username]) && file_exists($users[$username]["image"])) {
  $profile_image = $users[$username]["image"];
}

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
        // not require password
        document.getElementById("password").removeAttribute("required");
        document.getElementById("confirm").removeAttribute("required");

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
            /*
            var query = $("#regForm").serialize();
            var queryplus = query + "&oldusername=" + "<?php echo $username; ?>";
            console.log(queryplus);
            $.post("savechanges.php", queryplus, function(data) {
                //window.location = "main.php";
                if (data.error) {
                    $("#regError").text(data.error);
                    $("#regError").show();
                }
                else {
                    //$("#imgForm").submit();
                    window.location = "main.php";
                }
            }, "json");
            */

            // form data
            var form = $("form")[0]; // You need to use standard javascript object here
            var formData = new FormData(form);
            formData.append("oldusername", "<?php echo $username; ?>");
            console.log(form);

            $.ajax({
                url: "savechanges.php",
                data: formData,
                type: "POST",
                contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                processData: false, // NEEDED, DON'T OMIT THIS
                // ... Other options like success and etc
                success : function(data){
                  if (data.error) {
                      $("#regError").text(data.error);
                      $("#regError").show();
                  }
                  else {
                      //$("#imgForm").submit();
                      window.location = "main.php";
                  }
                }
            });

            // submit file
            //$("#imgForm").submit();
            //$("imgForm").submit(function( event ) {
              //console.log(event);
            //});

            return false;
        });

        // Check for the username
        $("#username").on("change", function() {
            // Hide the error
            $("#unavailError").hide();

            // Show the error if it is not available and not equal to old name
            if ($("#username").val() != "" && $("#username").val() != "<?php echo $username; ?>") {
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

        // preview image
        $("#imgupload").change(function(){
            readURL(this);
            //$("#imgForm").submit();
            //other uploading proccess [server side by ajax and form-data ]
        });
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $("#profilepic").attr("src", e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

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
        <h3><?php echo $username; ?>'s Profile</h3>
      </div>
    </div>

    <form enctype="multipart/form-data" method="post" action="upload.php" id="regForm"> 
    <div class="row">

      <div class="col-3 p-3 text-center">
        <div class="row justify-content-center">
          <img height="100" width="100" id="profilepic" class="img-responsive center-block" 
          src="<?php echo $profile_image;?>" alt="profile-image"/>
        </div>
        <div class="row justify-content-center">
          <p><small>Upload a different photo...</small></p>
        </div>
        <div class="row justify-content-center">

          <input class="upload" type="file" id="imgupload" name="imgfile" accept="image/*"/>

        </div>
      </div>

      <div class="col-9 p-3">
        <div class="row">
          <div class="col px-4">

            <!--form id="regForm"-->
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
                Leave the password fields below blank if there's no change in password
                <!--button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button-->
              </div>

              <div class="form-row">
                <div class="col form-group">
                  <label for="password">New Password</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text"><i class="fas fa-key"></i></div>
                    </div>
                    <input type="password" required class="form-control" id="password" name="password" placeholder="New Password" formnovalidate>
                  </div>
                </div>
                <div class="col form-group">
                  <label for="confirm">Confirm New Password</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text"><i class="fas fa-key"></i></div>
                    </div>
                    <input type="password" required class="form-control" id="confirm" name="confirm" placeholder="Confirm Password" formnovalidate>
                  </div>
                </div>
              </div>

              <div class="form-row pb-2">
                  <div id="regError" class="col form-group text-center text-danger" style="display: none">
                    <i class="fas fa-times"></i> <span>Error: please recheck the above info</span>
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
     
            <!--/form-->    

          </div>
        </div>
      </div>

    </div>
    </form>

  </div>
</body>
</html>
