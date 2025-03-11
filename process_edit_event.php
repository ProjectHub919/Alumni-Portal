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
    $eventTitle = $type = $date = $location = $description = $photoPath = "";
    $eventTitle_error = $type_error = $date_error = $location_error = $description_error = $upload_error = "";

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

        // Retrieve the post ID
        $idPost = $_POST["id"];

        // Validate event title 
        if (isset($_POST["eventTitle"])) {
            if (empty(trim($_POST["eventTitle"]))) {
                // error message for Required 
                $eventTitle_error = "*Event/News is required";
                // error message for Too long
            } else if (strlen(trim($_POST["eventTitle"])) > 100) {
                $eventTitle_error = "*Title should be less than 100 characters";
            } else {
                $eventTitle = trim($_POST["eventTitle"]);
            }
        }

        // Validate type
        if (isset($_POST["type"])) {
            if (empty($_POST["type"])) {
                // error message for Required 
                $type_error = "*Type is required";
            } else {
                $type = $_POST["type"];
            }
        }

        // Validate date 
        if (isset($_POST["date"])) {
            if (empty($_POST["date"])) {
                // error message for Required 
                $date_error = "*Date is required";
            } else {
                $date = $_POST["date"];
            }
        }

        // Validate location
        if (isset($_POST["location"])) {
            if (empty(trim($_POST["location"]))) {
                // error message for Required 
                $location_error = "*Location is required";
                // error message for Too long
            } else if (strlen(trim($_POST["location"])) > 50) {
                $location_error = "*Location should be less than 50 characters";
            } else {
                $location = trim($_POST["location"]);
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

        // Update Profile Photo
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
        if (empty($eventTitle_error) && empty($type_error) && empty($date_error) && empty($location_error) && empty($description_error)  && empty($upload_error)) {

            // Uploading photo to file and set its path in the database
            $targetDir = "img/";

            // Lowercase and replace the empty space of event title (To be used as the photo name)
            $lowerEventTitle = strtolower(str_replace(" ", "_", $eventTitle));
            $newFileName = $lowerEventTitle . "_" . time() . "." . pathinfo($_FILES["photo_input"]["name"], PATHINFO_EXTENSION);
            $targetFile = $targetDir . $newFileName;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            $oldPhotoPath = $_POST["photoPath"];

            if ($imageFileType != "") {
                // Remove the old photo using the old path of the photo stored inside the database to locate it
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }

                // Move the uploaded file to the server
                if (move_uploaded_file($_FILES["photo_input"]["tmp_name"], $targetFile)) {
                    // Update the Photo Path
                    $photoPath = $targetFile;
                } else {
                    echo "<script>alert('Error uploading the photo.')</script>";
                    echo "<script>window.location.href = 'edit_event.php';</script>";
                    exit();
                }
            } else {
                // No upload, hence the path to the photo remain the same
                $photoPath = $oldPhotoPath;
            }

            // Update event/news data in the event_table
            $updateQuery = "UPDATE event_table SET title=?, `location`=?, `description`=?, event_date=?, photo=?, `type`=? WHERE id=?";
            $stmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($stmt, "ssssssi", $eventTitle, $location, $description, $date, $photoPath, $type, $idPost);
            mysqli_stmt_execute($stmt);

            // Close the statement
            mysqli_stmt_close($stmt);

            // Display a success alert 
            echo "<script>alert('$eventTitle updated successful!');</script>";

            //Go to manage event page
            echo "<script>location.replace('manage_events.php');</script>";
            exit();
        }

        // Close database connection
        mysqli_close($conn);
    }

    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>