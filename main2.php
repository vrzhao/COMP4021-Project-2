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

$username = $_SESSION["username"];
$profile_image = "images/default_profile.png";
if (array_key_exists("image", $users[$username]) && file_exists($users[$username]["image"])) {
  $profile_image = $users[$username]["image"];
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>bookstore Database</title>
    <meta charset="utf-8">
    <meta name="viewport" 
          content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> 
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css">
    <script>
    $(document).ready(function() {
        $(window).on('hashchange', function() {
            var page = window.location.hash;
            if (page == "") page = "#list";


            $(".page").hide();

            switch (page) {
              case "#list":
                $("#listPage").show();
                break;
              case "#add":
                $("#addPage").show();
                break;
              case "#user":
                window.location = "profile.php";
                break;
              case "#signout":
                window.location = "signout.php";
                break;
              case "#edit":
                $("#editPage").show();
                break;
              case "#delete":
                //referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
                //document.getElementById("demo");
                $("#deletePage").show();
                break;
            }
          });

        $(window).trigger("hashchange");

        $("#listForm select").on("change", function() {
            var query = $("#listForm").serialize();

            $.get("list.php", query, function(data) {
                var html = "<div class='row'>";
                $(data).find("language").each(function(i, language) {
                    $(language).find("book").each(function(i, book) {
                        book = $(book);

                        html += "<div class='book col-4 col-md-3 col-lg-2'>";

                        if (book.find("image").text() != "") {
                            html += "<div class='image'><img src='" + book.find("image").text() + "' class='w-75' alt='Image'></div>";
                        }

                        html += "<div class='title'>" + book.find("title").text() + "</div>";

            						html += "<div class='author'>" + book.find("author").text() + "</div>";

            						html += "<div class='edit'>";

            						html += "<span><a href='#edit'>Edit</a></span>";

            						html += "		";

            						html += "<span><a href='#delete'>Delete</a></span>";

            						html += "</div>";

                        html += "</div>";
                    });
                });
                html += "</div>";
                $("#listContent").html(html);
            })
                .fail(function() {
                    alert("Unknown error!");
                });
        });

		$("#deleteForm").on("submit", function() {
			$.get("delete.php", {title: $titletext} , function(data) {
				if ($(data).find("error").length) {
                    alert($(data).find("error").text());
                }
				else
					window.location.hash = "#list";
			})
                .fail(function() {
                    alert("Unknown error!");
                });
        });

        $("#listForm select:first").trigger("change");

        $("#addForm").on("submit", function() {
            var query = $("#addForm").serialize();

            $.get("add.php", query, function(data) {
                if ($(data).find("error").length) {
                    alert($(data).find("error").text());
                }
                else
                    window.location.hash = "#list";
            })
                .fail(function() {
                    alert("Unknown error!");
                });

            return false;
        });

		$("#logout").on("click", function() {
            window.location = "signout.php";
        });

    });

    $(document).click(function(event) {
        if ($(event.target).text()=="Delete") {
                  console.log($(event.target).text());

          $titletext = $(event.target).parent().parent().parent().children().eq(1).text();
          $authortext = $(event.target).parent().parent().parent().children().eq(2).text();
          console.log($titletext);
          console.log($authortext);

          $("#deleteBookTitle").text("Title: "+$titletext);
          $("#deleteBookAuthor").text("Author: "+$authortext);
        }
    });

    </script>
    <style>
    .navbar {
        margin: 1em;
        border: 2px darkgray solid;
        border-radius: 1em;
        background:
    }
    .navbar-brand img {
        height: 80px;
        margin-right: 20px;
    }
    .container h2 {
        text-align: center;
    }
    .book {
        padding: 1em;
    }
    .book .title {
        text-align: center;
        font-size: 120%;
        font-weight: bold;
        color: darkgray;
    }
    .book .image {
        text-align: center;
    }
    .book .author {
		text-align: center;
        font-size: 90%; 
        font-weight: bold;
    }
	.book .edit{
		text-align: center;
    }

    #profilepic {
        border-radius: 50%;
        margin: auto;
      }
    </style>
