<?php
include("config.php");

if (isset($_GET['department'])) {
    $department = mysqli_real_escape_string($conn, $_GET['department']); // Sanitize input

    // Prepare and execute the SQL query
    $query = "SELECT name FROM doctors WHERE department = '$department'";
    $result = mysqli_query($conn, $query);

    $doctors = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $doctors[] = $row['name'];
    }

    // Return the JSON response
    echo json_encode($doctors);
}
?>
