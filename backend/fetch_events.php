<?php
header('Content-Type: application/json');
require 'db.php';
$res = $mysqli->query("SELECT event_id, title, description, date, time, venue, category, price, total_seats, available_seats, image_path as image FROM events ORDER BY date ASC");
$events = [];
while($r = $res->fetch_assoc()) $events[] = $r;
echo json_encode($events);
