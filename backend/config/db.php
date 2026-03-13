<?php
$host = "localhost";
$user = "root";        // XAMPP default
$pass = "";            // XAMPP default
$db   = "event_ticketing_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>
