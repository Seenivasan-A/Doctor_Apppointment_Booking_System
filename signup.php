<?php
session_start();
include 'config.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the signup form is submitted
    if (isset($_POST['submit_patient_signup'])) {
        $patient_name = trim($_POST['patient_name']);
        $patient_password = trim($_POST['patient_password']);

        // Validate input
        if (empty($patient_name) || empty($patient_password)) {
            echo "Please fill in all the fields.";
            exit;
        }

        // Check if patient already exists in the database
        $stmt = $conn->prepare("SELECT * FROM patients WHERE patient_name = :patient_name");
        $stmt->bindParam(':patient_name', $patient_name);
        $stmt->execute();
        $existingPatient = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingPatient) {
            echo "The patient name already exists. Please choose a different name.";
            exit;
        }

        // Hash the password before storing it
        $hashed_password = password_hash($patient_password, PASSWORD_BCRYPT);

        // Insert the new patient into the database
        $stmt = $conn->prepare("INSERT INTO patients (patient_name, patient_password) VALUES (:patient_name, :patient_password)");
        $stmt->bindParam(':patient_name', $patient_name);
        $stmt->bindParam(':patient_password', $hashed_password);

        if ($stmt->execute()) {
            // Success: Set session and redirect to the patient dashboard or login page
            $_SESSION['patient_name'] = $patient_name;
            header('Location: patient_dashboard.php');
            exit;
        } else {
            // Error inserting the new patient
            echo "There was an error during signup. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Signup</title>
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2>Patient Signup</h2>
        <form action="patient_signup.php" method="post">
            <div class="form-group">
                <label for="patient_name">Patient Name:</label>
                <input type="text" id="patient_name" name="patient_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="patient_password">Password:</label>
                <input type="password" id="patient_password" name="patient_password" class="form-control" required>
            </div>
            <button type="submit" name="submit_patient_signup" class="btn btn-success mt-3">Sign Up</button>
        </form>
    </div>
</body>

</html>
