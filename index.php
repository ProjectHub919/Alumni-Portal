<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home</title>
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
    <!-- Heading -->
    <div class="d-flex align-items-center title_background">
        <h1 class="p-2 text-light text-center w-100 fadeInAnimation">Alumni Portal</h1>
    </div>

    <!-- Container -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card mt-4 shadow-lg p-3 border_round">
                    <div class="card-body">
                        <!-- Profile -->
                        <div class="row">
                            <div class="col-md-4 text-center d-flex flex-column align-items-center mt-3">
                                <img src="img/mypic.png" alt="MyProfile" class="rounded img-fluid hover_border img_profile_width">
                                <p class="text-muted mt-3 mb-1">Computer Science Student</p><br />
                            </div>
                            <div class="col-md-8">
                                <!-- Full Name -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <p class="mb-0"><strong>Full Name</strong></p>
                                    </div>
                                    <div class="col-md-9">
                                        <p class="text-muted mb-0">Choo Yien Yang</p>
                                    </div>
                                </div>
                                <hr />
                                <!-- Student ID -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <p class="mb-0"><strong>Student ID</strong></p>
                                    </div>
                                    <div class="col-md-9">
                                        <p class="text-muted mb-0">102768588</p>
                                    </div>
                                </div>
                                <hr />
                                <!-- Email -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <p class="mb-0"><strong>Email</strong></p>
                                    </div>
                                    <div class="col-md-9">
                                        <p class="text-muted mb-0">102768588@students.swinburne.edu.my</p>
                                    </div>
                                </div>
                                <hr />
                                <!-- Statement -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <p class="mb-0"><strong>Statement</strong></p>
                                    </div>
                                    <div class="col-md-9 mb-0">
                                        <p class="text-muted mb-0 text_justify">I declare that this assignment is my individual work. I have not work collaboratively nor have I copied from any other student's work or from any other source. I have not engaged another party to complete this assignment. I am aware of the University's policy with regards to plagiarism. I have not allowed, and will not allow, anyone to copy my work with the intention of passing it off as his or her own work.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Buttons -->
        <div class="row mt-4 mb-5">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-6 col-12 text-center">
                <!-- When small screen, display these two buttons -->
                <a class="btn btn-outline-primary btn-lg btn-sm d-lg-none" href="main.php" role="button">Main Page</a>
                <a class="btn btn-outline-secondary btn-lg ms-1 btn-sm d-lg-none" href="about.php" role="button">About This Assignment</a>
                <!-- When big screen, display these two buttons -->
                <a class="btn btn-outline-primary btn-lg fs-4 d-none d-lg-inline" href="main.php" role="button">Main Page</a>
                <a class="btn btn-outline-secondary btn-lg fs-4 ms-1 d-none d-lg-inline" href="about.php" role="button">About This Assignment</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>