<!DOCTYPE html>
<html lang="en">

<head>
    <title>ViewAdvertisements</title>
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
    $filterDropdownOption = isset($_POST["filterDropdown"]) ? $_POST["filterDropdown"] : "all";

    // Initialize array to store advertisement data
    $advertisementData = array();

    // Initialize error message
    $error_msg = "There are no Advertisement in the Database!";

    // Retrieve all advertisement data from the database by default
    $sql = "SELECT * FROM advertisement_table";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Filter button clicked
        if (isset($_POST["filterBtn"])) {
            $filterDropdownOption = isset($_POST["filterDropdown"]) ? $_POST["filterDropdown"] : "";

            // Filter advertisements based on the selected drop-down menu
            // Filter based on All
            if ($filterDropdownOption == "all") {
                $sql = "SELECT * FROM advertisement_table";

                // Error message for no Advertisements in database
                $error_msg = "There are no Advertisements in the Database!";

                // Filter based on Engineering
            } elseif ($filterDropdownOption == "engineering") {
                $sql = "SELECT * FROM advertisement_table WHERE category = 'engineering'";

                // Error message for no Engineering Advertisements in database
                $error_msg = "There are no Engineering Advertisements in the Database!";

                // Filter based on IT
            } elseif ($filterDropdownOption == "it") {
                $sql = "SELECT * FROM advertisement_table WHERE category = 'it'";

                // Error message for no IT Advertisements in database
                $error_msg = "There are no IT Advertisements in the Database!";

                // Filter based on Business
            } elseif ($filterDropdownOption == "business") {
                $sql = "SELECT * FROM advertisement_table WHERE category = 'business'";

                // Error message for no Business Advertisements in database
                $error_msg = "There are no Business Advertisements in the Database!";

                // Filter based on Design
            } elseif ($filterDropdownOption == "design") {
                $sql = "SELECT * FROM advertisement_table WHERE category = 'design'";

                // Error message for no Design Advertisements in database
                $error_msg = "There are no Design Advertisements in the Database!";
            }
        }
    }
    $result = mysqli_query($conn, $sql);

    // Fetching all the advertisements data 
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $advertisementData[] = $row;
        }
    } else {
        echo "Error in the query: " . mysqli_error($conn);
    }

    // Close the result set
    mysqli_free_result($result);

    // Close the connection
    mysqli_close($conn);
    ?>

    <!-- Navigation Bar -->
    <?php include('navigation.php') ?>

    <!-- Title -->
    <h2 class="text-center mt-3 appearing_word">Advertisements</h2>

    <div class="container main_width">
        <div class="mt-4 p-3 border border-2 border-dark">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="row g-3 align-items-center">

                <!-- Filter label and dropdown -->
                <div class="col-auto">
                    <label for="filterDropdown">Category:</label>
                </div>

                <div class="col-auto">
                    <select class="form-select me-2" id="filterDropdown" name="filterDropdown">
                        <option value="all" <?php echo ($filterDropdownOption == 'all') ? 'selected' : ''; ?>>All</option>
                        <option value="engineering" <?php echo ($filterDropdownOption == 'engineering') ? 'selected' : ''; ?>>Engineering</option>
                        <option value="it" <?php echo ($filterDropdownOption == 'it') ? 'selected' : ''; ?>>IT</option>
                        <option value="business" <?php echo ($filterDropdownOption == 'business') ? 'selected' : ''; ?>>Business</option>
                        <option value="design" <?php echo ($filterDropdownOption == 'design') ? 'selected' : ''; ?>>Design</option>
                    </select>
                </div>

                <!-- Button to display list -->
                <div class="col-auto">
                    <button type="submit" name="filterBtn" class="btn btn-success">
                        <img src="img/search_icon.png" alt="Search Icon" width="22" height="22">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Content Container -->
    <div class="container-fluid mt-4 main_width">
        <!-- Row of Cards -->
        <div class="row">
            <!-- Check if $advertisementData is not empty -->
            <?php if (empty($advertisementData)) : ?>
                <!-- Display error message if $advertisementData is empty -->
                <div class="col-12 text-center">
                    <h4 class="text-danger"><?php echo $error_msg ?></h4>
                </div>
            <?php else : ?>
                <!-- Card -->
                <?php foreach ($advertisementData as $advertisement) : ?>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 mb-5 d-flex justify-content-center">
                        <div class="card hover_border shadow-lg enlarge_event">
                            <!-- Photo -->
                            <img src="<?php echo $advertisement['photo']; ?>" class="card-img-top" alt="Photo">
                            <!-- Advertisement Contents -->
                            <div class="card-body">
                                <!-- Title -->
                                <h5 class="card-title">
                                    <?php echo $advertisement['title']; ?>
                                </h5>

                                <!-- Category -->
                                <p class="card-text">
                                    Category: <?php echo ucfirst($advertisement['category']); ?>
                                </p>

                                <!-- Description -->
                                <p class="card-text text_justify"><?php echo $advertisement['description']; ?></p>

                                <!-- Email -->
                                <p class="card-text">
                                    <img src="img/mail.png" alt="Mail Icon" width="25" height="25" class="me-2">
                                    <a href="mailto:<?php echo $advertisement['email']; ?>"><?php echo $advertisement['email']; ?></a>
                                </p>

                                <!-- Conditionally display "For more info" button for advertisement -->
                                <?php if ($advertisement['url'] != "") : ?>
                                    <!-- Button to company website of the advertisement -->
                                    <a href="<?php echo $advertisement['url']; ?>" target="_blank" class="btn btn-outline-danger px-3 shadow-sm" role="button">For More Info</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>