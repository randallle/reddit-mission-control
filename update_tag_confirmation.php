<?php
    // ---- STEP 1: Establish the database connection
    // Four pieces of required information
    $host = "";
    $user = "";
    $password = "";
    $db = "";

    // Attempt to connect to database
    $mysqli = new mysqli($host, $user, $password, $db);

    // Check to see if there was an error
    if($mysqli->connect_errno) {
        echo $mysqli->connect_error;
        exit();
    }
    
    // If tag does NOT exist in the database, insert it
    $sql = "SELECT * FROM tags WHERE name LIKE '%" . $_GET["tag"] . "%';";

    $results = $mysqli->query($sql);
    if(!$results) {
        echo $mysqli->error;
        exit();
    }

    if(mysqli_num_rows($results) == 0) {
        $insert_tag_sql = "INSERT INTO tags (name) VALUES ('" . $_GET["tag"] . "');";
        $insert_success = $mysqli->query($insert_tag_sql);
        if(!$insert_success) {
            echo $mysqli->error;
            exit();
        }
    }

    // Get the tag
    $results = $mysqli->query($sql);
    if(!$results) {
        echo $mysqli->error;
        exit();
    }

    $row = $results->fetch_assoc();
    $tag_id = $row["id"];

    // Update the tag for the given post id
    $update_post_sql = "UPDATE posts SET tags_id = ". $tag_id . " WHERE id = " . $_GET["id"]. ";";
    $results = $mysqli->query($update_post_sql);
    if(!$results) {
        $isUpdated = false;
        echo $mysqli->error;
        exit();
    } else {
        $isUpdated = true;
    }

    $mysqli->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container-xl">
            <a class="navbar-brand" href="search.php">Reddit Mission Control</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#toggleMobileMenu"
                aria-controls="toggleMobileMenu" aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="toggleMobileMenu">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="search.php">Search</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="save_post.php">Save a Post</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Visualize</a>
                    </li>
                </ul>

                <div class="text-end">
                    <a href="index.html" role="button" class="btn btn-light text-dark me-2">Log Out</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container content">
        <!-- Heading -->
        <div class="row">
            <h1 class="col-12 mt-5 mb-4">Update Tag Confirmation</h1>
            <?php if ( isset($error) && !empty($error) ) : ?>
				<div class="text-danger font-italic">
					There was an error
				</div>
			<?php endif; ?>
			
			<?php if ($isUpdated) : ?>
				<div class="text-success">
					<span class="font-italic">"<?php echo substr($_GET['title'], 0, 40) ?></span>..." was successfully updated.
				</div>
			<?php endif; ?>
        </div>

        <div class="row mt-4 mb-4">
			<div class="col-12">
				<a href="details.php?id=<?php echo $_GET['id']; ?>" role="button" class="btn btn-primary">Back to Details</a>
			</div> <!-- .col -->
		</div> <!-- .row -->
    </div>


    <!-- Footer -->
    <footer class="py-3 my-4">
        <p class="text-center text-muted border-top pt-3">&copy; 2021 Randall Le</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
        crossorigin="anonymous"></script>
    
</body>
</html>