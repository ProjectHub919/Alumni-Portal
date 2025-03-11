<!DOCTYPE html>
<html lang="en">

<head>
    <title>ViewEvents</title>
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

    // By default is all when enter the webpage
    $filterOption = isset($_POST["radioOption"]) ? $_POST["radioOption"] : "all";
    $filterDropdownOption = isset($_POST["filterDropdown"]) ? $_POST["filterDropdown"] : "all";

    // Initialize array to store events/news data
    $eventData = array();

    // Initialize error message
    $error_msg = "There are no Events/News in the Database!";

    // Retrieve all event/news data from the database by default
    $sql = "SELECT * FROM event_table";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Filter button clicked
        if (isset($_POST["filterBtn"])) {
            $filterOption = isset($_POST["radioOption"]) ? $_POST["radioOption"] : "";
            $filterDropdownOption = isset($_POST["filterDropdown"]) ? $_POST["filterDropdown"] : "";

            // Get the current date
            $currentDate = date("Y-m-d");

            // Filter events and news based on the selected radio options and drop-down menu
            // Filter based on All
            if ($filterOption == "all") {
                $sql = "SELECT * FROM event_table";

                // Include events in the past
                if ($filterDropdownOption == "past") {
                    $sql .= " WHERE event_date < '$currentDate'";
                    $error_msg = "There are no past Events/News in the Database!";
                }
                // Include upcoming events
                elseif ($filterDropdownOption == "upcoming") {
                    $sql .= " WHERE event_date >= '$currentDate'";
                    $error_msg = "There are no upcoming Events/News in the Database!";
                }
                // Default case (no events/news in database)
                else {
                    $error_msg = "There are no Events/News in the Database!";
                }

                // Filter based on Events
            } elseif ($filterOption == "events") {
                $sql = "SELECT * FROM event_table WHERE type = 'events'";

                // Include events in the past
                if ($filterDropdownOption == "past") {
                    $sql .= " AND event_date < '$currentDate'";
                    $error_msg = "There are no past Events in the Database!";
                }
                // Include upcoming events
                elseif ($filterDropdownOption == "upcoming") {
                    $sql .= " AND event_date >= '$currentDate'";
                    $error_msg = "There are no upcoming Events in the Database!";
                }
                // Default case (no events in database)
                else {
                    $error_msg = "There are no Events in the Database!";
                }

                // Filter based on News
            } elseif ($filterOption == "news") {
                $sql = "SELECT * FROM event_table WHERE type = 'news'";

                // Include news in the past
                if ($filterDropdownOption == "past") {
                    $sql .= " AND event_date < '$currentDate'";
                    $error_msg = "There are no past News in the Database!";
                }
                // Include upcoming news
                elseif ($filterDropdownOption == "upcoming") {
                    $sql .= " AND event_date >= '$currentDate'";
                    $error_msg = "There are no upcoming News in the Database!";
                }
                // Default case (no news in database)
                else {
                    $error_msg = "There are no News in the Database!";
                }
            }
        }

        // Sign up button clicked
        if (isset($_POST["btn_signUp"])) {
            // Get the event ID from the form submission
            $eventID = isset($_POST["event_id"]) ? $_POST["event_id"] : null;

            // Check if the event ID is valid
            if ($eventID != null) {
                // Check if the user has already registered for the event
                $checkSQL = "SELECT * FROM event_registration_table WHERE event_id = ? AND participant_email = ?";
                $checkStmt = mysqli_prepare($conn, $checkSQL);
                mysqli_stmt_bind_param($checkStmt, "is", $eventID, $email);
                mysqli_stmt_execute($checkStmt);
                $checkResult = mysqli_stmt_get_result($checkStmt);

                // If the user has not already registered, register the user into the event
                if (mysqli_num_rows($checkResult) == 0) {
                    $insertSQL = "INSERT INTO event_registration_table (event_id, participant_email) VALUES (?, ?)";
                    $insertStmt = mysqli_prepare($conn, $insertSQL);
                    mysqli_stmt_bind_param($insertStmt, "is", $eventID, $email);

                    // Execute the insert statement
                    if (mysqli_stmt_execute($insertStmt)) {
                        // Popup message for successful registration
                        echo "<script>alert('Hooray ! You are registered for this event!');</script>";
                    } else {
                        // Insertion failed
                        echo "<script>alert('Insertion failed on the database.');</script>";
                    }
                    // Close the prepared statements
                    mysqli_stmt_close($insertStmt);
                } else {
                    // Popup message for unsuccessful registration
                    echo "<script>alert('OOPS! Registration unsuccessful. You have already registered.');</script>";
                }

                // Close the prepared statements
                mysqli_stmt_close($checkStmt);
            } else {
                // Invalid event ID
                echo "<script>alert('Invalid event ID.');</script>";
            }
        }
    }
    $result = mysqli_query($conn, $sql);

    // Fetching all the event/news data 
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $eventData[] = $row;
        }
    } else {
        echo "Error in the query: " . mysqli_error($conn);
    }

    // Allow Date to have suffix(exp: st, th)
    function formatWithOrdinalSuffix($date)
    {
        $day = date('j', strtotime($date));
        $suffix = date('S', strtotime($date));
        return $day . $suffix . " " . date('F Y', strtotime($date));
    }

    // Close the result set
    mysqli_free_result($result);

    // Close the connection
    mysqli_close($conn);
    ?>

    <!-- Navigation Bar -->
    <?php include('navigation.php') ?>

    <!-- Title -->
    <h2 class="text-center mt-3 appearing_word">Events/News List</h2>

    <div class="container main_width mb-5">
        <div class="mt-4 p-3 border border-2 border-dark">
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

                <!-- Filter label and dropdown -->
                <div class="col-auto">
                    <select class="form-select me-2" id="filterDropdown" name="filterDropdown">
                        <option value="all" <?php echo ($filterDropdownOption == 'all') ? 'selected' : ''; ?>>All</option>
                        <option value="past" <?php echo ($filterDropdownOption == 'past') ? 'selected' : ''; ?>>Past</option>
                        <option value="upcoming" <?php echo ($filterDropdownOption == 'upcoming') ? 'selected' : ''; ?>>Upcoming</option>
                    </select>
                </div>

                <!-- Button to display list -->
                <div class="col-auto">
                    <button type="submit" name="filterBtn" class="btn btn-success">
                        <img src="img/search_icon.png" alt="Search Icon" width="22" height="22">
                        Display List
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Content Container -->
    <div class="container my-3 main_width">
        <!-- Row of Cards -->
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-12">
                <div class="row d-flex justify-content-center align-items-center">
                    <!-- Check if $eventData is not empty -->
                    <?php if (empty($eventData)) : ?>
                        <!-- Display error message if $eventData is empty -->
                        <div class="col-12 text-center">
                            <h4 class="text-danger"><?php echo $error_msg ?></h4>
                        </div>
                    <?php else : ?>
                        <!-- Card -->
                        <?php foreach ($eventData as $event) : ?>
                            <div class="col-12 mb-5">
                                <div class="card hover_border shadow-lg enlarge_event">
                                    <div class="card-body py-5">
                                        <div class="row d-flex justify-content-center align-items-center">
                                            <!-- Left side with image -->
                                            <div class="col-md-4 text-center">
                                                <div class="border border-2 border-dark rounded p-2 py-3">
                                                    <img src="<?php echo $event['photo']; ?>" class="img-fluid" alt="Photo">
                                                </div>
                                            </div>
                                            <!-- Right side with text -->
                                            <!-- Title -->
                                            <div class="col-md-8">
                                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                                    <h2 class="card-title text-dark">
                                                        <?php echo $event['title']; ?>
                                                    </h2>

                                                    <!-- Green badge indicating "Events", Yellow badge indicating "News" -->
                                                    <span class="badge <?php echo ($event['type'] == 'events') ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                                        <?php echo ucfirst($event['type']); ?>
                                                    </span>
                                                </div>

                                                <!-- Location + Event Date -->
                                                <div class="card-text mb-3">
                                                    <h5>
                                                        <?php echo $event['location']; ?> |
                                                        <span class="text-primary"><?php echo formatWithOrdinalSuffix($event['event_date']); ?></span>
                                                    </h5>
                                                </div>

                                                <!-- Description -->
                                                <p class="card-text text_justify"><?php echo $event['description']; ?></p>

                                                <!-- Conditionally display "Sign Up" button for events -->
                                                <?php if ($event['type'] == 'events') : ?>
                                                    <form method="post" id="signUp_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                                        <!-- Hidden input for the event ID -->
                                                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">

                                                        <button type="submit" name="btn_signUp" class="btn btn-lg btn-outline-primary px-3 shadow-sm" type="button">Sign Up</button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>