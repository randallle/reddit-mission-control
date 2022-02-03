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

    // ---- STEP 2: Generate, submit SQL query, and check for errors
    $sql = "SELECT posts.id, posts.title as title, subreddits.name as subreddit, tags.name as tag
    FROM posts
        LEFT JOIN subreddits ON posts.subreddits_id = subreddits.id
        LEFT JOIN tags ON posts.tags_id = tags.id
    WHERE 1=1";

    // Check if user typed a search term for a post title
    if( isset($_GET['title']) && !empty($_GET['title']) ) {
        $sql = $sql . " AND posts.title LIKE '%" . $_GET['title'] . "%' ";
    }

    // Check if user selected a subreddit
    if( isset($_GET['subreddit']) && !empty($_GET['subreddit']) ) {
        $sql = $sql . " AND subreddits.id = " . $_GET['subreddit'];
    }

    $sql = $sql . ";";

    $results = $mysqli->query($sql);
    if(!$results) {
        echo $mysqli->error;
        exit();
    }

    $mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search - Reddit Mission Control</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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

    <!-- Heading -->
    <div class="container">
        <div class="row">
            <h1 class="col-12 mt-4 mb-4">Search Results</h1>
        </div>
    </div>

    <div class="container content">
        <div class="row mb-4">
			<div class="col-12 mt-4">
				<a href="search.php" role="button" class="btn btn-primary bg-dark">Back to Form</a>
			</div> <!-- .col -->
		</div> <!-- .row -->

        <div class="col-12">
            <table class="table table-hover table-responsive mt-4">
                <thead>
                    <tr>
                        <th style="width:75%">Title</th>
                        <th style="width:12.5%">Subreddit</th>
                        <th style="width:12.5%">Tag</th>
                    </tr>
                </thead>
                <tbody>	
                    <!-- Display results here -->
                    <?php while($row = $results->fetch_assoc()):?>
                        <tr>
                            <td>
                                <a href="details.php?id=<?php echo $row['id']; ?>">
                                    <?php echo $row['title']; ?>
                                </a>
                            </td>
                            <td><?php echo $row['subreddit']?></td>
                            <td><?php echo $row['tag']?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div> <!-- .col -->
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