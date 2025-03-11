<!DOCTYPE html>
<html lang="en">

<head>
    <title>Main</title>
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
    $conn = mysqli_connect($servername, $username, $password);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        // Create database if it does not exist
        $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
        if (!mysqli_query($conn, $sql)) {
            echo "<script> alert('Database created unsuccessfully!') </script>";
        }

        // Select the database
        mysqli_select_db($conn, $dbname);

        //SQL query to create user_table
        $userTableSql = "CREATE TABLE IF NOT EXISTS user_table (
            email VARCHAR(50) NOT NULL PRIMARY KEY,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            dob DATE NULL,
            gender VARCHAR(6) NOT NULL,
            contact_number VARCHAR(15) NULL,
            hometown VARCHAR(50) NOT NULL,
            current_location VARCHAR(50) NULL,
            profile_image VARCHAR(100) NULL,
            job_position VARCHAR(50) NULL,
            qualification VARCHAR(70) NULL,
            `year` INT(4) NULL,
            university VARCHAR(50) NULL,
            company VARCHAR(50) NULL,
            `resume` VARCHAR(100) NULL
        )";

        if (!mysqli_query($conn, $userTableSql)) {
            echo "<script> alert('User Table created unsuccessfully!') </script>";
        }

        //SQL query to create account_table
        $accountTableSql = "CREATE TABLE IF NOT EXISTS account_table (
            email VARCHAR(50) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `type` VARCHAR(5) NOT NULL,
            `status` VARCHAR(20) NOT NULL,
            FOREIGN KEY (email) REFERENCES user_table(email) ON DELETE CASCADE ON UPDATE CASCADE
        )";

        if (!mysqli_query($conn, $accountTableSql)) {
            echo "<script> alert('Account Table created unsuccessfully!') </script>";
        }

        //SQL query to create event_table
        $eventTableSql = "CREATE TABLE IF NOT EXISTS event_table (
            id INT(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(50) NOT NULL,
            `location` VARCHAR(50) NOT NULL,
            `description` VARCHAR(700) NOT NULL,
            event_date DATE NOT NULL,
            photo VARCHAR(100) NULL,
            `type` VARCHAR(10) NOT NULL
        )";

        if (!mysqli_query($conn, $eventTableSql)) {
            echo "<script> alert('Event Table created unsuccessfully!') </script>";
        }

        //SQL query to create event_registration_table
        $eventRegTableSql = "CREATE TABLE IF NOT EXISTS event_registration_table (
            event_id INT(4) NOT NULL,
            participant_email VARCHAR(50) NOT NULL,
            FOREIGN KEY (event_id) REFERENCES event_table(id) ON DELETE CASCADE ON UPDATE CASCADE
        )";

        if (!mysqli_query($conn, $eventRegTableSql)) {
            echo "<script> alert('Event Registration Table created unsuccessfully!') </script>";
        }

        //SQL query to create advertisement_table
        $advertisementTableSql = "CREATE TABLE IF NOT EXISTS advertisement_table (
            id INT(4) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(50) NOT NULL,
            category VARCHAR(50) NOT NULL,
            `description` VARCHAR(700) NOT NULL,
            photo VARCHAR(100) NULL,
            email VARCHAR(50) NOT NULL,
            `url` VARCHAR(255) NULL
        )";

        if (!mysqli_query($conn, $advertisementTableSql)) {
            echo "<script> alert('Advertisement Table created unsuccessfully!') </script>";
        }

        // Checking whether there's data in user table
        $userTableCountQuery = "SELECT COUNT(*) FROM user_table";
        $userTableCountResult = mysqli_query($conn, $userTableCountQuery);
        $userTableCount = mysqli_fetch_row($userTableCountResult)[0];

        // If no data in user table, add dummy data
        if ($userTableCount == 0) {
            // Populate user_table with dummy data
            $populateUserTableSql = "INSERT INTO user_table (email, first_name, last_name, dob, gender, contact_number, hometown, current_location, profile_image, job_position, qualification, `year`, university, company, `resume`) 
            VALUES 
            ('admin@swin.edu.my', 'Choo', 'Yien Yang', '2003-03-15', 'Male', '0173118298', 'Kuching, Sarawak', 'Kuantan', '', 'Developer', 'Bachelor of Computer Science', 2009, 'Swinburne', 'Google', ''),

            ('johnson@gmail.com', 'Johnson', 'Sim', '2003-04-01', 'Male', '0123119098', 'Kuching, Sarawak', 'Sabah', 'profile_images/johnson@gmail.com.png', 'Developer', 'Bachelor of Computer Science', 2010, 'Swinburne', 'Google', 'resume/johnson@gmail.com.pdf'),

            ('kelly@gmail.com', 'Kelly', 'Smith', '1992-08-15', 'Female', '0198765431', 'Sabah', 'Kuala Lumpur', 'profile_images/kelly@gmail.com.png', 'Manager', 'Bachelor of Arts', 2013, 'Unimas', 'Petronas', 'resume/kelly@gmail.com.pdf'),

            ('ahmad@gmail.com', 'Ahmad', 'Ismail', '1990-02-22', 'Male', '0171122334', 'Penang', 'Penang', 'profile_images/ahmad@gmail.com.png', 'Engineer', 'Bachelor of Electrical Engineering', 2012, 'USM', 'Intel', 'resume/ahmad@gmail.com.pdf'),

            ('tan@gmail.com', 'Tan', 'Chin Swee', '1985-06-10', 'Female', '0165544332', 'Kuala Lumpur', 'Johor Bahru', '', 'Marketing Manager', 'Master of Business Administration', 2015, 'UTM', 'Maybank', ''),

            ('mohammad@gmail.com', 'Mohammad', 'Yusof', '1988-12-05', 'Male', '0139876543', 'Kelantan', 'Kota Kinabalu', '', 'Project Manager', 'Bachelor of Civil Engineering', 2010, 'UM', 'Shell', 'resume/mohammad@gmail.com.pdf'),

            ('siti@gmail.com', 'Siti', 'Rahman', '1994-03-18', 'Female', '0181122334', 'Kuching, Sarawak', 'Melaka', 'profile_images/siti@gmail.com.png', 'Software Developer', 'Bachelor of Information Technology', 2016, 'MMU', 'CIMB', 'resume/siti@gmail.com.pdf')";

            if (!mysqli_query($conn, $populateUserTableSql)) {
                echo "User_table populated with dummy data unsuccessfully!";
            }
        }

        // Checking whether there's data in account table
        $accountTableCountQuery = "SELECT COUNT(*) FROM account_table";
        $accountTableCountResult = mysqli_query($conn, $accountTableCountQuery);
        $accountTableCount = mysqli_fetch_row($accountTableCountResult)[0];

        // If no data in account table, add dummy data
        if ($accountTableCount == 0) {
            // Hash the password using SHA-256
            $hashedPassword = hash('sha256', 'hii1234@');
            $hashedAdminPassword = hash('sha256', 'admin');

            // Populate account_table with admin data
            $populateAccountTableSql = "INSERT INTO account_table (email, `password`, `type`, `status`) 
            VALUES 
            ('admin@swin.edu.my', '$hashedAdminPassword', 'admin', 'Approve'),
            ('johnson@gmail.com', '$hashedPassword', 'user', 'Approve'),
            ('kelly@gmail.com', '$hashedPassword', 'user', 'Approve'),
            ('ahmad@gmail.com', '$hashedPassword', 'user', 'Pending'),
            ('tan@gmail.com', '$hashedPassword', 'user', 'Approve'),
            ('mohammad@gmail.com', '$hashedPassword', 'user', 'Approve'),
            ('siti@gmail.com', '$hashedPassword', 'user', 'Reject')";

            if (!mysqli_query($conn, $populateAccountTableSql)) {
                echo "Account_table populated with admin and dummy user data unsuccessfully";
            }
        }

        // Checking whether there's data in event table
        $eventTableCountQuery = "SELECT COUNT(*) FROM event_table";
        $eventTableCountResult = mysqli_query($conn, $eventTableCountQuery);
        $eventTableCount = mysqli_fetch_row($eventTableCountResult)[0];

        // If no data in event table, add dummy data
        if ($eventTableCount == 0) {
            // Populate event_table with dummy data
            $populateEventTableSql = "INSERT INTO event_table (title, `location`, `description`, event_date, photo, `type`) 
            VALUES 
            ('Glamping With Alumni', 'Farmer''s Market Pavilion', 'Immerse yourself in mountain mists and luxurious landscapes at Tiarasa Escapes, a unique creation by Puan Sn Tiara Jacquelin nestled in the foothills of Genting Highlands that offers an amazing family glamping experience. Having grown up reading Enid Blyton, Puan Sri Tiara captured childhood dreams in this gorgeously crafted, tribal-infused glamping resort - with Safari-inspired tented and treetop villas by the brook, arts and crafts, classic Malaysian games, wading pools, riverside picnic, bonfire, moonlight cinema, and more, that both kids and adults alike will enjoy.', '2023-08-31', 'img/glamping_event.jpg', 'events'),

            ('Friendly Tournament', 'Garden International University', 'Proudly organised by Swinbees and taking place on the last weekend of every month, the Weekend Friendly Series is Little League Soccer''s series of in-house tournaments. Launched in 2018, the series gives players at all Little League training centres the chance to experience real matches in a fun, friendly tournament format. Friendly matches are hosted by a different Little League venue each month, allowing participants to play on different fields across the Klang Valley.', '2023-05-09', 'img/tournament.png', 'events'),

            ('Making a difference through mentoring', 'Swinburne University Sarawak Campus', 'The Swinburne Alumni Mentorship Program was designed to foster meaningful relationships between alumni mentors and student, housestaff, or alumni mentees that will help mentees better navigate their career paths. The program also offers an important opportunity for alumni mentors to engage with the Swinburne community and experience a rewarding relationship with a mentee who is eager to learn from them.', '2023-04-12', 'img/mentorship.png', 'news'),

            ('Tech Expo 2024', 'Kuala Lumpur Convention Centre', 'Explore the latest advancements in technology at Tech Expo 2024. The event will feature keynote speakers, interactive workshops, and cutting-edge demonstrations from leading tech companies. Join us to witness the future of innovation and network with industry experts.', '2024-07-05', 'img/tech_expo.jpg', 'events'),

            ('Culinary Delights Festival', 'Penang Food Street', 'Indulge your taste buds at the Culinary Delights Festival in the heart of Penang. Enjoy a variety of local and international cuisines, cooking demonstrations, and live entertainment. This gastronomic event promises a delightful experience for food enthusiasts and culinary adventurers.', '2024-09-18', 'img/culinary.png', 'events'),

            ('Swinburne Alumni Achievements', 'Swinburne University Sarawak Campus', 'Celebrating the outstanding achievements of Swinburne alumni! Our graduates continue to make a mark in various industries, contributing to innovation and success. Their accomplishments inspire current students and showcase the excellence fostered at Swinburne University.', '2024-03-03', 'img/alumni_achievement.jpg', 'news')";

            if (!mysqli_query($conn, $populateEventTableSql)) {
                echo "Event_table populated with dummy data unsuccessfully";
            }
        }

        // Checking whether there's data in advertisement table
        $advertisementTableCountQuery = "SELECT COUNT(*) FROM advertisement_table";
        $advertisementTableCountResult = mysqli_query($conn, $advertisementTableCountQuery);
        $advertisementTableCount = mysqli_fetch_row($advertisementTableCountResult)[0];

        // If no data in advertisement table, add dummy data
        if ($advertisementTableCount == 0) {
            // Populate advertisement_table with dummy data
            $populateadvertisementTableSql = "INSERT INTO advertisement_table (title, category, `description`, photo, email, `url`) VALUES
            ('Job Opening at XYZ Engineering', 'engineering', 'Exciting engineering job opportunity at XYZ Company. We are seeking highly motivated engineers to join our dynamic team and contribute to cutting-edge projects. Apply now and be part of the future of technology!', 'img/engineering1.png', 'xyz@gmail.com', 'https://malaysia.indeed.com/cmp/Mmp-Construction-Sdn-Bhd?from=mobviewjob&tk=1hg092egigm6h800&fromjk=a2fee4e505a813d3&attributionid=mobvjcmp'),
        
            ('IT Solutions for Your Business', 'it', 'Explore our comprehensive IT solutions tailored to meet the unique needs of businesses. Our expert team provides innovative software solutions, cybersecurity services, and IT consulting. Stay ahead in the digital era with our IT services.', 'img/it_solution.jpg', 'itsolutions@gmail.com', ''),
        
            ('Business Investment Seminar', 'business', 'Join us for an enlightening seminar on profitable business investments. Learn from industry experts, discover lucrative opportunities, and network with fellow entrepreneurs. This seminar is your key to financial success in the business world.', 'img/business_seminar.jpg', 'applo@gmail.com', 'https://academy.pitchin.my/investment-workshop/'),
        
            ('Design Workshop: Creative Trends', 'design', 'Unleash your creativity in our design workshop! Explore the latest creative trends, learn from experienced designers, and get hands-on experience with cutting-edge design tools. Elevate your design skills and be inspired by the world of creativity.', 'img/design_workshop.png', 'alianze@gmail.com', 'https://www.designworkshop.com/'),
        
            ('Software Developer Wanted', 'it', 'Exciting opportunity for a skilled software developer to join ABC Tech. Be part of a dynamic team, work on innovative projects, and contribute to the development of cutting-edge software solutions. Apply now and take your career to new heights!', 'img/it_job.jpg', 'swinburne@gmail.com', ''),
        
            ('Innovation in Product Design', 'design', 'Discover innovative approaches to product design in our exclusive workshop. Learn from industry leaders, explore emerging design technologies, and gain insights into the future of product design. Elevate your skills and stay ahead in the competitive design landscape.', 'img/product_design.jpg', 'silver@gmail.com', '')";

            if (!mysqli_query($conn, $populateadvertisementTableSql)) {
                echo "Advertisement_table populated with dummy data unsuccessfully";
            }
        }
    }

    // Close the connection
    mysqli_close($conn);

    ?>

    <!-- Container -->
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Left Page -->
            <div class="col-lg-5 bg-dark text-center border_main">
                <h1 class="text-light text-center w-100 pt-4 fadeInAnimation">Alumni Portal</h1>
                <h6 class="text-light text_justify p-4 fadeInAnimation">Stay connected with your alma mater and friends. Build networks and propel your career to the next stage.</h6>
                <a class="btn btn-outline-info m-5 px-5 text-white" href="login.php" role="button">Login</a>
                <a class="btn btn-outline-light m-5 px-5" href="registration.php" role="button">Register</a>
            </div>
            <!-- Right Page -->
            <div class="col-lg-7 px-0">
                <img src="img/main_photo.jpg" alt="MainPicture" class="img-fluid h-100 w-100 object-fit-cover">
            </div>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>