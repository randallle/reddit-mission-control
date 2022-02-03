<?php
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
                        <a class="nav-link" href="search.php">Search</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="save_post.php">Save a Post</a>
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
            <h1 class="col-12 mt-5 mb-4">Save a Post</h1>
        </div>
        
        <form action="save_post_confirmation.php" method="GET">
            <div class="form-group row">
                <label for="title-id" class="col-sm-3 col-form-label text-sm-right">Title:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="title-id" name="title">
                </div>
            </div>

            <div class="form-group row pt-3">
                <label for="subreddit-id" class="col-sm-3 col-form-label text-sm-right">Subreddit:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="subreddit-id" name="subreddit">
                </div>
            </div> <!-- .form-group -->

            <div class="form-group row pt-3">
                <label for="url-id" class="col-sm-3 col-form-label text-sm-right">URL:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="url-id" name="url">
                </div>
            </div> <!-- .form-group -->

            <div class="form-group row pt-3">
                <label for="tag-id" class="col-sm-3 col-form-label text-sm-right">Tag:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="tag-id" name="tag">
                </div>
            </div>

            <div class="form-group row pt-3">
                <div class="col-sm-3"></div>
                <div class="col-sm-9 mt-2">
                    <button type="submit" class="btn btn-primary bg-dark">Search</button>
                    <button type="reset" class="btn btn-light">Reset</button>
                </div>
            </div> <!-- .form-group -->
        </form>
        
        
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