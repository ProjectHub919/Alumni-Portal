<!DOCTYPE html>
<html lang="en">

<head>
    <title>MainMenu</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Web development">
    <meta name="keywords" content="HTML, CSS, JavaScript">
    <meta name="author" content="Yien Yang CHOO">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="style/style.css" rel="stylesheet" />
</head>

<body>
    <?php
    // Set the servername, username, and password
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Alumni";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    //Session Start
    session_start();

    //Retrieve From Session
    $loginStatus = isset($_SESSION["loginStatus"]) ? $_SESSION["loginStatus"] : null;
    $email = isset($_SESSION["email"]) ? $_SESSION["email"] : null;
    $type = isset($_SESSION["type"]) ? $_SESSION["type"] : null;

    //Check if not login, return to login page
    if (!isset($loginStatus) || $loginStatus !== true) {
        echo "<script>alert('Please login to your account.')</script>";
        echo "<script>location.replace('login.php');</script>";
        exit();
    //Check if user type is not a user
    }else if(!isset($type) || $type != "user"){
        echo "<script>alert('You must be a user to access this page.')</script>";
        echo "<script>location.replace('login.php');</script>";
        exit();
    }

    // Find the user name based on the user's email
    $foundUserName = "";
    $sql = "SELECT first_name, last_name FROM user_table WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $foundFirstName = $row['first_name'];
        $foundLastName = $row['last_name'];
    
        $foundUserName = $foundFirstName . ' ' . $foundLastName;
    }else{
        echo "Error in the query: " . mysqli_error($conn);
    }
    // Close the result set
    mysqli_free_result($result);

    // Close the connection
    mysqli_close($conn);
    ?>

    <!-- Navigation Bar -->
    <?php include('navigation.php') ?>
    <h2 class="text-center mt-3 slide_in">Welcome, <?php echo $foundUserName ?></h2>

    <!-- Content Container -->
    <div class="container-fluid mt-3 main_width">
        <!-- Row of Cards -->
        <div class="row">
            <!-- Card 1 -->
            <div class="col-md-6 col-lg-4 mb-4 d-flex justify-content-center">
                <div class="card border_bottom_rounded hover_border menu_card">
                    <img src="img/social_image.jpg" class="card-img-top" alt="Card 1">
                    <!-- Card Contents -->
                    <div class="card-body">
                        <h5 class="card-title">Alumni Friends</h5>
                        <p class="card-text">Expand your network. Reconnect with your alma mater</p>
                        <button class="btn btn-primary"><a href="view_alumni.php" class="text-decoration-none text-white">View Alumni</a></button>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-6 col-lg-4 mb-4 d-flex justify-content-center">
                <div class="card border_bottom_rounded hover_border menu_card">
                    <img src="img/social_event.jpg" class="card-img-top" alt="Card 2">
                    <!-- Card Contents -->
                    <div class="card-body">
                        <h5 class="card-title">Events/News</h5>
                        <p class="card-text">Keep an eye out below for our evolving list of events</p>
                        <button class="btn btn-primary"><a href="view_events.php" class="text-decoration-none text-white">View Events/News</a></button>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-md-6 col-lg-4 mb-4 d-flex justify-content-center">
                <div class="card border_bottom_rounded hover_border menu_card">
                    <img src="img/advertisement_photo.jpeg" class="img-fluid card-img-top" alt="Card 3">
                    <!-- Card Contents -->
                    <div class="card-body card_padding">
                        <h5 class="card-title">Advertisements</h5>
                        <p class="card-text">Access exclusive job listings, workshops, seminars to nurture your professional growth</p>
                        <button class="btn btn-primary"><a href="view_advertisements.php" class="text-decoration-none text-white">View Advertisements</a></button>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-md-6 col-lg-4 mb-4 d-flex justify-content-center">
                <div class="card border_bottom_rounded hover_border menu_card">
                    <img src="img/profile_picture.jpg" class="card-img-top" alt="Card 4">
                    <!-- Card Contents -->
                    <div class="card-body">
                        <h5 class="card-title">Update Profile</h5>
                        <p class="card-text">Highlight your latest professional achievements and relevant skills</p>
                        <button class="btn btn-primary"><a href="update_profile.php" class="text-decoration-none text-white">View Profile</a></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>