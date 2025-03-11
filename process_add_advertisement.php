<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Web development">
    <meta name="keywords" content="HTML, CSS, JavaScript">
    <meta name="author" content="Yien Yang CHOO">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="style/style.css" rel="stylesheet" />
</head>

<body>

    <?php
    // Initialize
    $advertisementTitle = $category = $url = $advertisement_email = $description = $photoPath = "";
    $advertisementTitle_error = $category_error = $url_error = $email_error = $description_error = $upload_error = "";

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

        // Validate advertisement title 
        if (isset($_POST["advertisementTitle"])) {
            if (empty(trim($_POST["advertisementTitle"]))) {
                // error message for Required 
                $advertisementTitle_error = "*Advertisement is required";
                // error message for Too long
            } else if (strlen(trim($_POST["advertisementTitle"])) > 100) {
                $advertisementTitle_error = "*Title should be less than 100 characters";
            } else {
                $advertisementTitle = trim($_POST["advertisementTitle"]);
            }
        }

        // Validate category
        if (isset($_POST["category"])) {
            if (empty($_POST["category"])) {
                // error message for Required 
                $category_error = "*Category is required";
            } else {
                $category = $_POST["category"];
            }
        }

        // Validate url 
        if (isset($_POST["url"])) {
            if (!empty($_POST["url"])) {
                // Check if the URL is valid
                if (!filter_var($_POST["url"], FILTER_VALIDATE_URL)) {
                    $url_error = "*Invalid URL";
                }else {
                    $url = $_POST["url"];
                }
            }
        }

        // Validate email
        if (isset($_POST["email"])) {
            if (empty(trim($_POST["email"]))) {
                // error message for Required 
                $email_error = "*Email is required";
                // error message for invalid format
            } else if (!preg_match('/^\w+@([a-z_]+?\.)+[a-z]{2,3}$/', trim($_POST["email"]))) {
                $email_error = "*Invalid email format";
            } else {
                $advertisement_email = trim($_POST["email"]);
            }
        }

        // Validate description
        if (isset($_POST["description"])) {
            if (empty(trim($_POST["description"]))) {
                // error message for Required 
                $description_error = "*Description is required";
                // error message for Too long
            } else if (strlen(trim($_POST["description"])) > 700) {
                $description_error = "*Description should be less than 700 characters";
            } else {
                $description = trim($_POST["description"]);
            }
        }

        // Update Photo
        if (isset($_FILES["photo_input"])) {
            // Get uploaded Image File extension to check its type
            $targetFile = basename($_FILES["photo_input"]["name"]);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if ($imageFileType != "") {
                // Check file type
                $allowedExtensions = array("jpg", "png");
                if (!in_array($imageFileType, $allowedExtensions)) {
                    $upload_error = "*Only JPG and PNG files are allowed";
                }
            }
        }

        // If all validation passed
        if (empty($advertisementTitle_error) && empty($category_error) && empty($url_error) && empty($email_error) && empty($description_error)  && empty($upload_error)) {

            // Uploading photo to file and set its path in the database
            $targetDir = "img/";

            // Lowercase and replace the empty space of advertisement title (To be used as the photo name)
            $lowerAdvertisementTitle = strtolower(str_replace(" ", "_", $advertisementTitle));
            $newFileName = $lowerAdvertisementTitle . "_" . time() . "." . pathinfo($_FILES["photo_input"]["name"], PATHINFO_EXTENSION);
            $targetFile = $targetDir . $newFileName;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if ($imageFileType == "") {
                // No file was uploaded, set default photo path
                $defaultPhotoPath = "img/default_advertising.jpg";

                // Check whether there's default photo
                if (file_exists($defaultPhotoPath)) {
                    // Extract out the type
                    $defaultPhotoType = pathinfo($defaultPhotoPath, PATHINFO_EXTENSION);

                    // Create the new name for the default photo
                    $newFileName = $lowerAdvertisementTitle . "_" . time() . "." . $defaultPhotoType;
                    $targetFile = $targetDir . $newFileName;

                    // Create a copy of the default photo and modify its name
                    copy($defaultPhotoPath, $targetFile);
                    $photoPath = $targetFile;
                } else {
                    echo "<script>alert('Default photo doesn't exist.')</script>";
                    echo "<script>window.location.href = 'add_advertisement.php';</script>";
                    exit();
                }
            } else {
                // Move the uploaded file to the server
                if (move_uploaded_file($_FILES["photo_input"]["tmp_name"], $targetFile)) {
                    // Get the Photo Path
                    $photoPath = $targetFile;
                } else {
                    echo "<script>alert('Error uploading the photo.')</script>";
                    echo "<script>window.location.href = 'add_advertisement.php';</script>";
                    exit();
                }
            }

            // Insert advertisement data into the advertisement_table
            $insertQuery = "INSERT INTO advertisement_table (title, category, `description`, photo, email, `url`) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($stmt, "ssssss", $advertisementTitle, $category, $description, $photoPath, $advertisement_email, $url);
            mysqli_stmt_execute($stmt);

            // Close the statement
            mysqli_stmt_close($stmt);

            // Display a success alert 
            echo "<script>alert('$advertisementTitle created successful!');</script>";

            //Go to admin main menu page
            echo "<script>location.replace('main_menu_admin.php');</script>";
            exit();
        }

        // Close database connection
        mysqli_close($conn);
    }

    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>