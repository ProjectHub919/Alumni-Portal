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

    // Initialize
    $firstName = $lastName = $dateOfBirth = $gender = $email = $hometown = $password = $confirm_password = "";
    $firstName_error = $lastName_error = $dateOfBirth_error = $email_error = $hometown_error = $password_error = $confirm_password_error = $account_exist_error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

        // Validation for password
        if (empty(trim($_POST["password"]))) {
            $password_error = "*Password is required";
        } elseif (!preg_match('/^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/', trim($_POST["password"]))) {
            $password_error = "*Your password must contain at least 8 characters with 1 number and 1 symbol";
        } else {
            $password = trim($_POST["password"]);
        }

        // Validation for confirm password
        if (empty(trim($_POST["cfmPassword"]))) {
            $confirm_password_error = "Please confirm your password";
        } else {
            $confirm_password = trim($_POST["cfmPassword"]);
            if ($password != $confirm_password) {
                $confirm_password_error = "*Password and confirm password do not match";
            }
        }

        // If all validation passed
        if (empty($firstName_error) && empty($lastName_error) && empty($dateOfBirth_error) && empty($email_error) && empty($hometown_error) && empty($password_error) && empty($confirm_password_error)) {
            // Check if the email already exists in the database
            $checkEmailQuery = "SELECT * FROM user_table WHERE email = ?";
            $stmt = mysqli_prepare($conn, $checkEmailQuery);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $account_exist_error = "There is an existing account.";
            } else {
                // Hash the password using SHA-256
                $hashedPassword = hash('sha256', $password);

                // Insert user data into the user_table
                $insertUserQuery = "INSERT INTO user_table (email, first_name, last_name, dob, gender, hometown) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $insertUserQuery);
                mysqli_stmt_bind_param($stmt, "ssssss", $email, $firstName, $lastName, $dateOfBirth, $gender, $hometown);
                mysqli_stmt_execute($stmt);

                // Insert user data into the account_table
                $insertAccountQuery = "INSERT INTO account_table (email, `password`, `type`, `status`) VALUES (?, ?, 'user', 'Pending')";
                $stmt = mysqli_prepare($conn, $insertAccountQuery);
                mysqli_stmt_bind_param($stmt, "ss", $email, $hashedPassword);
                mysqli_stmt_execute($stmt);

                // Display a success alert 
                echo "<script>alert('Registration successful!');</script>";

                //Go to login page
                echo "<script>location.replace('login.php');</script>";
                exit();
            }
            // Close the statement
            mysqli_stmt_close($stmt);
        }

        // Close database connection
        mysqli_close($conn);
    }

    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>