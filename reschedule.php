<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_id = $_POST['appointment_id'];
    $new_date = $_POST['new_date'];

    // Update the appointment in the database
    $query = "UPDATE appointments SET appointment_date = '$new_date', status = 'Pending', reschedule = TRUE WHERE id = '$appointment_id'";
    
    if (mysqli_query($conn, $query)) {
        echo "Appointment rescheduled successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    
    // Redirect back to the appointment status page
    header("Location: dropdown.php");
    exit();
}
?>
