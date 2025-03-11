<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Web development">
    <meta name="keywords" content="HTML, CSS, JavaScript">
    <meta name="author" content="Yien Yang CHOO">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="style/style.css" rel="stylesheet" />
</head>

<body class="white_background">
    <?php
    // Initialize
    $email = $password = $email_error = $password_error = $login_error = "";

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

        // Validation for email
        if (empty(trim($_POST["email"]))) {
            $email_error = "*Email is required";
        } elseif (!preg_match('/^\w+@([a-z_]+?\.)+[a-z]{2,3}$/', trim($_POST["email"]))) {
            $email_error = "*Invalid email format";
        } else {
            $email = trim($_POST["email"]);
        }

        // Validation for password
        if (empty(trim($_POST["password"]))) {
            $password_error = "*Password is required";
        } else {
            $password = trim($_POST["password"]);
        }

        if (empty($email_error) && empty($password_error)) {
            // Retrieve the user data
            $sql = "SELECT * FROM account_table WHERE email = ?";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $emailParam);
                $emailParam = $email;

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);

                    // Check if email exists
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        mysqli_stmt_bind_result($stmt, $email, $hashed_password, $type, $status);
                        if (mysqli_stmt_fetch($stmt)) {
                            // Hash the password using SHA-256
                            $hashedInputPassword = hash('sha256', $password);

                            // Verify the password
                            if ($hashedInputPassword == $hashed_password) {
                                if ($status == "Approve") {
                                    // Start session and set session variables
                                    session_start();
                                    $_SESSION["loginStatus"] = true;
                                    $_SESSION["email"] = $email;
                                    $_SESSION["type"] = $type;

                                    // Display a success alert 
                                    echo "<script>alert('Login successful!');</script>";

                                    if ($type == "user") {
                                        // Redirect to Main Menu Page
                                        echo "<script>location.replace('main_menu.php');</script>";
                                    } else if ($type == "admin") {
                                        // Redirect to Main Menu Admin Page
                                        echo "<script>location.replace('main_menu_admin.php');</script>";
                                    }
                                    exit();
                                }else{
                                    // Display error message
                                    $login_error = "Your account's status is not Approve yet!";
                                }
                            } else {
                                $login_error = "Login failed. Invalid password. Please try again";
                            }
                        }
                    } else {
                        $login_error = "Login failed. Invalid email. Please try again";
                    }
                }
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        }

        // Close the connection
        mysqli_close($conn);
    }
    ?>

    <!-- Container -->
    <div class="container-fluid pt-5 mt-2 mb-5">
        <!-- Row -->
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-11">
                <div class="card hover_border lightblue_border shadow-lg">
                    <div class="card-body">
                        <div class="row d-flex justify-content-center align-items-center">
                            <!-- Column -->
                            <div class="col-md-4 text-center">
                                <!-- Sign In Image -->
                                <img src="img/signin-image.jpg" alt="SignInPicture" class="img-fluid w-100 h-100">
                            </div>
                            <div class="col-md-8">
                                <!-- Heading -->
                                <h2>Please Log In</h2>
                                <!-- Form -->
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <div class="row">
                                        <!-- Email Address -->
                                        <label for="email" class="form-label ps-3 d-block">Email Address</label>
                                        <div class="col mb-4">
                                            <div class="form-outline">
                                                <input type="email" id="email" name="email" class="form-control" value="<?php echo $email; ?>" />
                                                <span class="text-danger"><?php echo $email_error; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- Password -->
                                        <label for="password" class="form-label ps-3 d-block">Password</label>
                                        <div class="col mb-4">
                                            <div class="form-outline">
                                                <input type="password" id="password" name="password" class="form-control" value="<?php echo $password; ?>" />
                                                <span class="text-danger"><?php echo $password_error; ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Button -->
                                    <input class="btn btn-outline-primary btn-lg ps-5 pe-5" type="submit" value="Login" />

                                    <!-- Go to Register Page -->
                                    <p class=" mt-4 mb-0"><strong>Don't have an account? <a href="registration.php" class="link-danger text-decoration-none link-opacity-75-hover">Register here!</a></strong></p>

                                    <!-- Existing account error message -->
                                    <span class="text-danger h5"><strong><?php echo $login_error; ?></strong></span>
                                </form>
                            </div>
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