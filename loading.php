<?php
    // Function: removeEmoji()
    // Purpose: Some subreddit titles have emojis, we gotta ignore them
    function removeEmoji($text) {
        $text = iconv('UTF-8', 'ISO-8859-15//IGNORE', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        return iconv('ISO-8859-15', 'UTF-8', $text);
    }

    // Import the reddit cred variables
    require("reddit-creds.php");

    // Define the Reddit API endpoints
    define("REDDIT_ACCESS_TOKEN_ENDPOINT", "https://www.reddit.com/api/v1/access_token");
    define("REDDIT_ME_ENDPOINT", "https://oauth.reddit.com/api/v1/me");

    // Grab the code from query parameter after the user authenticates
    $code = $_GET["code"];


    // ********** GET ACCESS TOKEN **********
    // Set required parameters for POST request
    $data = array(
        "grant_type" => "authorization_code",
        "code" => $code,
        "redirect_uri" => $redirect_uri
    );

    // Set header information
    // We are using basic auth here, so must encode the string "client_id:client_secret"
    $headers = [
        "Authorization: Basic " . base64_encode("bPoEUEw6UHTLnhYHRHzjEw:QS6YSYchZw8eIEPMFaeNmv1q_aNAWQ")
    ];

    // Use cURL to make POST request 
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, REDDIT_ACCESS_TOKEN_ENDPOINT);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    // Get access_token from response
    $response = curl_exec($curl);
    $response = json_decode($response, true);

    // This is what is returned when there is an error, after decoding
    // array(1) { ["error"]=> string(13) "invalid_grant" }
    $access_token = $response["access_token"];
    define("ACESS_TOKEN", $access_token);

    // echo $access_token;

    // ********** GET USER NAME **********
    // There are no required parameters, so no need to set $data

    // Set header information
    $headers = [
        "Authorization: Bearer " . $access_token, 
        "User-Agent: Reddit Mission Control"
    ];

    // Make GET request
    $curl_me = curl_init();
    curl_setopt($curl_me, CURLOPT_URL, REDDIT_ME_ENDPOINT);
    curl_setopt($curl_me, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_me, CURLOPT_HTTPHEADER, $headers);

    // Get username
    $response = curl_exec($curl_me);
    $response = json_decode($response, true);
    // var_dump($response);
    $username = $response["name"];

    // ********** GET SAVED POSTS **********
    $reddit_saved_endpoint = "https://oauth.reddit.com/user/" . $username . "/saved";
    
    $curl_saved = curl_init();
    curl_setopt($curl_saved, CURLOPT_URL, $reddit_saved_endpoint);
    curl_setopt($curl_saved, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_saved, CURLOPT_HTTPHEADER, $headers);

    // Get saved posts
    $response = curl_exec($curl_saved);
    $response = json_decode($response, true);

    $saved_posts = $response["data"]["children"];
    
    // Populate the SQL database here

    // ---- STEP 1: Establish the database connection
    // Four pieces of required information
    $host = "";
    $user = "";
    $password = "";
    $db = "";

    // Attempt to connect to database
    $ymsqli = new mysqli($host, $user, $password, $db);

    // Check to see if there was an error
    if($mysqli->connect_errno) {
        echo $mysqli->connect_error;
        echo "<hr>";
        echo "There was an error connecting to sql";
        exit();
    }

    $mysqli->set_charset('utf8');


    // First, clear the database before populating it
    // Delete posts table records
    $delete_posts_sql = "DELETE FROM posts;";
    $results = $mysqli->query($delete_posts_sql);
    if(!$results) {
        echo $mysqli->error;
        exit();
    }

    // Delete subreddits table records
    $delete_subreddits_sql = "DELETE FROM subreddits;";
    $results = $mysqli->query($delete_subreddits_sql);
    if(!$results) {
        echo $mysqli->error;
        exit();
    }


    foreach($saved_posts as $post) {
        // 1. Prepare SQL query with placeholders

        // If subreddit does NOT exist in the database, insert it
        $sql = "SELECT * FROM subreddits WHERE name LIKE '%" . $post["data"]["subreddit"] . "%';";
        $results = $mysqli->query($sql);
        if(!$results) {
            echo $mysqli->error;
            exit();
        }

        if(mysqli_num_rows($results) == 0) {
            $insert_subreddit_sql = "INSERT INTO subreddits (name, subscribers) VALUES ('" . $post["data"]["subreddit"] . "' , " . $post["data"]["subreddit_subscribers"] . ");";
            // echo $insert_subreddit_sql;
            $insert_success = $mysqli->query($insert_subreddit_sql);
            if(!$insert_success) {
                echo $mysqli->error;
                exit();
            }
        } 
        
        // Get the subreddit_id
        $results = $mysqli->query($sql);
        if(!$results) {
            echo $mysqli->error;
            exit();
        }
        while($row = $results->fetch_assoc()) {
            $subreddit_id = $row["id"];
        }

        // Insert post to posts table
        // echo "<p>" . mysqli_real_escape_string($mysqli, $post["data"]["title"]) . "</p>";
        $insert_post_sql = "INSERT INTO posts (title, subreddits_id, url, tags_id) VALUES ('" . removeEmoji(mysqli_real_escape_string($mysqli, $post["data"]["title"])) . "' , " . $subreddit_id . ", '" . $post["data"]["url"] . "' , NULL);";
        // echo $insert_post_sql;
        $results = $mysqli->query($insert_post_sql);
        if(!$results) {
            echo $mysqli->error;
            exit();
        }
        

        //$statement = $mysqli->prepare("INSERT INTO posts (title, label_id, sound_id, genre_id, rating_id, format_id, award) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        // echo "<p>subreddit: " . $post["data"]["subreddit"] . "</p>";
        // echo "<p>title: " . $post["data"]["title"] . "</p>";
        // echo "<p>subscribers: " . $post["data"]["subreddit_subscribers"] . "</p>";
        // echo "<p>url: " . $post["data"]["url"] . "</p>";
        // echo "<hr>";
    }
    

    // // 2. Bind variables to the placeholders
    // $statement->bind_param("ssiiiiis", $_POST['title'], $release_date_id, $label_id, $sound_id, $genre_id, $rating_id, $format_id, $award_id);

    // // 3. Execute the query
    // $executed = $statement->execute();
    // // If there is an error, $executed will return null
    // if(!$executed) {
    //     echo $mysqli->error;
    // }
    // //echo $sql;

    $mysqli->close();

    // Move to search screen
    // https://code.tutsplus.com/tutorials/how-to-redirect-with-php--cms-34680

    header("refresh:1;url=search.php" );

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading Saved Posts...</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
            <h1 class="display-1 text-center">Loading your saved posts...<hr style="border: 2px solid black;"></h1>
            
            <h1 class="display-4 text-center">Please wait</h1> 
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
        crossorigin="anonymous"></script>
</body>
</html>