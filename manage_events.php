<!DOCTYPE html>
<html lang="en">

<head>
    <title>ManageEvents</title>
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

    // By default is all when enter the webpage
    $filterOption = isset($_POST["radioOption"]) ? $_POST["radioOption"] : 'all';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Delete button clicked
        if (isset($_POST["deleteBtn"])) {
            $idPost = $_POST["id"];

            // Check Register Event
            $checkRegEventQuery = "SELECT * FROM event_registration_table WHERE event_id = '$idPost'";
            $checkRegEventResult = mysqli_query($conn, $checkRegEventQuery);

            if ($checkRegEventResult) {
                if (mysqli_num_rows($checkRegEventResult) > 0) {
                    // Delete the event/news from event registration table
                    $deleteRegQuery = "DELETE FROM event_registration_table WHERE event_id = '$idPost'";
                    mysqli_query($conn, $deleteRegQuery);
                }

                // Free the result
                mysqli_free_result($checkRegEventResult);
            } else {
                echo "Error: " . mysqli_error($conn);
            }

            // Delete the Image of the Event/News from the img file
            $selectPhotoQuery = "SELECT photo, title FROM event_table WHERE id = '$idPost'";
            $selectPhotoQueryResult = mysqli_query($conn, $selectPhotoQuery);

            if ($selectPhotoQueryResult) {
                // Fetch the photo path from the result set
                $row = mysqli_fetch_assoc($selectPhotoQueryResult);
                $photoPath = $row["photo"];
                $eventTitle = $row["title"];

                // Check if the file exists before attempting to delete it
                if (file_exists($photoPath)) {
                    // Delete the file
                    unlink($photoPath);
                }

                // Delete the event/news from event table
                $deleteQuery = "DELETE FROM event_table WHERE id = '$idPost'";
                mysqli_query($conn, $deleteQuery);

                echo "<script>alert('$eventTitle has been removed from the database successfully!')</script>";
                echo "<script>location.replace('manage_events.php');</script>";

                // Free the result set
                mysqli_free_result($selectPhotoQueryResult);
                exit();
            } else {
                echo "Error in the query: " . mysqli_error($conn);
            }
        }
    }
    ?>

    <!-- Navigation Bar -->
    <?php include('admin_navigation.php') ?>

    <!-- Title and Add Event button -->
    <div class="container mt-3 d-flex justify-content-between align-items-center">
        <h2>Manage Events/News</h2>
        <a href="add_event.php" class="btn btn-danger">Add Event</a>
    </div>

    <!-- Outer square-shaped box -->
    <div class="container">
        <div class="mt-4 p-3 border border-2 border-dark">
            <!-- Form for filtering -->
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="row g-3 align-items-center">
                <!-- Radio buttons -->
                <!-- Value - All -->
                <div class="col-auto">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="radioOption" id="radioOption1" value="all" <?php echo ($filterOption == 'all') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="radioOption1">All</label>
                    </div>
                </div>

                <!-- Value - Events -->
                <div class="col-auto">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="radioOption" id="radioOption2" value="events" <?php echo ($filterOption == 'events') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="radioOption2">Events</label>
                    </div>
                </div>

                <!-- Value - News -->
                <div class="col-auto">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="radioOption" id="radioOption3" value="news" <?php echo ($filterOption == 'news') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="radioOption3">News</label>
                    </div>
                </div>

                <!-- Button to display list -->
                <div class="col-auto">
                    <button type="submit" name="filterBtn" class="btn btn-success">
                        <img src="img/search_icon.png" alt="Small Icon" width="22" height="22">
                        Display List
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="container mt-4">
        <div class="table-responsive">
            <table class="table table_dark_borders table-hover">
                <thead>
                    <?php
                    // Initialize error message
                    $error_msg = "There are no events/news in the Database!";

                    $sql = "SELECT * FROM event_table";

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        // Filter button clicked
                        if (isset($_POST["filterBtn"])) {
                            $filterOption = isset($_POST["radioOption"]) ? $_POST["radioOption"] : '';

                            // Filter events and news based on the selected option
                            if ($filterOption == "all") {
                                $sql = "SELECT * FROM event_table";

                                // Filter based on Events
                            } elseif ($filterOption == "events") {
                                $sql = "SELECT * FROM event_table WHERE type = 'events'";
                                $error_msg = "There are no Events type in the Database!";

                                // Filter based on News
                            } elseif ($filterOption == "news") {
                                $sql = "SELECT * FROM event_table WHERE type = 'news'";
                                $error_msg = "There are no News type in the Database!";
                            }
                        }
                    }

                    $result = mysqli_query($conn, $sql);

                    if ($result) {
                        if (mysqli_num_rows($result) > 0) {
                    ?>
                            <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">Type</th>
                                <th scope="col">Title</th>
                                <th scope="col">Date</th>
                                <th scope="col">Location</th>
                                <th scope="col">Edit</th>
                                <th scope="col">Delete</th>
                            </tr>
                </thead>
                <tbody>
                    <?php
                            // Allow Date to have suffix(exp: st, th)
                            function formatWithOrdinalSuffix($date)
                            {
                                $day = date('j', strtotime($date));
                                $suffix = date('S', strtotime($date));
                                return $day . $suffix . " " . date('F Y', strtotime($date));
                            }

                            $count = 1;

                            while ($row = mysqli_fetch_assoc($result)) {
                                $id = $row["id"];
                                $type = $row["type"];
                                $title = $row["title"];
                                $date = $row['event_date'];
                                $location = $row['location'];
                    ?>
                        <tr>
                            <th scope="row" class="text-center align-middle"><?php echo $count++; ?></th>
                            <td class="text-center align-middle"><?php echo $type; ?></td>
                            <td class="text-center align-middle"><?php echo $title; ?></td>
                            <td class="text-center align-middle"><?php echo formatWithOrdinalSuffix($date); ?></td>
                            <td class="text-center align-middle"><?php echo $location; ?></td>
                            <td class="text-center align-middle">
                                <!-- Edit Icon -->
                                <a href="edit_event.php?id=<?php echo $id; ?>" class="delete_btn">
                                    <img src="img/edit_icon.png" width="40" height="40" alt="Edit" class="enlarge">
                                </a>
                            </td>
                            <td class="text-center align-middle">
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <!-- Delete Icon -->
                                    <button type="submit" name="deleteBtn" class="delete_btn" onclick="return confirm('Are you sure you want to delete this event?');">
                                        <img src="img/rubbish_bin.png" width="40" height="40" alt="Delete" class="enlarge">
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php
                            }
                            mysqli_free_result($result);
                    ?>
                </tbody>
            </table>
    <?php
                        } else {
                            // Display a message if there is no data in the database
                            echo '<strong><p class="text-center text-danger">' . $error_msg . '</p></strong>';
                        }
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }

                    // Close the connection
                    mysqli_close($conn);
    ?>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>