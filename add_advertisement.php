<!DOCTYPE html>
<html lang="en">

<head>
    <title>AddAdvertisements</title>
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

    // Close the connection
    mysqli_close($conn);
    ?>

    <!-- Navigation Bar -->
    <?php include('admin_navigation.php') ?>

    <!-- Include -->
    <?php include('process_add_advertisement.php') ?>

    <h2 class="text-center mt-3 appearing_word">Add Advertisement</h2>

    <!-- Container -->
    <div class="container-fluid py-3">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-lg-7">
                <div class="card hover_border lightblue_border shadow-lg">
                    <div class="card-body p-4 p-md-5">
                        <!-- Form -->
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                            <div class="row">
                                <!-- Title -->
                                <div class="col-md-8 mb-4">
                                    <div class="form-outline">
                                        <label for="advertisement_title" class="form-label">Advertisement Title*</label>
                                        <input type="text" id="advertisementTitle" name="advertisementTitle" placeholder="Advertisement Title" class="form-control" value="<?php echo $advertisementTitle; ?>" />
                                        <span class="text-danger"><?php echo $advertisementTitle_error; ?></span>
                                    </div>
                                </div>

                                <!-- Category -->
                                <div class="col-md-4 mb-4">
                                    <div class="form-outline">
                                        <label for="category" class="form-label">Category*</label>
                                        <select class="form-select" id="category" name="category">
                                            <!-- Selected based on last selected -->
                                            <option value="engineering" <?php echo ($category == "engineering") ? "selected" : ""; ?>>Engineering</option>
                                            <option value="it" <?php echo ($category == "it") ? "selected" : ""; ?>>IT</option>
                                            <option value="business" <?php echo ($category == "business") ? "selected" : ""; ?>>Business</option>
                                            <option value="design" <?php echo ($category == "design") ? "selected" : ""; ?>>Design</option>
                                        </select>
                                        <span class="text-danger"><?php echo $category_error; ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- URL -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <label for="url" class="form-label">URL</label>
                                        <input type="text" id="url" name="url" placeholder="URL Advertisement" class="form-control" value="<?php echo $url; ?>" />
                                        <span class="text-danger"><?php echo $url_error; ?></span>
                                    </div>
                                </div>
                                <!-- Email -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <label for="email" class="form-label">Email*</label>
                                        <input type="text" id="email" name="email" placeholder="Email (Contact Info)" class="form-control" value="<?php echo $advertisement_email; ?>" />
                                        <span class="text-danger"><?php echo $email_error; ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Description -->
                            <div class="row">
                                <label for="description" class="form-label">Description*</label>
                                <div class="col-md-12 mb-4">
                                    <div class="form-outline">
                                        <textarea id="description" name="description" class="form-control" rows="6"><?php echo $description; ?></textarea>
                                        <span class="text-danger"><?php echo $description_error; ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Photo -->
                            <div class="row">
                                <label for="upload_photo" class="form-label">Upload Photo</label>
                                <div class="col-md-6 mb-4">
                                    <input type="file" id="photo_input" name="photo_input" class="form-control mb-3">
                                    <span class="text-danger"><?php echo $upload_error; ?></span>
                                </div>
                            </div>

                            <!-- Button -->
                            <div class="d-flex justify-content-end mt-2">
                                <a class="btn btn-primary btn-lg me-3" href="main_menu_admin.php" role="button">Cancel</a>
                                <input class="btn btn-danger btn-lg" type="submit" value="Create" />
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