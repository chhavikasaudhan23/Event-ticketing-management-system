<?php
include '../../backend/config/db.php';
session_start();

$user_id = $_SESSION['user_id'] ?? 0;

if(!$user_id){
    header("Location: login.php");
    exit;
}

if(!isset($_GET['event_id'])){
    header("Location: events.php");
    exit;
}

$event_id = (int)$_GET['event_id'];

$event_q = mysqli_query($conn,"SELECT * FROM events WHERE id='$event_id'");
if(mysqli_num_rows($event_q)==0){
    header("Location: events.php");
    exit;
}

$event = mysqli_fetch_assoc($event_q);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Event Details | Eventify</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}

body{
    background:linear-gradient(#0f2027,#203a43,#2c5364);
    min-height:100vh;
    color:#fff;
}

/* ===== CARD ===== */
.details-card{
    background:rgba(255,255,255,0.18);
    backdrop-filter:blur(14px);
    border-radius:20px;
    padding:35px;
    box-shadow:0 10px 25px rgba(0,0,0,.25);
}

.details-card h2{
    margin-bottom:10px;
}

.info p{
    margin-bottom:6px;
    opacity:.9;
}

.price{
    font-size:26px;
    font-weight:600;
    margin:15px 0;
    color:#00ffc2;
}

input[type=number]{
    border-radius:10px;
    border:none;
    padding:10px;
    width:100%;
}

.btn-pay{
    background:#00e0a8;
    border:none;
    color:#000;
    font-weight:600;
    padding:12px;
    border-radius:10px;
    width:100%;
}
.btn-pay:hover{
    background:#00ffc2;
}
</style>
</head>

<body>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="details-card">

                <h2><?= htmlspecialchars($event['title']) ?></h2>

                <div class="info">
                    <p>📅 <b>Date:</b> <?= date("d M Y",strtotime($event['date'])) ?></p>
                    <p>📍 <b>Venue:</b> <?= htmlspecialchars($event['venue']) ?></p>
                    <p>🎟️ <b>Available Tickets:</b> <?= $event['total_tickets'] ?></p>
                </div>

                <div class="price">₹<?= $event['price'] ?> / Ticket</div>

                <!-- BOOKING FORM -->
                <form action="payments.php" method="POST">
                    <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                    <input type="hidden" name="price" value="<?= $event['price'] ?>">

                    <label class="mb-1">Number of Tickets</label>
                    <input type="number" name="quantity" min="1" max="25" value="1" required>

                    <button type="submit" class="btn-pay mt-4">
                        Proceed to Payment
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

</body>
</html>
