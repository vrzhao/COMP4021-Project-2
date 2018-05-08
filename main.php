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
				$("#userPage").show();
                break;
			case "#signout":
				window.location = "signout.php";
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

						html += "<div><a class='edit' href=#>edit</a></div>";

						html += "<div><a class='delete' href=#>delete</a></div>";

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

		$("#listForm").on("edit", function() {
			var query = $("#listForm").serialize();

			$.get("edit.php", query, function(data) {
				
			});
		});

		$("#listForm").on("delete", function() {
			var query = $("#listForm").serialize();

			$.get("delete.php", query, function(data) {
				
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
        margin-right: 40px;
    }
    .container h2 {
        text-align: center;
    }
    .book {
        padding: 1em;
    }
    .book .name {
        text-align: center;
        font-size: 120%;
        font-weight: bold;
        color: darkgray;
    }
    .book .image {
        text-align: center;
    }
    .book .authors {
        text-align: center;
    }
    .book .author {
        font-size: 90%;
        font-weight: bold;
    }
    </style>
</head>
<body>
    <!-- Put your navbar here -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="#">
        <img src="book.svg" alt="">
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
            <a class="nav-link" href="#user">User Settings</a>
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
			  <option value="japanese">Japnese</option>
            </select>
          </div>
		  <div class="form-group col-6 col-md-4 col-lg-3">
            <label for="catagoryFilter">Catagory</label>
            <select required class="form-control" id="catagoryFilter" name="catagory">
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

      <!-- Add the form for a new Pokémon here -->
      <form id="addForm">
        <div class="form-group">
          <label for="Author">Author(s)</label>
          <input type="text" required multiple class="form-control" id="Author" name="Author" placeholder="Enter Author">
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
			<option value="japanese">Japnese</option>
		  </select>
		<div class="form-group">
          <label for="catagory">Catagory</label>
          <select required class="form-control" id="catagory" name="catagory">
            <option value="Cooking">Cooking</option>
            <option value="Children">Children</option>
            <option value="Fiction">Fiction</option>
            <option value="Non-Fiction">Non-Fiction</option>
		  </select>
        </div>
        <button type="submit" class="btn btn-primary">Add the Book</button>
      </form>
    </div>
</body>
</html>
