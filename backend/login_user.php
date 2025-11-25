<?php
// db.php - DB connection
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'event_ticketing';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'DB connect failed', 'details' => $mysqli->connect_error]);
    exit;
}
$mysqli->set_charset("utf8");
