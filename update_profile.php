<!DOCTYPE html>
<html lang="en">

<head>
    <title>UpdateProfile</title>
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

    // Retrieve the alumni data from the database
    $sql = "SELECT * FROM user_table WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Fetch the data and store it in $alumniData
        $alumniData = mysqli_fetch_assoc($result);
    } else {
        echo "Error in the query: " . mysqli_error($conn);
    }

    // Close the result set
    mysqli_free_result($result);

    // Close the connection
    mysqli_close($conn);
    ?>

    <!-- Include -->
    <?php include('process_update.php') ?>

    <!-- Navigation Bar -->
    <?php include('navigation.php') ?>

    <!-- Container -->
    <div class="container-fluid p-3">
        <div class="row justify-content-center">
            <div class="col-11">
                <div class="card hover_border shadow-lg">
                    <div class="card-body">
                        <div class="row">
                            <?php if (!empty($alumniData)) : ?>
                                <div class="col-4 text-center">
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
                                    <img src="<?php echo $profilePicture . '?timestamp=' . time(); ?>" class="img-fluid mt-4 rounded-circle border border-dark update_profile_img" alt="Profile Picture">
                                    <h5 class="mt-2"><?php echo $alumniData['first_name'] . ' ' . $alumniData['last_name']; ?></h5>
                                    <p class="card-text text-muted card-text-smaller"><?php echo $alumniData['email']; ?></p>

                                    <form method="post" id="upload_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                                        <!-- Update Profile Photo Section -->
                                        <h4 class="text-primary mt-5 pt-3 mb-3">Update Profile Photo</h4>
                                        <div class="col-8 mx-auto">
                                            <input type="file" id="profile_photo_input" name="profile_photo_input" class="form-control mb-3">
                                        </div>

                                        <!-- When small screen, display this button -->
                                        <button type="submit" id="update_profile_photo_btn" name="update_profile_photo_btn" class="btn btn-lg btn-outline-primary px-2 shadow-sm btn-sm d-lg-none">Update Image</button>

                                        <!-- When big screen, display this button -->
                                        <button type="submit" id="update_profile_photo_btn" name="update_profile_photo_btn" class="btn btn-lg btn-outline-primary px-2 shadow-sm d-none d-lg-inline">Update Image</button>

                                        <!-- Update Resume Section -->
                                        <h4 class="text-primary mt-5 mb-3">Upload Resume</h4>
                                        <div class="col-8 mx-auto">
                                            <input type="file" id="resume_input" name="resume_input" class="form-control mb-3">
                                        </div>

                                        <!-- When small screen, display this button -->
                                        <button type="submit" id="upload_resume_btn" name="upload_resume_btn" class="btn btn-lg btn-outline-primary px-2 shadow-sm btn-sm d-lg-none">Upload Resume</button>

                                        <!-- When big screen, display this button -->
                                        <button type="submit" id="upload_resume_btn" name="upload_resume_btn" class="btn btn-lg btn-outline-primary px-2 shadow-sm d-none d-lg-inline">Upload Resume</button>

                                        <div class="pt-3 col-7 mx-auto">
                                            <?php if (!empty($resume_msg)) : ?>
                                                <div class="alert alert-success border border-2 border-success" role="alert">
                                                    <?php echo $resume_msg; ?>
                                                </div>
                                            <?php elseif (!empty($resume_error_msg)) : ?>
                                                <div class="alert alert-danger border border-2 border-danger" role="alert">
                                                    <?php echo $resume_error_msg; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-8 p-3">
                                    <form method="post" id="update_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                        <div class="row">
                                            <div class="col-12">
                                                <h6 class="mb-2 text-primary">Personal Details</h6>
                                            </div>

                                            <!-- First Name -->
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                                <div class="form-group">
                                                    <label for="firstName" class="form-label d-block">First Name</label>
                                                    <div class="form-outline">
                                                        <input type="text" id="firstName" name="firstName" placeholder="Enter first name" class="form-control" value="<?php echo $alumniData['first_name']; ?>" />
                                                        <span class="text-danger"><?php echo $firstName_error; ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Last Name -->
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                                <div class="form-group">
                                                    <label for="lastName" class="form-label d-block">Last Name</label>
                                                    <div class="form-outline">
                                                        <input type="text" id="lastName" name="lastName" placeholder="Enter last name" class="form-control" value="<?php echo $alumniData['last_name']; ?>" />
                                                        <span class="text-danger"><?php echo $lastName_error; ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Contact No. -->
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                                <div class="form-group">
                                                    <label for="contactNo" class="form-label d-block">Contact No.</label>
                                                    <div class="form-outline">
                                                        <input type="text" id="contactNo" name="contactNo" placeholder="Please enter Contact No." class="form-control" value="<?php echo isset($alumniData['Contact No']) ? $alumniData['contact_number'] : ''; ?>" />
                                                        <span class="text-danger"><?php echo $contactNo_error; ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Email -->
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                                <div class="form-group">
                                                    <label for="email" class="form-label d-block">Email</label>
                                                    <div class="form-outline">
                                                        <input type="text" id="email" name="email" placeholder="Please enter Email" class="form-control" value="<?php echo $alumniData['email']; ?>" />
                                                        <span class="text-danger"><?php echo $email_error; ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Gender -->
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                                <div class="form-group">
                                                    <label for="gender" class="form-label d-block">Gender</label>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="gender" id="gender" value="Female" <?php echo ($alumniData['gender'] === "Female") ? "checked" : ""; ?> />
                                                        <label class="form-check-label" for="gender">Female</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="gender" id="gender" value="Male" <?php echo ($alumniData['gender'] === "Male") ? "checked" : ""; ?> />
                                                        <label class="form-check-label" for="gender">Male</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Date Of Birth -->
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                                <div class="form-group">
                                                    <label for="dateOfBirth" class="form-label d-block">Date Of Birth</label>
                                                    <div class="form-outline">
                                                        <input type="date" id="dateOfBirth" name="dateOfBirth" class="form-control" value="<?php echo $alumniData['dob']; ?>" />
                                                        <span class="text-danger"><?php echo $dateOfBirth_error; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <h6 class="mb-2 text-primary">Address</h6>
                                            </div>

                                            <!-- Hometown -->
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                                <div class="form-group">
                                                    <label for="hometown" class="form-label">Hometown</label>
                                                    <div class="form-outline">
                                                        <input type="text" id="hometown" name="hometown" placeholder="Enter hometown" class="form-control" value="<?php echo $alumniData['hometown']; ?>" />
                                                        <span class="text-danger"><?php echo $hometown_error; ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Current location -->
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                                <div class="form-group">
                                                    <label for="currentLocation" class="form-label">Current Location</label>
                                                    <div class="form-outline">
                                                        <input type="text" id="currentLocation" name="currentLocation" placeholder="Enter current location" class="form-control" value="<?php echo isset($alumniData['current_location']) ? $alumniData['current_location'] : ''; ?>" />
                                                        <span class="text-danger"><?php echo $currentLocation_error; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <h6 class="mb-2 text-primary">Education</h6>
                                            </div>

                                            <!-- Degree Program -->
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                                <div class="form-group">
                                                    <label for="degreeProgram" class="form-label">Degree Program</label>
                                                    <div class="form-outline">
                                                        <input type="text" id="degreeProgram" name="degreeProgram" placeholder="Enter degree program" class="form-control" value="<?php echo isset($alumniData['qualification']) ? $alumniData['qualification'] : ''; ?>" />
                                                        <span class="text-danger"><?php echo $degreeProgram_error; ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Year Graduated -->
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                                <div class="form-group">
                                                    <label for="yearGraduated" class="form-label">Year graduated</label>
                                                    <div class="form-outline">
                                                        <input type="text" id="yearGraduated" name="yearGraduated" placeholder="Enter year graduated" class="form-control" value="<?php echo isset($alumniData['year']) ? $alumniData['year'] : ''; ?>" />
                                                        <span class="text-danger"><?php echo $yearGraduated_error; ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- University -->
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                                <div class="form-group">
                                                    <label for="university" class="form-label">University</label>
                                                    <div class="form-outline">
                                                        <input type="text" id="university" name="university" placeholder="Enter university" class="form-control" value="<?php echo isset($alumniData['university']) ? $alumniData['university'] : ''; ?>" />
                                                        <span class="text-danger"><?php echo $university_error; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <h6 class="mb-2 text-primary">Current job</h6>
                                            </div>

                                            <!-- Job Position -->
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                                <div class="form-group">
                                                    <label for="jobPosition" class="form-label">Job Position</label>
                                                    <div class="form-outline">
                                                        <input type="text" id="jobPosition" name="jobPosition" placeholder="Enter job position" class="form-control" value="<?php echo isset($alumniData['job_position']) ? $alumniData['job_position'] : ''; ?>" />
                                                        <span class="text-danger"><?php echo $jobPosition_error; ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Company -->
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-2">
                                                <div class="form-group">
                                                    <label for="company" class="form-label">Company</label>
                                                    <div class="form-outline">
                                                        <input type="text" id="company" name="company" placeholder="Enter company" class="form-control" value="<?php echo isset($alumniData['company']) ? $alumniData['company'] : ''; ?>" />
                                                        <span class="text-danger"><?php echo $company_error; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Button -->
                                        <div class="row mt-2">
                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-6 col-12 text-end">
                                                <!-- When small screen, display these two buttons -->
                                                <a class="btn btn-outline-secondary me-1 btn-lg btn-sm d-lg-none" href="main_menu.php" role="button">Cancel</a>
                                                <input class="btn btn-outline-success btn-lg btn-sm d-lg-none" type="submit" name="update_info_btn" value="Update" />
                                                <!-- When big screen, display these two buttons -->
                                                <a class="btn btn-outline-secondary me-1 btn-lg d-none d-lg-inline" href="main_menu.php" role="button">Cancel</a>
                                                <input class="btn btn-outline-success btn-lg d-none d-lg-inline" type="submit" name="update_info_btn" value="Update" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>