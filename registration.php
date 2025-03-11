<!DOCTYPE html>
<html lang="en">

<head>
    <title>Registration</title>
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
    <!-- Include -->
    <?php include('process_register.php') ?>

    <!-- Container -->
    <div class="container-fluid py-5">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-lg-7">
                <div class="card hover_border lightblue_border shadow-lg">
                    <div class="card-body p-4 p-md-5">
                        <!-- Heading -->
                        <h2 class="text-center mb-4 pb-2">Registration Form</h2>
                        <!-- Form -->
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="row">
                                <!-- Username -->
                                <label for="username" class="form-label ps-3 d-block">Username</label>
                                <div class="col-md-6 mb-4">
                                    <!-- First Name -->
                                    <div class="form-outline">
                                        <input type="text" id="firstName" name="firstName" placeholder="First Name" class="form-control" value="<?php echo $firstName; ?>"/>
                                        <span class="text-danger"><?php echo $firstName_error; ?></span>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <!-- Last Name -->
                                    <div class="form-outline">
                                        <input type="text" id="lastName" name="lastName" placeholder="Last Name" class="form-control" value="<?php echo $lastName; ?>"/>
                                        <span class="text-danger"><?php echo $lastName_error; ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Date Of Birth -->
                                <div class="col-md-6 mb-4">
                                    <label for="dateOfBirth" class="form-label">Date Of Birth</label>
                                    <div class="form-outline">
                                        <input type="date" id="dateOfBirth" name="dateOfBirth" class="form-control" value="<?php echo $dateOfBirth; ?>"/>
                                        <span class="text-danger"><?php echo $dateOfBirth_error; ?></span>
                                    </div>
                                </div>
                                <!-- Gender -->
                                <div class="col-md-6 mb-4">
                                    <label for="gender" class="form-label mb-2 pb-1">Gender</label><br />
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="gender" value="Female" <?php echo (empty($gender) || $gender === "Female") ? "checked" : ""; ?> />
                                        <label class="form-check-label" for="gender">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="gender" value="Male" <?php echo ($gender === "Male") ? "checked" : ""; ?> />
                                        <label class="form-check-label" for="gender">Male</label>
                                    </div>
                                </div>
                            </div>
                            <!-- Email -->
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" id="email" name="email" placeholder="Email" class="form-control" value="<?php echo $email; ?>"/>
                                        <span class="text-danger"><?php echo $email_error; ?></span>
                                    </div>
                                </div>
                                <!-- Hometown -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <label for="hometown" class="form-label">Hometown</label>
                                        <input type="text" id="hometown" name="hometown" placeholder="Hometown" class="form-control" value="<?php echo $hometown; ?>"/>
                                        <span class="text-danger"><?php echo $hometown_error; ?></span>
                                    </div>
                                </div>
                            </div>
                            <!-- Password -->
                            <div class="row">
                                <label for="password" class="form-label">Password</label>
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <input type="text" id="password" name="password" placeholder="Password" class="form-control hide_password" value="<?php echo $password; ?>"/>
                                        <span class="text-danger"><?php echo $password_error; ?></span>
                                    </div>
                                </div>
                                <!-- Confirm Password -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline">
                                        <input type="text" id="cfmPassword" name="cfmPassword" placeholder="Confirm Password" class="form-control hide_password" value="<?php echo $confirm_password; ?>"/>
                                        <span class="text-danger"><?php echo $confirm_password_error; ?></span>
                                    </div>
                                </div>
                            </div>
                            <!-- Button -->
                            <div class="d-flex justify-content-end mt-2">
                                <a class="btn btn-primary btn-lg me-3" href="registration.php" role="button">Reset</a>
                                <input class="btn btn-danger btn-lg" type="submit" value="Register" />
                            </div>
                            <!-- Go to Login Page -->
                            <p class="text-muted text-center mt-4 mb-0">Already have an account? <a href="login.php">Login here!</a></p>

                            <!-- Existing account error message -->
                            <div class="text-center">
                                <span class="text-danger"><strong><?php echo $account_exist_error; ?></strong></span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>