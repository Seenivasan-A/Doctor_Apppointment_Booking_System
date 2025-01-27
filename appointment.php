<?php
include "config.php"; // Include your database configuration file

header('Content-Type: application/json'); // Set content type to JSON

// Check connection
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and collect form data
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $appointment_date = $conn->real_escape_string($_POST['date']);
    $department = $conn->real_escape_string($_POST['department']);
    $doctor = $conn->real_escape_string($_POST['doctor']);
    $message = $conn->real_escape_string($_POST['message']);

    // Validate that the required fields are not empty
    if (empty($name) || empty($email) || empty($phone) || empty($appointment_date) || empty($department) || empty($doctor)) {
        echo json_encode(['status' => 'error', 'message' => 'All required fields must be filled out.']);
        exit;
    }

    // SQL query to insert appointment data into the database
    $sql = "INSERT INTO appointments (name, email, phone, appointment_date, department, doctor, message) 
            VALUES ('$name', '$email', '$phone', '$appointment_date', '$department', '$doctor', '$message')";

    // Execute the query and return a JSON response based on the outcome
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Appointment successfully booked!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $conn->error]);
    }

    // Close the connection
    $conn->close();
}
?>
