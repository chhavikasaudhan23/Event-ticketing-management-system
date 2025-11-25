<?php
header('Content-Type: application/json');
require 'db.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $mysqli->prepare("SELECT event_id, title, description, date, time, venue, category, price, total_seats, available_seats, image_path as image FROM events WHERE event_id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if($row = $res->fetch_assoc()) echo json_encode($row);
else echo json_encode(['error'=>'Not found']);
