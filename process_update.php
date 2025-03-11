<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
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
    // Initialize
    $firstName = $lastName = $dateOfBirth = $gender = $email = $hometown = $contactNo = $currentLocation = $degreeProgram = $yearGraduated = $university = $jobPosition = $company = "";
    $firstName_error = $lastName_error = $dateOfBirth_error = $email_error = $hometown_error = $contactNo_error = $currentLocation_error = $degreeProgram_error = $yearGraduated_error = $university_error = $jobPosition_error = $company_error = $resume_msg = $resume_error_msg = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

        // Update Profile Photo Code
        if (isset($_POST['update_profile_photo_btn'])) {
            // Update Profile Photo
            if (isset($_FILES["profile_photo_input"])) {
                $targetDir = "profile_images/";

                // Retrieve From Session
                $email_session = $_SESSION["email"];

                // Generate a new file name based on the session email
                $newFileName = $email_session . "." . pathinfo($_FILES["profile_photo_input"]["name"], PATHINFO_EXTENSION);

                // Path to the image file
                $targetFile = $targetDir . $newFileName;
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // Check whether user upload any photo when clicked the update photo button
                if ($imageFileType == "") {
                    echo "<script>alert('Please insert a profile photo to update your profile photo.')</script>";
                    echo "<script>window.location.href = 'update_profile.php';</script>";
                    exit();
                }

                // Check file type
                $allowedExtensions = array("jpg", "jpeg", "png");
                if (!in_array($imageFileType, $allowedExtensions)) {
                    echo "<script>alert('Only JPG, JPEG, and PNG files are allowed.')</script>";
                    echo "<script>window.location.href = 'update_profile.php';</script>";
                    exit();
                }

                // Check file size
                if ($_FILES["profile_photo_input"]["size"] > 5 * 1024 * 1024) {
                    echo "<script>alert('Profile Photo size exceeds the limit of 5MB.')</script>";
                    echo "<script>window.location.href = 'update_profile.php';</script>";
                    exit();
                }

                // Check if a file with the same name already exists
                if (file_exists($targetFile)) {

                    // Delete any existing file with the same session email (regardless of extension)
                    $existingFiles = glob($targetDir . $email_session . ".*");

                    if (!empty($existingFiles)) {
                        foreach ($existingFiles as $oldProfile) {
                            if (is_file($oldProfile)) {
                                unlink($oldProfile);
                            }
                        }
                    }
                }

                // Move the uploaded file to the server
                if (move_uploaded_file($_FILES["profile_photo_input"]["tmp_name"], $targetFile)) {

                    // Get the Profile Photo Path
                    $profilePhotoPath = $targetFile;

                    // Update the database to update the new profile image path
                    $updateQuery = "UPDATE user_table SET profile_image = ? WHERE email = ?";
                    $stmtUpdate = mysqli_prepare($conn, $updateQuery);
                    mysqli_stmt_bind_param($stmtUpdate, "ss", $profilePhotoPath, $email_session);

                    if (mysqli_stmt_execute($stmtUpdate)) {
                        echo "<script>alert('Profile Photo updated successfully.')</script>";
                        echo "<script>window.location.href = 'update_profile.php';</script>";
                    } else {
                        echo "<script>alert('Error updating the profile photo to the database.')</script>";
                        echo "<script>window.location.href = 'update_profile.php';</script>";
                    }

                    // Close the statement
                    mysqli_stmt_close($stmtUpdate);
                } else {
                    echo "<script>alert('Error uploading the profile photo.')</script>";
                    echo "<script>window.location.href = 'update_profile.php';</script>";
                    exit();
                }
            }
        }

        // Upload Resume Code
        if (isset($_POST['upload_resume_btn'])) {
            // Upload Resume
            if (isset($_FILES["resume_input"])) {
                $targetDir = "resume/";

                // Retrieve From Session
                $email_session = $_SESSION["email"];

                // Generate a new file name based on the session email
                $newFileName = $email_session . "." . pathinfo($_FILES["resume_input"]["name"], PATHINFO_EXTENSION);

                // Path to the image file
                $targetFile = $targetDir . $newFileName;
                $pdfFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // Initialized the value
                $uploadOk = true;

                // Check whether user uplaoded any resume when clicked the upload resume button
                if ($pdfFileType == "") {
                    $resume_error_msg = "Please upload a resume.";
                    $uploadOk = false;
                
                    // Check file type
                } else if ($pdfFileType != "pdf") {
                    $resume_error_msg = "Only PDF files are allowed.";
                    $uploadOk = false;
                }

                // Check file size
                if ($_FILES["resume_input"]["size"] > 7 * 1024 * 1024) {
                    $resume_error_msg = "Resume size exceeds the limit of 7MB.";
                    $uploadOk = false;
                }

                if ($uploadOk) {
                    // Move the uploaded file to the server
                    if (move_uploaded_file($_FILES["resume_input"]["tmp_name"], $targetFile)) {

                        // Get the Profile Photo Path
                        $resumePath = $targetFile;

                        // Update the database 
                        $updateQuery = "UPDATE user_table SET `resume` = ? WHERE email = ?";
                        $stmtUpdate = mysqli_prepare($conn, $updateQuery);
                        mysqli_stmt_bind_param($stmtUpdate, "ss", $resumePath, $email_session);

                        if (mysqli_stmt_execute($stmtUpdate)) {
                            $resume_msg = "Resume uploaded successfully";
                        } else {
                            echo "<script>alert('Error uploading Resume to the database.')</script>";
                            echo "<script>window.location.href = 'update_profile.php';</script>";
                        }

                        // Close the statement
                        mysqli_stmt_close($stmtUpdate);
                    } else {
                        echo "<script>alert('Error uploading the resume.')</script>";
                        echo "<script>window.location.href = 'update_profile.php';</script>";
                        exit();
                    }
                }
            }
        }

        if (isset($_POST['update_info_btn'])) {
            // Validate first name input
            if (empty(trim($_POST["firstName"]))) {
                $firstName_error = "*First name is required";
            } else if (!preg_match('/^[a-zA-Z\s]+$/', trim($_POST["firstName"]))) {
                $firstName_error = "*Only letters and white spaces allowed";
            } else {
                $firstName = trim($_POST["firstName"]);
            }

            // Validate last name input
            if (empty(trim($_POST["lastName"]))) {
                $lastName_error = "*Last name is required";
            } else if (!preg_match('/^[a-zA-Z\s]+$/', trim($_POST["lastName"]))) {
                $lastName_error = "*Only letters and white spaces allowed";
            } else {
                $lastName = trim($_POST["lastName"]);
            }

            // Validate date of birth
            if (empty($_POST["dateOfBirth"])) {
                $dateOfBirth_error = "*Date of birth is required";
            } else {
                $dateOfBirth = $_POST["dateOfBirth"];
            }

            // Gender
            if (isset($_POST["gender"]) && !empty(trim($_POST["gender"]))) {
                $gender = trim($_POST["gender"]);
            }

            // Validation for email
            if (empty(trim($_POST["email"]))) {
                $email_error = "*Email is required";
            } elseif (!preg_match('/^\w+@([a-z_]+?\.)+[a-z]{2,3}$/', trim($_POST["email"]))) {
                $email_error = "*Invalid email format";
            } else {
                $email = trim($_POST["email"]);
            }

            // Validation for hometown
            if (empty(trim($_POST["hometown"]))) {
                $hometown_error = "*Hometown is required";
            } else {
                $hometown = trim($_POST["hometown"]);
            }

            // Validation for contact no
            if (!empty($_POST["contactNo"])) {
                if (!preg_match('/^\d{10}$/', $_POST["contactNo"])) {
                    $contactNo_error = "*Invalid contact number";
                } else {
                    $contactNo = trim($_POST["contactNo"]);
                }
            }

            // Validation for current location
            if (!empty($_POST["currentLocation"])) {
                if (!preg_match('/^[a-zA-Z\s]+$/', $_POST["currentLocation"])) {
                    $currentLocation_error = "*Only letters and white spaces allowed";
                } else {
                    $currentLocation = trim($_POST["currentLocation"]);
                }
            }

            // Validation for degree program
            if (!empty($_POST["degreeProgram"])) {
                if (!preg_match('/^[a-zA-Z\s]+$/', $_POST["degreeProgram"])) {
                    $degreeProgram_error = "*Only letters and white spaces allowed";
                } else {
                    $degreeProgram = trim($_POST["degreeProgram"]);
                }
            }

            // Validation for year graduated
            if (!empty($_POST["yearGraduated"])) {
                if (!preg_match('/^\d{4}$/', $_POST["yearGraduated"])) {
                    $yearGraduated_error = "*Year should only contain 4 numbers";
                } else {
                    $yearGraduated = trim($_POST["yearGraduated"]);
                }
            }

            // Validation for university
            if (!empty($_POST["university"])) {
                if (!preg_match('/^[a-zA-Z\s]+$/', $_POST["university"])) {
                    $university_error = "*Only letters and white spaces allowed";
                } else {
                    $university = trim($_POST["university"]);
                }
            }

            // Validation for job position
            if (!empty($_POST["jobPosition"])) {
                if (!preg_match('/^[a-zA-Z\s]+$/', $_POST["jobPosition"])) {
                    $jobPosition_error = "*Only letters and white spaces allowed";
                } else {
                    $jobPosition = trim($_POST["jobPosition"]);
                }
            }

            // Validation for company
            if (!empty($_POST["company"])) {
                if (!preg_match('/^[a-zA-Z\s]+$/', $_POST["company"])) {
                    $company_error = "*Only letters and white spaces allowed";
                } else {
                    $company = trim($_POST["company"]);
                }
            }

            if (empty($firstName_error) && empty($lastName_error) && empty($dateOfBirth_error) && empty($email_error) && empty($hometown_error) && empty($contactNo_error) && empty($currentLocation_error) && empty($degreeProgram_error) && empty($yearGraduated_error) && empty($university_error) && empty($jobPosition_error) && empty($company_error)) {
                //Retrieve From Session
                $email_session = $_SESSION["email"];

                // Check if the new email is different from the current email
                if ($email != $email_session) {
                    //Check if the new email is already in use
                    $checkEmailQuery = "SELECT email FROM user_table WHERE email = ?";
                    $stmtCheckEmail = mysqli_prepare($conn, $checkEmailQuery);
                    mysqli_stmt_bind_param($stmtCheckEmail, "s", $email);
                    mysqli_stmt_execute($stmtCheckEmail);
                    $resultCheckEmail = mysqli_stmt_get_result($stmtCheckEmail);

                    if (mysqli_num_rows($resultCheckEmail) > 0) {
                        // Display an alert box to the user
                        echo "<script>alert('This email is already in use. Please choose a different email.');</script>";
                        echo "<script>window.location.href = 'update_profile.php';</script>";
                        exit();
                    }

                    // Free the result set
                    mysqli_free_result($resultCheckEmail);

                    // Close the statement
                    mysqli_stmt_close($stmtCheckEmail);

                    // If new email is not use, retrieve current file names and paths
                    $getFileNamesQuery = "SELECT profile_image, `resume` FROM user_table WHERE email = ?";
                    $stmtGetFileNames = mysqli_prepare($conn, $getFileNamesQuery);
                    mysqli_stmt_bind_param($stmtGetFileNames, "s", $email_session);
                    mysqli_stmt_execute($stmtGetFileNames);
                    $resultFileNames = mysqli_stmt_get_result($stmtGetFileNames);

                    // Update the profile image and resume name of the old email to the new email
                    if ($row = mysqli_fetch_assoc($resultFileNames)) {
                        $currentProfileImage = $row['profile_image'];
                        $currentResume = $row['resume'];

                        // Check if profile_image is not NULL
                        if ($currentProfileImage != null) {
                            // The new name (email) for the profile image
                            $newProfileImageName = "profile_images/" . $email . "." . pathinfo($currentProfileImage, PATHINFO_EXTENSION);

                            // Rename the profile image file in the folder
                            rename($currentProfileImage, $newProfileImageName);

                            // Update the path in the database
                            $updateProfileImageQuery = "UPDATE user_table SET profile_image = ? WHERE email = ?";
                            $stmtUpdateProfileImage = mysqli_prepare($conn, $updateProfileImageQuery);
                            mysqli_stmt_bind_param($stmtUpdateProfileImage, "ss", $newProfileImageName, $email_session);
                            mysqli_stmt_execute($stmtUpdateProfileImage);
                            mysqli_stmt_close($stmtUpdateProfileImage);
                        }

                        // Check if resume is not NULL
                        if ($currentResume != null) {
                            // The new name (email) for the resume
                            $newResumeName = "resume/" . $email . "." . pathinfo($currentResume, PATHINFO_EXTENSION);

                            // Rename the resume file in the folder
                            rename($currentResume, $newResumeName);

                            // Update the path in the database
                            $updateResumeQuery = "UPDATE user_table SET `resume` = ? WHERE email = ?";
                            $stmtUpdateResume = mysqli_prepare($conn, $updateResumeQuery);
                            mysqli_stmt_bind_param($stmtUpdateResume, "ss", $newResumeName, $email_session);
                            mysqli_stmt_execute($stmtUpdateResume);
                            mysqli_stmt_close($stmtUpdateResume);
                        }
                    }

                    // Free the result
                    mysqli_free_result($resultFileNames);

                    // Close the statement
                    mysqli_stmt_close($stmtGetFileNames);

                    // Update the email in both user_table and account_table
                    $updateEmailQueryUser = "UPDATE user_table SET email = ? WHERE email = ?";
                    $stmtUpdateEmailUser = mysqli_prepare($conn, $updateEmailQueryUser);
                    mysqli_stmt_bind_param($stmtUpdateEmailUser, "ss", $email, $email_session);
                    mysqli_stmt_execute($stmtUpdateEmailUser);

                    $updateEmailQueryAccount = "UPDATE account_table SET email = ? WHERE email = ?";
                    $stmtUpdateEmailAccount = mysqli_prepare($conn, $updateEmailQueryAccount);
                    mysqli_stmt_bind_param($stmtUpdateEmailAccount, "ss", $email, $email_session);
                    mysqli_stmt_execute($stmtUpdateEmailAccount);

                    $_SESSION["email"] = $email; // Update the email in the session

                    // Free the statements
                    mysqli_stmt_close($stmtUpdateEmailUser);
                    mysqli_stmt_close($stmtUpdateEmailAccount);
                }

                // Update other fields in user_table
                $updateQuery = "UPDATE user_table SET first_name = ?, last_name = ?, dob = ?, gender = ?, hometown = ?, contact_number = ?, current_location = ?, qualification = ?, `year` = ?, university = ?, job_position = ?, company = ? WHERE email = ?";
                $stmtUpdateQuery = mysqli_prepare($conn, $updateQuery);
                mysqli_stmt_bind_param($stmtUpdateQuery, "ssssssssissss", $firstName, $lastName, $dateOfBirth, $gender, $hometown, $contactNo, $currentLocation, $degreeProgram, $yearGraduated, $university, $jobPosition, $company, $email);
                mysqli_stmt_execute($stmtUpdateQuery);

                // Free the statement
                mysqli_stmt_close($stmtUpdateQuery);

                // Redirect to the update_profile.php page
                header("location: update_profile.php");
                exit();
            }
        }

        // Close database connection
        mysqli_close($conn);
    }
    ?>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>