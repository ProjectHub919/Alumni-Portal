<!DOCTYPE html>
<html lang="en">

<head>
    <title>ViewProfile</title>
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

    // Check if the email parameter exists in the URL
    if (isset($_GET['email'])) {
        $profileEmail = $_GET['email'];

        // Check if the profile email matches the session email
        if ($profileEmail == $email) {
            echo "<script>alert('You can\'t view your profile here. Please view your own profile at the update profile page.')</script>";
            echo "<script>location.replace('view_alumni.php');</script>";
            exit();
        }
    }

    $alumniData = array();

    // Retrieve alumni data from the database, exclude admin type, and only include user with account status 'Approve'
    $sql = "SELECT user_table.*, account_table.type, account_table.status 
            FROM user_table
            JOIN account_table ON user_table.email = account_table.email
            WHERE user_table.email = ? AND account_table.type = ? AND account_table.status = 'Approve'";

    // Use prepared statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ss", $profileEmail, $type);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result set
    $result = mysqli_stmt_get_result($stmt);

    // Error message when user try to find other users that arent shown in the view alumni
    if (mysqli_num_rows($result) == 0) {
        echo "<script>alert('You aren\'t authorize to do that.')</script>";
        echo "<script>location.replace('view_alumni.php');</script>";
        exit();
    }

    if ($result) {
        // Fetch the data and store it in $alumniData
        $alumniData = mysqli_fetch_assoc($result);
    } else {
        echo "Error in the query: " . mysqli_error($conn);
    }

    // Close the result set
    mysqli_free_result($result);

    // Close the statement
    mysqli_stmt_close($stmt);

    // Close the connection
    mysqli_close($conn);
    ?>

    <!-- Navigation Bar -->
    <?php include('navigation.php') ?>

    <!-- Content Container -->
    <div class="container-fluid my-5">
        <!-- Row of Cards -->
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-10">
                <div class="row d-flex justify-content-center align-items-center">
                    <!-- Card -->
                    <?php if (!empty($alumniData)) : ?>
                        <div class="col-md-8">
                            <div class="card hover_border border_round lightblue_border shadow-lg">
                                <div class="card-body p-3">
                                    <div class="row d-flex justify-content-center align-items-center">
                                        <!-- Left side with image -->
                                        <div class="col-md-4 text-center">
                                            <?php
                                            // Check the gender and load the corresponding avatar
                                            $gender = $alumniData['gender'];
                                            $defaultAvatarSrc = ($gender == 'Male') ? 'profile_images/male_avatar.png' : 'profile_images/female_avatar.png';

                                            // Check if the alumni has a profile picture, and use the default picture if not
                                            if (isset($alumniData['profile_image']) && !empty($alumniData['profile_image'])) {
                                                $profilePicture = $alumniData['profile_image'];
                                            } else {
                                                $profilePicture = $defaultAvatarSrc;
                                            }
                                            ?>
                                            <!-- Timestamps for the purpose of forcing the browser to fetch the updated image. (Prevent cached image)-->
                                            <img src="<?php echo $profilePicture . '?timestamp=' . time(); ?>" class="img-fluid profile_img" alt="Profile Picture">
                                        </div>
                                        <!-- Right side with text -->
                                        <!-- Name -->
                                        <div class="col-md-8 text_align mt-2">
                                            <h2 class="card-title text-dark mb-0 text_align">
                                                <?php echo $alumniData['first_name'] . ' ' . $alumniData['last_name']; ?>
                                            </h2>
                                            <!-- Additional Information -->
                                            <!-- Job Position -->
                                            <?php if (isset($alumniData['Job Position'])) : ?>
                                                <p class="card-text mb-0 text_align"><?php echo $alumniData['job_position']; ?></p>
                                            <?php endif; ?>

                                            <!-- Current Location + Company -->
                                            <?php if (isset($alumniData['company']) && (!empty($alumniData['company'])) && isset($alumniData['current_location']) && (!empty($alumniData['current_location']))) : ?>
                                                <p class="card-text text-muted mb-0 text_align"><?php echo $alumniData['company']; ?> | <?php echo $alumniData['current_location']; ?></p>
                                            <?php endif; ?>

                                            <!-- Email -->
                                            <p class="card-text text-primary text_align"><?php echo $alumniData['email']; ?></p>

                                            <div class="bg_light_grey rounded p-1 my-3 shadow-sm">
                                                <!-- Hometown -->
                                                <p class="card-text text-muted text_15 mb-0 text_align">Hometown</p>
                                                <p class="card-text mb-0 text_align"><?php echo $alumniData['hometown']; ?></p>

                                                <!-- Education -->
                                                <?php if (isset($alumniData['qualification']) && (!empty($alumniData['qualification'])) && isset($alumniData['year']) && (!empty($alumniData['year'])) && isset($alumniData['university']) && (!empty($alumniData['university']))) : ?>
                                                    <p class="card-text text-muted text_15 mb-0 text_align">Education</p>
                                                    <p class="card-text text_align"><?php echo $alumniData['qualification']; ?> | Year <?php echo $alumniData['year']; ?> | <?php echo $alumniData['university']; ?></p>
                                                <?php endif; ?>
                                            </div>

                                            <?php if (isset($alumniData['resume']) && !empty($alumniData['resume'])) : ?>
                                                <?php
                                                // The path to the PDF file
                                                $pdfFile = $alumniData['resume'];
                                                ?>

                                                <button class="btn btn-primary btn-block"><a href="<?php echo $pdfFile; ?>" class="text-decoration-none text-white">View Resume</a></button>
                                            <?php else : ?>
                                                <button class="btn btn-primary btn-block" disabled>View Resume</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>