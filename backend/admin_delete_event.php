<?php
header('Content-Type: application/json');
require 'db.php';
$id = intval($_POST['event_id'] ?? 0);
if(!$id) { echo json_encode(['success'=>false,'msg'=>'No id']); exit; }
$stmt = $mysqli->prepare("DELETE FROM events WHERE event_id = ?");
$stmt->bind_param('i', $id);
if($stmt->execute()) echo json_encode(['success'=>true]); else echo json_encode(['success'=>false,'error'=>$mysqli->error]);
