<?php
include "config.php"; // Include your database configuration file

session_start();

// Handle Patient Signup
if (isset($_POST['submit_patient_signup'])) {
    if (isset($_POST['signup_patient_name']) && isset($_POST['signup_patient_email']) && isset($_POST['signup_patient_password'])) {
        $signup_name = $_POST['signup_patient_name'];
        $signup_email = $_POST['signup_patient_email'];
        $signup_pass = $_POST['signup_patient_password'];

        // Check if the email already exists
        $check_email = mysqli_query($conn, "SELECT * FROM `patients` WHERE email = '$signup_email'") or die('Query Failed');
        if (mysqli_num_rows($check_email) > 0) {
            $error = "Email is already registered!";
        } else {
            // Insert new patient into the database without password hashing
            $insert_patient = mysqli_query($conn, "INSERT INTO `patients` (name, email, password) VALUES ('$signup_name', '$signup_email', '$signup_pass')") or die('Query Failed');
            if ($insert_patient) {
                $_SESSION['user_name'] = $signup_name; // Store patient name in session
                header('location:index.html'); // Redirect to the home page
                exit();
            } else {
                $error = "Signup failed! Please try again.";
            }
        }
    }
}

// Handle Patient Login
if (isset($_POST['submit_patient_login'])) {
    if (empty($_POST['patient_email']) || empty($_POST['patient_password'])) {
        $error = "Please enter both username and password.";
    } else {
        $email = $_POST['patient_email'];
        $pass = $_POST['patient_password'];
        //$_SESSION['name'] = $name;
       echo $email;
       echo $pass;
        // Check if the user is a patient
        $select_patients = mysqli_query($conn, "SELECT * FROM `patients` WHERE email = '$email' AND password = '$pass'") or die('Query Failed');
        if (mysqli_num_rows($select_patients) > 0) {
           // $_SESSION['user_id'] = $name; // Store patient name in session

            $patient = mysqli_fetch_assoc($select_patients);
            $_SESSION['email'] = $email;
            $_SESSION['user_name']=$patient['name'];
            header('location:index.html'); // Redirect to the home page
            exit();
        } else {
            $error = "Invalid login credentials!";
        }
    }
}

// Handle Doctor Signup
if (isset($_POST['submit_doctor_signup'])) {
    if (isset($_POST['signup_doctor_name']) && isset($_POST['signup_doctor_email']) && isset($_POST['signup_doctor_password']) && isset($_POST['signup_doctor_department'])) {
        $signup_doctor_name = $_POST['signup_doctor_name'];
        $signup_doctor_email = $_POST['signup_doctor_email'];
        $signup_doctor_pass = $_POST['signup_doctor_password'];
        $signup_doctor_department = $_POST['signup_doctor_department'];

        // Check if the email already exists
        $check_email = mysqli_query($conn, "SELECT * FROM `doctors` WHERE email = '$signup_doctor_email'") or die('Query Failed');
        if (mysqli_num_rows($check_email) > 0) {
            $error = "Email is already registered!";
        } else {
            // Insert new doctor into the database without password hashing
            $insert_doctor = mysqli_query($conn, "INSERT INTO `doctors` (name, email, password, department) VALUES ('$signup_doctor_name', '$signup_doctor_email', '$signup_doctor_pass', '$signup_doctor_department')") or die('Query Failed');
            if ($insert_doctor) {
                $_SESSION['doctor_name'] = $signup_doctor_name; // Store doctor name in session
                header('location:docapprove.php'); // Redirect to doctor approval page
                exit();
            } else {
                $error = "Signup failed! Please try again.";
            }
        }
    }
}

