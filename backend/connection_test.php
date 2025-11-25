<?php
// Database connection details
$host = "localhost";        // Your local server
$user = "root";             // Default MySQL username
$password = "";             // Default MySQL password (empty)
$database = "event_ticketing";   // Your database name

// Create connection
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("❌ Connection Failed: " . mysqli_connect_error());
}

echo "✅ Database Connected Successfully!";
?>
