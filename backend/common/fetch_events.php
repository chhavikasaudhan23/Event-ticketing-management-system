<?php
require_once("../config/db.php");

$data = [];
$sql = "SELECT * FROM events ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
