<?php
header('Content-Type: application/json');
require 'db.php';
$data = json_decode(file_get_contents('php://input'), true);
if(!$data){ echo json_encode(['success'=>false,'msg'=>'No data']); exit; }
$user_id = intval($data['user_id']);
$event_id = intval($data['event_id']);
$quantity = intval($data['quantity']);
$amount_paid = floatval($data['amount_paid']);
$status = $mysqli->real_escape_string($data['status']);
$stmt = $mysqli->prepare("INSERT INTO bookings (user_id, event_id, quantity, amount_paid, booking_date, payment_status) VALUES (?,?,?,?,NOW(),?)");
$stmt->bind_param('iiids', $user_id, $event_id, $quantity, $amount_paid, $status);
if($stmt->execute()){
  $mysqli->query("UPDATE events SET available_seats = available_seats - $quantity WHERE event_id = $event_id");
  echo json_encode(['success'=>true,'booking_id'=>$stmt->insert_id]);
} else echo json_encode(['success'=>false,'error'=>$mysqli->error]);
