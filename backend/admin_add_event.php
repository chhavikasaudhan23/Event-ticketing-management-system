<?php
header('Content-Type: application/json');
require 'db.php';
$imagePath = null;
if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
  $uploadDir = '../assets/images/uploads/';
  if(!is_dir($uploadDir)) mkdir($uploadDir,0755,true);
  $name = basename($_FILES['image']['name']);
  $ext = pathinfo($name, PATHINFO_EXTENSION);
  $newName = 'ev_'.time().'_'.rand(1000,9999).'.'.$ext;
  $target = $uploadDir.$newName;
  if(move_uploaded_file($_FILES['image']['tmp_name'], $target)) $imagePath = 'assets/images/uploads/'.$newName;
}
$title = $mysqli->real_escape_string($_POST['title'] ?? '');
$desc = $mysqli->real_escape_string($_POST['description'] ?? '');
$date = $_POST['date'] ?? null;
$time = $_POST['time'] ?? null;
$venue = $mysqli->real_escape_string($_POST['venue'] ?? '');
$category = $mysqli->real_escape_string($_POST['category'] ?? '');
$price = floatval($_POST['price'] ?? 0);
$total_seats = intval($_POST['total_seats'] ?? 0);
$available_seats = $total_seats;
$stmt = $mysqli->prepare("INSERT INTO events (title, description, date, time, venue, category, price, total_seats, available_seats, image_path) VALUES (?,?,?,?,?,?,?,?,?,?)");
$stmt->bind_param('ssssssdiss', $title, $desc, $date, $time, $venue, $category, $price, $total_seats, $available_seats, $imagePath);
if($stmt->execute()) echo json_encode(['success'=>true,'event_id'=>$stmt->insert_id]);
else echo json_encode(['success'=>false,'error'=>$mysqli->error]);
