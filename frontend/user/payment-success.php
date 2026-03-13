<?php
include '../../backend/config/db.php';
session_start();

/* ===== LOGIN CHECK ===== */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

/* ===== POST DATA CHECK ===== */
if (!isset($_POST['event_id'], $_POST['quantity'], $_POST['total'])) {
    header("Location: events.php");
    exit;
}

$user_id    = (int)$_SESSION['user_id'];
$user_name  = $_SESSION['user_name'] ?? 'User';

$event_id   = (int)$_POST['event_id'];
$tickets    = (int)$_POST['quantity'];
$total_paid = (float)$_POST['total'];

$payment_mode = "UPI (Demo)";

/* ===== FETCH EVENT ===== */
$event_q = mysqli_query($conn,"
    SELECT title, price, total_tickets 
    FROM events 
    WHERE id = $event_id
");

if (mysqli_num_rows($event_q) == 0) {
    die("Invalid Event");
}

$event = mysqli_fetch_assoc($event_q);

/* ===== CHECK TICKET AVAILABILITY ===== */
if ($event['total_tickets'] < $tickets) {
    die("Not enough tickets available");
}

$price_per_ticket = (float)$event['price'];

/* ===== INSERT BOOKING ===== */
$insert = mysqli_query($conn,"
    INSERT INTO bookings 
    (user_id, event_id, tickets_booked, total_price, status) 
    VALUES 
    ($user_id, $event_id, $tickets, $total_paid, 'Paid')
");

if (!$insert) {
    die("Booking Failed: " . mysqli_error($conn));
}

/* ===== UPDATE EVENT TICKETS ===== */
mysqli_query($conn,"
    UPDATE events 
    SET total_tickets = total_tickets - $tickets 
    WHERE id = $event_id
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Successful | Eventify</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
}

.glass-card{
    background:rgba(255,255,255,0.18);
    backdrop-filter:blur(14px);
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
    padding:30px;
    max-width:480px;
    width:100%;
    text-align:center;
}

.glass-card hr{
    border-color:rgba(255,255,255,0.3);
}

.success{
    font-size:48px;
    margin-bottom:10px;
}

.btn-main{
    border-radius:10px;
    padding:12px;
    font-weight:600;
}
</style>
</head>

<body>

<div class="glass-card">
    <div class="success">✅</div>
    <h3 class="mb-2">Payment Successful</h3>
    <p class="opacity-75">Your booking has been confirmed</p>

    <hr>

    <!-- USER DETAILS -->
    <p><b>Name:</b> <?= htmlspecialchars($user_name) ?></p>
    <p><b>User ID:</b> <?= $user_id ?></p>

    <hr>

    <!-- EVENT DETAILS -->
    <p><b>Event:</b> <?= htmlspecialchars($event['title']) ?></p>
    <p><b>Tickets:</b> <?= $tickets ?></p>
    <p><b>Price / Ticket:</b> ₹<?= number_format($price_per_ticket,2) ?></p>
    <p><b>Total Paid:</b> ₹<?= number_format($total_paid,2) ?></p>
    <p><b>Payment Mode:</b> <?= $payment_mode ?></p>

    <a href="my-bookings.php" class="btn btn-success btn-main w-100 mt-3">
        View My Tickets
    </a>

    <a href="dashboard.php" class="btn btn-outline-light btn-main w-100 mt-2">
        Go to Dashboard
    </a>
</div>

</body>
</html>
