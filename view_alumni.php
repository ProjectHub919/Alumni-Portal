<!DOCTYPE html>
<html lang="en">

<head>
    <title>ViewAlumni</title>
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
    } else if (!isset($type) || $type != "user") {
        echo "<script>alert('You must be a user to access this page.')</script>";
        echo "<script>location.replace('login.php');</script>";
        exit();
    }

    $alumniData = array();

    // Retrieve alumni data from the database, exclude admin type, and only include user with account status 'Approve'
    $sql = "SELECT user_table.*, account_table.type, account_table.status 
        FROM user_table
        JOIN account_table ON user_table.email = account_table.email
        WHERE user_table.email != '$email' AND account_table.type = '$type' AND account_table.status = 'Approve'";
        
    $result = mysqli_query($conn, $sql);

    // Fetching all the other user data baside the login user
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) 
        {
            $alumniData[] = $row;
        }
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

    <!-- Content Container -->
    <div class="container-fluid my-4 alum_width">
        <!-- Row of Cards -->
        <div class="row">
            <!-- Card -->
            <?php foreach ($alumniData as $alumni) : ?>
                <div class="col-lg-4 col-md-4 col-sm-6 d-flex justify-content-center mb-5">
                    <div class="card hover_border border_round lightblue_border zoom_in alum_card">
                        <div class="row p-3">
                            <!-- Left side with image -->
                            <div class="col-md-5 text-center">
                                <?php
                                // Check the gender and load the corresponding avatar
                                $gender = $alumni['gender'];
                                $defaultAvatarSrc = ($gender == 'Male') ? 'profile_images/male_avatar.png' : 'profile_images/female_avatar.png';

                                // Check if the alumni has a profile picture, and use the default picture if not
                                if (isset($alumni['profile_image']) && !empty($alumni['profile_image'])) {
                                    $profilePicture = $alumni['profile_image'];
                                } else {
                                    $profilePicture = $defaultAvatarSrc;
                                }

                                ?>
                                <!-- Timestamps for the purpose of forcing the browser to fetch the updated image. (Prevent cached image)-->
                                <img src="<?php echo $profilePicture . '?timestamp=' . time(); ?>" class="img-fluid img-thumbnail border-2 border border-dark alum_img" alt="Profile Picture">
                            </div>
                            <!-- Right side with text -->
                            <div class="col-md-7 d-flex align-items-center text_align">
                                <div class="card-body">
                                    <h5 class="card-title text-primary mb-0">
                                        <?php echo $alumni['first_name'] . ' ' . $alumni['last_name']; ?>
                                    </h5>
                                    <p class="card-text text-info">
                                        <?php echo $alumni['hometown']; ?>
                                    </p>
                                    <a href="profile_detail.php?email=<?php echo $alumni['email']; ?>" class="text-decoration-none stretched-link"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>