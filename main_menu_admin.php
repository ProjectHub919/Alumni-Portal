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
        //Check if user type is not an admin
    } else if (!isset($type) || $type != "admin") {
        echo "<script>alert('You must be an admin to access this page.')</script>";
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
    } else {
        echo "Error in the query: " . mysqli_error($conn);
    }
    // Close the result set
    mysqli_free_result($result);

    // Close the connection
    mysqli_close($conn);
    ?>

    <!-- Navigation Bar -->
    <?php include('admin_navigation.php') ?>
    <h2 class="text-center mt-3 appearing_word">Welcome, <?php echo $foundUserName ?></h2>

    <!-- Content Container -->
    <div class="container-fluid mt-3 main_width">
        <!-- Row of Cards -->
        <div class="row">
            <!-- Card 1 -->
            <div class="col-md-6 col-lg-4 mb-4 d-flex justify-content-center">
                <div class="card border_bottom_rounded hover_border menu_card">
                    <img src="img/manage_event.png" class="card-img-top" alt="Card 1">
                    <!-- Card Contents -->
                    <div class="card-body">
                        <h5 class="card-title">Events/News</h5>
                        <p class="card-text">Post and manage events/news to publish the latest updates</p>
                        <button class="btn btn-primary"><a href="manage_events.php" class="text-decoration-none text-white">Manage Events/News</a></button>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-6 col-lg-4 mb-4 d-flex justify-content-center">
                <div class="card border_bottom_rounded hover_border menu_card">
                    <img src="img/manage_account.png" class="card-img-top" alt="Card 2">
                    <!-- Card Contents -->
                    <div class="card-body">
                        <h5 class="card-title">User Accounts</h5>
                        <p class="card-text">Manage user account to bolster security measures</p>
                        <button class="btn btn-primary"><a href="manage_accounts.php" class="text-decoration-none text-white">Manage Accounts</a></button>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-md-6 col-lg-4 mb-4 d-flex justify-content-center">
                <div class="card border_bottom_rounded hover_border menu_card">
                    <img src="img/add_advertisement.jpg" class="card-img-top" alt="Card 1">
                    <!-- Card Contents -->
                    <div class="card-body">
                        <h5 class="card-title">Advertisements</h5>
                        <p class="card-text">The place where creation of Advertisements occurs</p>
                        <button class="btn btn-primary"><a href="add_advertisement.php" class="text-decoration-none text-white">Manage Advertisement</a></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>