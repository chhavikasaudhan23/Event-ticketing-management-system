<?php
include '../../backend/config/db.php';
session_start();

/* ===== LOGIN CHECK ===== */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

/* ===== DATA CHECK ===== */
if (!isset($_POST['event_id'], $_POST['quantity'], $_POST['price'])) {
    header("Location: events.php");
    exit;
}

$user_id  = $_SESSION['user_id'];
$event_id = (int)$_POST['event_id'];
$quantity = (int)$_POST['quantity'];
$price    = (int)$_POST['price'];

if ($quantity < 1) {
    header("Location: events.php");
    exit;
}

$total_amount = $quantity * $price;

/* ===== EVENT INFO ===== */
$event_q = mysqli_query($conn, "SELECT title FROM events WHERE id='$event_id'");
$event   = mysqli_fetch_assoc($event_q);

/* ===== FAKE UPI QR DATA ===== */
$upiData = "upi://pay?pa=eventify@upi&pn=Eventify&am=$total_amount&cu=INR&tn=".$event['title'];
$qrURL   = "https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=".urlencode($upiData);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment | Event Management</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}

body{
    background:linear-gradient(#0f2027,#203a43,#2c5364);
    min-height:100vh;
    color:#fff;
}

.pay-card{
    background:rgba(255,255,255,0.18);
    backdrop-filter:blur(16px);
    border-radius:22px;
    padding:40px;
    box-shadow:0 15px 40px rgba(0,0,0,0.35);
}

.summary p{margin-bottom:6px;opacity:.9;}

.amount{
    font-size:30px;
    font-weight:600;
    color:#00ffc2;
    margin:18px 0;
}

.qr-box{
    background:rgba(255,255,255,0.12);
    padding:18px;
    border-radius:18px;
    display:inline-block;
    box-shadow:0 10px 25px rgba(0,0,0,.3);
}

.qr-box img{
    width:220px;
    border-radius:12px;
}

.btn-pay{
    background:#00e0a8;
    color:#000;
    border:none;
    padding:14px;
    border-radius:12px;
    font-weight:600;
}
.btn-pay:hover{background:#00ffc2;}

.note{
    font-size:13px;
    opacity:.75;
    margin-top:10px;
}
.upi-icons img{
    width:40px;
    margin:6px;
    opacity:.85;
}
</style>
</head>

<body>

<div class="container my-5">
<div class="row justify-content-center">
<div class="col-md-6">

<div class="pay-card text-center">

    <h3 class="mb-3">🔐 Secure Payment</h3>

    <div class="summary">
        <p><b>Event:</b> <?= htmlspecialchars($event['title']) ?></p>
        <p><b>Tickets:</b> <?= $quantity ?></p>
        <p><b>Price / Ticket:</b> ₹<?= $price ?></p>
    </div>

    <div class="amount">₹<?= $total_amount ?></div>

    <hr>

    <h6 class="mb-3">Scan & Pay using UPI (Demo)</h6>

    <!-- REAL LOOKING FAKE QR -->
    <div class="qr-box mb-3">
        <img src="<?= $qrURL ?>" alt="UPI QR Code">
    </div>
    
    <p class="note">
        * Demo payment only. No real money is deducted.
    </p>

    <!-- PAYMENT FORM -->
    <form action="payment-success.php" method="POST">
        <input type="hidden" name="event_id" value="<?= $event_id ?>">
        <input type="hidden" name="quantity" value="<?= $quantity ?>">
        <input type="hidden" name="total" value="<?= $total_amount ?>">

        <button type="submit" class="btn btn-pay w-100 mt-4">
            Payment Completed
        </button>
    </form>

</div>

</div>
</div>
</div>

</body>
</html>