// Handle Doctor Login
if (isset($_POST['submit_doctor_login'])) {
    if (empty($_POST['doctor_email']) || empty($_POST['doctor_password'])) {
        $error = "Please enter both email and password.";
    } else {
        $doctor_email = $_POST['doctor_email'];
        $doctor_pass = $_POST['doctor_password'];
        $_SESSION['doctor_email'] = $doctor_email;

        // Check if the doctor exists by email and password
        $select_doctors = mysqli_query($conn, "SELECT * FROM `doctors` WHERE email = '$doctor_email' AND password = '$doctor_pass'") or die('Query Failed');
        if (mysqli_num_rows($select_doctors) > 0) {
            $doctor = mysqli_fetch_assoc($select_doctors);
            $_SESSION['doctor_id'] = $doctor_email;
            $_SESSION['doctor_name']=$doctor['name']; // Store doctor email in session
            header('location:docapprove.php'); // Redirect to doctor approval page
            exit();
        } else {
            $error = "Invalid login credentials!";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Login - Healthcare System</title>

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">
    <header id="header" class="header sticky-top">
        <div class="branding d-flex align-items-center">
            <div class="container position-relative d-flex align-items-center justify-content-between">
                <a href="index.html" class="logo d-flex align-items-center me-auto">
                    <h1 class="sitename">Karpagam</h1>
                </a>
            </div>
        </div>
    </header>

    <main class="main">
        <section id="hero" class="hero section light-background">
            <div class="container position-relative">
                <div class="welcome position-relative">
                    <h2>Welcome to Karpagam</h2>
                    <p>Login or Sign Up to manage your healthcare information.</p>
                </div>
                <div class="content row gy-4">
                    <div class="col-lg-12 d-flex align-items-stretch">
                        <div class="form-box w-100">
                            <!-- Tabs for Doctor Login, Patient Login, Patient Signup, and Doctor Signup -->
                            <ul class="nav nav-tabs" id="loginSignupTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="patient-login-tab" data-bs-toggle="tab" href="#patient-login" role="tab" aria-controls="patient-login" aria-selected="true">Patient Login</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="patient-signup-tab" data-bs-toggle="tab" href="#patient-signup" role="tab" aria-controls="patient-signup" aria-selected="false">Patient Signup</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="doctor-login-tab" data-bs-toggle="tab" href="#doctor-login" role="tab" aria-controls="doctor-login" aria-selected="false">Doctor Login</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="doctor-signup-tab" data-bs-toggle="tab" href="#doctor-signup" role="tab" aria-controls="doctor-signup" aria-selected="false">Doctor Signup</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="loginSignupTabContent">
                                <!-- Patient Login Tab -->
                                <div class="tab-pane fade show active" id="patient-login" role="tabpanel" aria-labelledby="patient-login-tab">
                                    <div class="card mt-3">
                                        <div class="card-header bg-secondary text-white">
                                            Patient Login
                                        </div>
                                        <div class="card-body">
                                            <form id="patientLoginForm" action="login.php" method="post">
                                                <div class="form-group">
                                                    <label for="patient_name">Useremail:</label>
                                                    <input type="text" id="patient_name" name="patient_email" required class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="patient_password">Password:</label>
                                                    <input type="password" id="patient_password" name="patient_password" required class="form-control">
                                                </div>
                                                <button type="submit" name="submit_patient_login" class="btn btn-secondary mt-3">Login</button>
                                                <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Patient Signup Tab -->
                                <div class="tab-pane fade" id="patient-signup" role="tabpanel" aria-labelledby="patient-signup-tab">
                                    <div class="card mt-3">
                                        <div class="card-header bg-success text-white">
                                            Patient Signup
                                        </div>
                                        <div class="card-body">
                                            <form id="patientSignupForm" action="login.php" method="post">
                                                <div class="form-group">
                                                    <label for="signup_patient_name">Name:</label>
                                                    <input type="text" id="signup_patient_name" name="signup_patient_name" required class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="signup_patient_email">Email:</label>
                                                    <input type="email" id="signup_patient_email" name="signup_patient_email" required class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="signup_patient_password">Create Password:</label>
                                                    <input type="password" id="signup_patient_password" name="signup_patient_password" required class="form-control">
                                                </div>
                                                <button type="submit" name="submit_patient_signup" class="btn btn-success mt-3">Sign Up</button>
                                                <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>
<!-- Doctor Login Tab -->
<div class="tab-pane fade" id="doctor-login" role="tabpanel" aria-labelledby="doctor-login-tab">
    <div class="card mt-3">
        <div class="card-header bg-primary text-white">
            Doctor Login
        </div>
        <div class="card-body">
            <form id="doctorLoginForm" action="login.php" method="post">
                <div class="form-group">
                    <label for="doctor_email">Doctor Email:</label>
                    <input type="email" id="doctor_email" name="doctor_email" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="doctor_password">Password:</label>
                    <input type="password" id="doctor_password" name="doctor_password" required class="form-control">
                </div>
                <button type="submit" name="submit_doctor_login" class="btn btn-primary mt-3">Login</button>
                <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
            </form>
        </div>
    </div>
</div>


                                <!-- Doctor Signup Tab -->
                                <div class="tab-pane fade" id="doctor-signup" role="tabpanel" aria-labelledby="doctor-signup-tab">
                                    <div class="card mt-3">
                                        <div class="card-header bg-info text-white">
                                            Doctor Signup
                                        </div>
                                        <div class="card-body">
                                            <form id="doctorSignupForm" action="login.php" method="post">
                                                <div class="form-group">
                                                    <label for="signup_doctor_name">Doctor Name:</label>
                                                    <input type="text" id="signup_doctor_name" name="signup_doctor_name" required class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="signup_doctor_email">Email:</label>
                                                    <input type="email" id="signup_doctor_email" name="signup_doctor_email" required class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="signup_doctor_password">Create Password:</label>
                                                    <input type="password" id="signup_doctor_password" name="signup_doctor_password" required class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="signup_doctor_department">Department:</label>
                                                    <select id="signup_doctor_department" name="signup_doctor_department" class="form-control" required>
                                                        <option value="Cardiology">Cardiology</option>
                                                        <option value="Eye Care">Eye Care</option>
                                                        <option value="Hepatology">Hepatology</option>
                                                        <!-- Add more departments as needed -->
                                                    </select>
                                                </div>
                                                <button type="submit" name="submit_doctor_signup" class="btn btn-info mt-3">Sign Up</button>
                                                <div class="mt-2">
                                                    <p>Already have an account? <a href="#doctor-login" id="goToDoctorLogin" onclick="showDoctorLogin()">Login here</a></p>
                                                </div>
                                                <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="footer" class="footer">
        <div class="container">
            <div class="copyright">
                &copy; 2024 Karpagam. All Rights Reserved.
            </div>
        </div>
    </footer>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        function showPatientLogin() {
            // Activate the Patient Login tab
            var patientLoginTab = new bootstrap.Tab(document.getElementById('patient-login-tab'));
            patientLoginTab.show(); // Show the Patient Login tab
        }

        function showDoctorLogin() {
            // Activate the Doctor Login tab
            var doctorLoginTab = new bootstrap.Tab(document.getElementById('doctor-login-tab'));
            doctorLoginTab.show(); // Show the Doctor Login tab
        }
    </script>
</body>
</html>
