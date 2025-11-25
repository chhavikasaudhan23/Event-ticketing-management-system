<?php
header('Content-Type: application/json');
$uploadDir = '../assets/images/uploads/';
if(!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
  $tmp = $_FILES['image']['tmp_name'];
  $name = basename($_FILES['image']['name']);
  $ext = pathinfo($name, PATHINFO_EXTENSION);
  $newName = 'ev_'.time().'_'.rand(1000,9999).'.'.$ext;
  $target = $uploadDir.$newName;
  if(move_uploaded_file($tmp, $target)) echo json_encode(['success'=>true,'path'=>'assets/images/uploads/'.$newName]);
  else echo json_encode(['success'=>false,'error'=>'move failed']);
} else echo json_encode(['success'=>false,'error'=>'no file']);
