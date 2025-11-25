<?php
header('Content-Type: application/json');
require 'db.php';
$res = $mysqli->query("SELECT b.booking_id, b.user_id, b.event_id, b.quantity, b.amount_paid, b.booking_date, b.payment_status FROM bookings b ORDER BY b.booking_date DESC");
$data = [];
while($r = $res->fetch_assoc()) $data[] = $r;
echo json_encode($data);
