<?php
$servername = "localhost";
$username = "root"; // Use your MySQL username
$password = "karpagam"; // Use your MySQL password
$dbname = "healthcare_system";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
