<?php
    if ( !isset($_GET['id']) || empty($_GET['id']) ) {
		$error = "Invalid Post ID.";
	} else {
        $host = "";
        $user = "";
        $password = "";
        $db = "";
	}

    // ---- STEP 1: Establish the database connection
	$mysqli = new mysqli($host, $user, $password, $db);
	if ( $mysqli->connect_errno ) {
		echo $mysqli->connect_error;
		exit();
	}

	$mysqli->set_charset('utf8');

    $sql = "SELECT posts.title as title
                , posts.url as url
                , subreddits.name as subreddit
                , subreddits.subscribers
                , tags.name as tag 
            FROM posts 
	            LEFT JOIN subreddits ON posts.subreddits_id = subreddits.id 
                LEFT JOIN tags ON posts.tags_id = tags.id 
            WHERE posts.id = " . $_GET['id'] . ";";

    // echo $sql; 

    $results = $mysqli->query($sql);
	if ( !$results ) {
		echo $mysqli->error;
		exit();
	}

	// Since we only get 1 result (searching by primary key), we don't need a loop.
	$row = $results->fetch_assoc();

	$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

    
    <div class="container content">
        <!-- Heading -->
        <div class="row">
            <h1 class="col-12 mt-5 mb-4">Post Details</h1>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <table class="table table-responsive">

                    <tr>
                        <th class="text-right">Post Title:</th>
                        <td><?php echo $row['title']; ?></td>
                    </tr>

                    <tr>
                        <th class="text-right">Subreddit:</th>
                        <td><?php echo $row['subreddit']; ?></td>
                    </tr>

                    <tr>
                        <th class="text-right">Subscribers:</th>
                        <td><?php echo number_format($row['subscribers']); ?></td>
                    </tr>

                    <!-- <tr>
                        <th class="text-right">Tag:</th>
                        <td><?php echo $row['tag']; ?></td>
                    </tr> -->
                </table>
                <form action="update_tag_confirmation.php?id=<?php echo $_GET["id"] ;?>" method="get">
                    <div class="form-group row">
                        <label for="tag-id" class="col-3 col-sm-3 col-md-3 col-form-label text-sm-right">Tag:</label>
                        <div class="col-9 col-sm-9 col-md-9">
                            <input type="text" class="form-control" id="tag-id" name="tag" value="<?php echo $row['tag'];?>">
                            <input type="text" value="<?php echo $_GET['id']; ?>" name="id" hidden>
                            <input type="text" value="<?php echo $row['title']; ?>" name="title" hidden>
                        </div>
                    </div>

                    <div class="row mt-4 mb-4">
                        <div class="col-12">
                            <a href="results.php" role="button" class="btn btn-primary bg-dark">Back to Search Results</a>
                            <a href="<?php echo $row["url"]; ?>" role="button" class="btn btn-primary" target="_blank">Go to Post</a>
                            <button type="submit" class="btn btn-warning disabled" id="save-button">Save</button>
                            <a onclick="return confirm('Are you sure you want to delete this post?')" href="delete.php?id=<?php echo $_GET['id'] . "&title=". $row['title'];?>" role="button" class="btn btn-outline-danger delete-btn">
                                Delete
                            </a>
                        </div> <!-- .col -->
                    </div> <!-- .row -->

                </form>
                

            </div> <!-- .col -->
        </div> <!-- .row -->
        
        
    </div> <!-- .container -->

    <!-- Footer -->
    <footer class="py-3 my-4">
        <p class="text-center text-muted border-top pt-3">&copy; 2021 Randall Le</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
        crossorigin="anonymous">
    </script>
    <script src="main.js"></script>


</body>

</html>