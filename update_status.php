<?php
include("config.php");

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);

    // Update the status in the database
    $query = "UPDATE appointments SET status = '$status' WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: docapprove.php"); // Redirect back to the appointments page
        exit();
    } else {
        echo "Error updating status: " . mysqli_error($conn);
    }
}
?>
