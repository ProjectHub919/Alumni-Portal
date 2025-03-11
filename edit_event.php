<!DOCTYPE html>
<html lang="en">

<head>
    <title>EditEvents</title>
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

    // Check if the id parameter exists in the URL
    if (isset($_GET['id'])) {
        $eventID = $_GET['id'];
    }

    // Use the event ID to fetch data from the database
    $sql = "SELECT * FROM event_table WHERE id = ?";

    // Use prepared statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "i", $eventID);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result set
    $result = mysqli_stmt_get_result($stmt);

    // Error message when user try to find non exisitng ID that arent exist in the database by modifying the url
    if (mysqli_num_rows($result) == 0) {
        echo "<script>alert('You are trying to access a non existing ID, dont do that!')</script>";
        echo "<script>location.replace('manage_events.php');</script>";
        exit();
    }

    if ($result) {
        // Fetch the data and use it as needed
        $eventData = mysqli_fetch_assoc($result);

        // Extract the data
        $eventTitleGet = $eventData['title'];
        $typeGet = $eventData['type'];
        $dateGet = $eventData['event_date'];
        $locationGet = $eventData['location'];
        $descriptionGet = $eventData['description'];
        $photoPathGet = $eventData['photo'];

        // Free the result set
        mysqli_free_result($result);
    } else {
        echo "Error in the query: " . mysqli_error($conn);
    }

    // Close the connection
    mysqli_close($conn);
    ?>

    <!-- Navigation Bar -->
    <?php include('admin_navigation.php') ?>

    <!-- Include -->
    <?php include('process_edit_event.php') ?>

    <h2 class="text-center mt-3 appearing_word">Edit Event/News</h2>

    <!-- Container -->
    <div class="container-fluid py-3">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-lg-7">
                <div class="card hover_border lightblue_border shadow-lg">
                    <div class="card-body p-4 p-md-5">
                        <!-- Form -->
                        <form method="post" enctype="multipart/form-data">
                            <!-- Hidden input for id -->
                            <input type="hidden" name="id" value="<?php echo $eventID; ?>">

                            <div class="row">
                                <!-- Event Title -->
                                <div class="col-md-8 mb-4">
                                    <div class="form-outline">
                                        <label for="event_title" class="form-label">Event Title*</label>
                                        <input type="text" id="eventTitle" name="eventTitle" placeholder="Event/News Title" class="form-control" value="<?php echo $eventTitleGet; ?>" />
                                        <span class="text-danger"><?php echo $eventTitle_error; ?></span>
                                    </div>
                                </div>

                                <!-- Type -->
                                <div class="col-md-4 mb-4">
                                    <div class="form-outline">
                                        <label for="type" class="form-label">Type*</label>
                                        <select class="form-select" id="type" name="type">
                                            <!-- Selected based on last selected -->
                                            <option value="events" <?php echo ($typeGet == "events") ? "selected" : ""; ?>>Event</option>
                                            <option value="news" <?php echo ($typeGet == "news") ? "selected" : ""; ?>>News</option>
                                        </select>
                                        <span class="text-danger"><?php echo $type_error; ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Date -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <label for="date" class="form-label">Date*</label>
                                        <input type="date" id="date" name="date" class="form-control" value="<?php echo $dateGet; ?>" />
                                        <span class="text-danger"><?php echo $date_error; ?></span>
                                    </div>
                                </div>
                                <!-- Location -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <label for="location" class="form-label">Location*</label>
                                        <input type="text" id="location" name="location" placeholder="Event/News Location" class="form-control" value="<?php echo $locationGet; ?>" />
                                        <span class="text-danger"><?php echo $location_error; ?></span>
                                    </div>
                                </div>
                            </div>
                            <!-- Description -->
                            <div class="row">
                                <label for="description" class="form-label">Description*</label>
                                <div class="col-md-12 mb-4">
                                    <div class="form-outline">
                                        <textarea id="description" name="description" class="form-control" rows="6"><?php echo $descriptionGet; ?></textarea>
                                        <span class="text-danger"><?php echo $description_error; ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Photo -->
                            <div class="row">
                                <label for="upload_photo" class="form-label">Photo</label>
                                <div class="col-md-6 mb-4">
                                    <!-- Hidden input for Photo Path -->
                                    <input type="hidden" name="photoPath" value="<?php echo $photoPathGet; ?>">

                                    <img src="<?php echo $photoPathGet; ?>" class="img-fluid border border-dark edit_event_img mb-3" alt="Photo">
                                    <input type="file" id="photo_input" name="photo_input" class="form-control mb-3">
                                    <span class="text-danger"><?php echo $upload_error; ?></span>
                                </div>
                            </div>

                            <!-- Button -->
                            <div class="d-flex justify-content-end mt-2">
                                <a class="btn btn-outline-dark btn-lg me-3" href="manage_events.php" role="button">Cancel</a>
                                <input class="btn btn-outline-success btn-lg" name="edit_btn" type="submit" value="Edit" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>