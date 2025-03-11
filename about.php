<!DOCTYPE html>
<html lang="en">

<head>
    <title>About</title>
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
    <h1 class="text-center p-4">About this Assignment</h1>
    <div class="container-fluid px-5">
        <div class="accordion w-100 lightblue_border hover_border" id="projectAccordion">
            <!-- First Question -->
            <div class="accordion-item">
                <div class="bg-dark text-light text-center p-3">
                    <h2>Assignment 2</h2>
                </div>
                <h2 class="accordion-header" id="headingTasks">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTasks" aria-expanded="false" aria-controls="collapseTasks">
                        <strong>What tasks have you not attempted or not completed?</strong>
                    </button>
                </h2>
                <div id="collapseTasks" class="accordion-collapse collapse" aria-labelledby="headingTasks" data-bs-parent="#projectAccordion">
                    <div class="accordion-body">
                        <ul>
                            <li>I have attempted and completed all the tasks.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Second Question -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTrouble">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTrouble" aria-expanded="false" aria-controls="collapseTrouble">
                        <strong>Which parts did you have trouble with?</strong>
                    </button>
                </h2>
                <div id="collapseTrouble" class="accordion-collapse collapse" aria-labelledby="headingTrouble" data-bs-parent="#projectAccordion">
                    <div class="accordion-body text_justify">
                        <ul>
                            <li>
                                While working on the task of uploading a profile photo to update the user's image, I encountered an issue preventing real-time updates. After thorough constantly testing, I identified the problem to be associated with browser caching. Once I pinpointed the issue, I was able to quickly fixed the problem.
                            </li>
                            <li>
                                While working on the task of editing event details, I encountered an issue where I couldn't revert back to the previous information stored in the database after intentionally making a mistake, like leaving the event title blank after clicking the edit button. Initially, I used the POST method to send the event ID to the edit event page, but it led to the problem described above. As a solution, I switched to the GET method, and the outcome matched my expectations.
                            </li>
                            <li>
                                Initially, when i was working on uploading photos and resumes to the server, I faced challenges coming up and creating the code for it, particularly regarding on how to search and remove the old file and prevent a file with the same name from replacing the one in the server. To overcome these challenges, I conducted thorough research, consulting various sources, with W3Schools proving to be very helpful as the examples given at there addressed my issues, allowing me to resolve these problems smoothly.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Third Question -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingNextTime">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNextTime" aria-expanded="false" aria-controls="collapseNextTime">
                        <strong>What would you like to do better next time?</strong>
                    </button>
                </h2>
                <div id="collapseNextTime" class="accordion-collapse collapse" aria-labelledby="headingNextTime" data-bs-parent="#projectAccordion">
                    <div class="accordion-body text_justify">
                        <ul>
                            <li>
                                If there's a next time, I would like to enhance the design of my interface to make it more modern and user-friendly, providing an improved of user experience. One aspect I aim to focus on is refining the color theme to create a more visually appealing website. Additionally, I plan to make the navigation easier and improve the overall layout for a smoother and more engaging user interaction.
                            </li>
                            <li>
                                If there's a next time, I would like to enhance the website's functionality by adding more advanced features. For example, on the advertisement page, I might replace the drop down menu with a search bar. This way, users can easily search for any category of the advertisement without the need for adding new menu everytime when there's a new category added.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Fourth Question -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFeatures">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFeatures" aria-expanded="false" aria-controls="collapseFeatures">
                        <strong>What extension features/extra challenges have you done, or attempted, when creating the site?</strong>
                    </button>
                </h2>
                <div id="collapseFeatures" class="accordion-collapse collapse" aria-labelledby="headingFeatures" data-bs-parent="#projectAccordion">
                    <div class="accordion-body text_justify">
                        <ul>
                            <li>Ext 5.1: Advertisement Page Module</li>
                            <li>Ext 5.2: Add advertisement Page</li>
                            <li>All webpages are responsive, adapting seamlessly to various screen sizes, including small, medium, and large.</li>
                            <li>In the edit event page, users cannot attempt to access other non existing event ID by modifying the URL. Doing so will result in a pop-up alert, informing them that they can't do that.</li>
                            <li>For the update profile photo and upload resume in the update profile page, when user updated a new profile photo or a new resume, the old profile photo or resume stored inside the server will get deleted and be replace by the new one.</li>
                            <li>Alert box will pop up informing user regarding their interactions with the website when opeations are performed such as creating or deleting an event successfully.</li>
                            <li>When input validation failed in the edit event page, the inputs in the input field will be revert back to the previous one (which are inputs saved in the database).</li>
                            <li>Everytime when photo and resume of the user is upload to the server, they will be renamed as the email of the user to make sure those files are unique only to the user. This applies to the event and advertisement photo as well where the naming for the photo will be the name of the event/advertisement plus the time when the user upload the photo to make sure it is unique to prevent accidents of replacing other files from occuring.</li>
                            <li>When the user email is updated, the name for the profile image and resume of that user will be updated as well.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Link to the video presentation -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingVideo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVideo" aria-expanded="false" aria-controls="collapseVideo">
                        <strong>Video</strong>
                    </button>
                </h2>
                <div id="collapseVideo" class="accordion-collapse collapse" aria-labelledby="headingVideo" data-bs-parent="#projectAccordion">
                    <div class="accordion-body">
                        <a href="https://www.youtube.com/watch?v=Mrp4FVC9zPU" target="_blank" class="fw-bold text-dark">My Video</a>
                    </div>
                </div>
            </div>

            <!--  Links to the Index Page  -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingHome">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHome" aria-expanded="false" aria-controls="collapseHome">
                        <strong>Home</strong>
                    </button>
                </h2>
                <div id="collapseHome" class="accordion-collapse collapse" aria-labelledby="headingHome" data-bs-parent="#projectAccordion">
                    <div class="accordion-body">
                        <a href="index.php" class="fw-bold text-dark">Home Page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>