</head>
<body>
    <!-- Put your navbar here -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="#">
        <img height="80" width="80" id="profilepic" src="<?php echo $profile_image;?>" alt="">
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="#list">List Books</a>
          </li>            
          <li class="nav-item">
            <a class="nav-link" href="#add">Add Books</a>
          </li>
		  <li class="nav-item">
            <a class="nav-link" href="#user">Edit Profile</a>
          </li>
		  <li class="nav-item">
            <a class="nav-link" href="#signout">Sign Out</a>
          </li>
        </ul>
      </div>
    </nav>

    <!-- This is the listing page -->
    <div id="listPage" class="container page pb-3" style="display: none">
      <h2>Book List</h2>

      <form id="listForm">
        <div class="form-row">
          <div class="form-group col-6 col-md-4 col-lg-3 offset-md-2 offset-lg-3">
            <label for="languageFilter">Language</label>
            <select required class="form-control" id="languageFilter" name="language">
              <option value="">- All -</option>
			  <option value="english">English</option>
			  <option value="chinese">Chinese</option>
			  <option value="german">German</option>
			  <option value="italian">Italian</option>
			  <option value="french">French</option>
			  <option value="korean">Korean</option>
			  <option value="japanese">Japanese</option>
            </select>
          </div>
		  <div class="form-group col-6 col-md-4 col-lg-3">
            <label for="categoryFilter">Category</label>
            <select required class="form-control" id="categoryFilter" name="category">
              <option value="">- All -</option>
              <option value="Cooking">Cooking</option>
              <option value="Children">Children</option>
              <option value="Fiction">Fiction</option>
              <option value="Non-Fiction">Non-Fiction</option>
            </select>
          </div>
        </div>
      </form>
      <div id="listContent">
      </div>
    </div>

    <!-- This is the adding page -->
    <div id="addPage" class="container page pb-3" style="display: none">
      <h2>Adding a New Book</h2>

      <!-- Add the form for a new book here -->
      <form id="addForm">
        <div class="form-group">
          <label for="author">Author</label>
          <input type="text" required class="form-control" id="author" name="author" placeholder="Enter author">
        </div>
        <div class="form-group">
          <label for="title">Book Title</label>
          <input type="text" required class="form-control" id="title" name="title" placeholder="Enter book title">
        </div>
        <div class="form-group">
          <label for="imageAddress">Image address</label>
          <input type="url" required class="form-control" id="imageAddress" name="imageAddress" placeholder="Enter image address">
        </div>
        <div class="form-group">
          <label for="language">Language</label>
          <select required class="form-control" id="language" name="language">
           <option value="english">English</option>
           <option value="chinese">Chinese</option>
           <option value="german">German</option>
           <option value="italian">Italian</option>
           <option value="french">French</option>
           <option value="korean">Korean</option>
           <option value="japanese">Japanese</option>
         </select>
         <div class="form-group">
          <label for="category">Category</label>
          <select required class="form-control" id="category" name="category">
            <option value="Cooking">Cooking</option>
            <option value="Children">Children</option>
            <option value="Fiction">Fiction</option>
            <option value="Non-Fiction">Non-Fiction</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Add the Book</button>
      </form>
    </div>
  </div>

    <!-- This is the delete page -->
    <div id="deletePage" class="container page pb-3" style="display: none">
      <h2 id="deleteMessage">Are you sure you want to delete this book?</h2>
      <div class="row">
        <h5 id="deleteBookTitle">Title: </h6>
      </div>
      <div class="row">
        <h5 id="deleteBookAuthor">Author: </h6>
      </div>
      <div class="row">
        <form id="deleteForm">
          <button type="submit" class="btn btn-primary">Yes</button>
		  <button href="#list" class="btn btn-primary">No</button>
        </form>
      </div>
    </div>
</body>
</html>
