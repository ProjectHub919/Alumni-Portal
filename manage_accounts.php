<!DOCTYPE html>
<html lang="en">

<head>
    <title>ManageAccounts</title>
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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $emailPost = $_POST["email"];

        // Approve button clicked
        if (isset($_POST["approveBtn"])) {
            // Update the status to 'Approve' in the database
            $updateQuery = "UPDATE account_table SET status = 'Approve' WHERE email = '$emailPost'";
            mysqli_query($conn, $updateQuery);
            echo "<script>location.replace('manage_accounts.php');</script>";
            exit();
        }

        // Reject button clicked
        if (isset($_POST["rejectBtn"])) {
            // Update the status to 'Reject' in the database
            $updateQuery = "UPDATE account_table SET status = 'Reject' WHERE email = '$emailPost'";
            mysqli_query($conn, $updateQuery);
            echo "<script>location.replace('manage_accounts.php');</script>";
            exit();
        }

        // Delete button clicked
        if (isset($_POST["deleteBtn"])) {
            // Delete the account data from account table
            $deleteAccountQuery = "DELETE FROM account_table WHERE email = '$emailPost'";
            mysqli_query($conn, $deleteAccountQuery);

            // Delete the user data from user table
            $deleteUserQuery = "DELETE FROM user_table WHERE email = '$emailPost'";
            mysqli_query($conn, $deleteUserQuery);
            echo "<script>location.replace('manage_accounts.php');</script>";
            exit();
        }
    }
    ?>

    <!-- Navigation Bar -->
    <?php include('admin_navigation.php') ?>
    <h2 class="text-center mt-3 appearing_word">Manage Users' Account</h2>

    <!-- Table -->
    <div class="container mt-4">
        <div class="table-responsive">
            <table class="table table_dark_borders table-hover">
                <thead>
                    <?php
                    $sql = "SELECT a.email, a.status, u.profile_image, u.first_name, u.last_name, u.gender
                                FROM account_table a
                                JOIN user_table u ON a.email = u.email
                                WHERE a.type != 'admin'";

                    $result = mysqli_query($conn, $sql);

                    if ($result) {
                        if (mysqli_num_rows($result) > 0) {
                    ?>
                            <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">Profile Images</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Approve</th>
                                <th scope="col">Delete</th>
                            </tr>
                </thead>
                <tbody>
                    <?php
                        $count = 1;

                        while ($row = mysqli_fetch_assoc($result)) {
                            $profileImage = $row["profile_image"];
                            $name = $row["first_name"] . " " . $row["last_name"];
                            $email = $row['email'];
                            $status = $row['status'];
                            $gender = $row['gender'];

                            // Determine button states based on status
                            $approveDisabled = ($status == 'Approve') ? 'disabled' : '';
                            $rejectDisabled = ($status == 'Reject') ? 'disabled' : '';
                    ?>
                        <tr>
                            <th scope="row" class="text-center align-middle"><?php echo $count++; ?></th>
                            <td class="text-center align-middle">
                                <?php
                                // Check the gender and load the corresponding avatar (For user that dont have profile image)
                                $defaultAvatarSrc = ($gender == 'Male') ? 'profile_images/male_avatar.png' : 'profile_images/female_avatar.png';

                                // If user got their own profile image, use it instead
                                if (!empty($profileImage)) {
                                    $defaultAvatarSrc = $profileImage;
                                }

                                ?>
                                <img src="<?php echo $defaultAvatarSrc; ?>" class="img-fluid rounded-circle border border-dark update_profile_img" alt="Profile Image">
                            </td>
                            <td class="text-center align-middle"><?php echo $name; ?></td>
                            <td class="text-center align-middle"><?php echo $email; ?></td>
                            <td class="text-center align-middle">
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <input type="hidden" name="email" value="<?php echo $email; ?>">
                                    <!-- Approve and Reject Button -->
                                    <button type="submit" name="approveBtn" class="btn btn-outline-success Acc_btn" <?php echo $approveDisabled; ?>>Approve</button>
                                    <button type="submit" name="rejectBtn" class="btn btn-outline-danger Acc_btn" <?php echo $rejectDisabled; ?>>Reject</button>
                                </form>
                            </td>
                            <td class="text-center align-middle">
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <input type="hidden" name="email" value="<?php echo $email; ?>">
                                    <!-- Delete Icon -->
                                    <button type="submit" name="deleteBtn" class="delete_btn" onclick="return confirm('Are you sure you want to delete this account?');">
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
                            echo '<strong><p class="text-center text-danger">There are no Data in the Account Table</p></strong>';
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