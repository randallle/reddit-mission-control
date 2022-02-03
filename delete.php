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
    
    // Delete the post
    $sql = "DELETE FROM posts WHERE id=" . $_GET['id'] . ";";

    $results = $mysqli->query($sql);
    if(!$results) {
        echo $mysqli->error;
        exit();
        $isDeleted = false;
    } else {
        $isDeleted = true;
    }

    $mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletion Confirmation</title>
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
            <h1 class="col-12 mt-5 mb-4">Deletion Confirmation</h1>
            <?php if ( isset($error) && !empty($error) ) : ?>
				<div class="text-danger font-italic">
					There was an error
				</div>
			<?php endif; ?>
			
			<?php if ($isDeleted) : ?>
				<div class="text-success">
					<span class="font-italic">"<?php echo substr($_GET['title'], 0, 40) ?></span>..." was successfully deleted.
				</div>
			<?php endif; ?>
        </div>

        <div class="row mt-4 mb-4">
			<div class="col-12">
				<a href="results.php" role="button" class="btn btn-primary">Back to Search Results</a>
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