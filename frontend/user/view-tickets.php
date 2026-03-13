<?php
include '../../backend/config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id   = (int)$_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? '';

if (!isset($_GET['booking_id']) || !is_numeric($_GET['booking_id'])) {
    die("Invalid Ticket");
}

$booking_id = (int)$_GET['booking_id'];

$query = "
SELECT 
    b.id,
    b.tickets_booked,
    b.status,
    e.title,
    e.date,
    e.time,
    e.venue
FROM bookings b
JOIN events e ON b.event_id = e.id
WHERE b.id = $booking_id AND b.user_id = $user_id
LIMIT 1
";

$res = mysqli_query($conn,$query);
if(mysqli_num_rows($res)==0){
    die("Invalid Ticket");
}

$data = mysqli_fetch_assoc($res);
$totalTickets = (int)$data['tickets_booked'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Tickets | Event Management</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins,sans-serif;}

body{
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    min-height:100vh;
    color:#fff;
}

/* ===== NAVBAR (SAME AS DASHBOARD) ===== */
.navbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:16px 40px;
    background:rgba(255,255,255,0.12);
    backdrop-filter:blur(14px);
}

.logo{
    font-size:20px;
    font-weight:600;
}

.nav-left,.nav-right{
    display:flex;
    align-items:center;
    gap:22px;
}

.navbar a{
    color:#fff;
    text-decoration:none;
    font-size:14px;
    padding:6px 10px;
    border-radius:6px;
    transition:.3s;
}
.navbar a:hover{
    background:rgba(255,255,255,0.25);
}

/* ===== PROFILE DROPDOWN ===== */
.profile{
    position:relative;
    cursor:pointer;
}

.dropdown{
    position:absolute;
    top:38px;
    right:0;
    background:rgba(0,0,0,0.75);
    border-radius:8px;
    display:none;
    min-width:130px;
    backdrop-filter:blur(10px);
}

.dropdown a{
    display:block;
    padding:10px;
    font-size:14px;
}
.dropdown a:hover{
    background:rgba(255,255,255,0.15);
}

/* ===== CONTENT ===== */
.container{
    max-width:1100px;
}

/* ===== TICKET CARD ===== */
.ticket-card{
    background:rgba(255,255,255,.18);
    backdrop-filter:blur(18px);
    border-radius:22px;
    padding:30px;
    margin-bottom:30px;
    box-shadow:0 15px 40px rgba(0,0,0,.35);
}

.ticket-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px dashed rgba(255,255,255,.4);
    padding-bottom:15px;
    margin-bottom:20px;
}

.qr{
    animation:pulse 1.5s infinite;
}

@keyframes pulse{
    0%{transform:scale(1)}
    50%{transform:scale(1.05)}
    100%{transform:scale(1)}
}

.info{
    background:rgba(255,255,255,.14);
    padding:12px;
    border-radius:12px;
    margin-bottom:12px;
}

/* ===== PRINT ===== */
@media print{
    .no-print{display:none}
    body{
        background:#fff;
        color:#000;
    }
    .ticket-card{
        page-break-after:always;
        background:#fff;
        color:#000;
        box-shadow:none;
    }
}
</style>
</head>

<body>

<!-- ===== NAVBAR ===== -->
<div class="navbar no-print">
    <div class="nav-left">
        <div class="logo">🎫 Event Management</div>
        <a href="dashboard.php">Dashboard</a>
        <a href="events.php">Events</a>
        <a href="my-bookings.php">My Tickets</a>
    </div>

    <div class="nav-right">
        <div class="profile" onclick="toggleDropdown()">
            👤 <?= htmlspecialchars($user_name) ?>
            <div class="dropdown" id="dropdown">
                <a href="logout.php" class="text-danger">Logout</a>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">

<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <h4>🎫 Your Tickets (<?= $totalTickets ?>)</h4>
    <button onclick="window.print()" class="btn btn-success">
        Print / Save All
    </button>
</div>

<?php
for($i=1; $i<=$totalTickets; $i++):
    $ticketNo = $booking_id.'-'.$i;
?>

<!-- ===== SINGLE TICKET ===== -->
<div class="ticket-card">

<div class="ticket-header">
    <div>
        <h4><?= htmlspecialchars($data['title']) ?></h4>
        <small><?= htmlspecialchars($data['venue']) ?></small>
    </div>

    <img class="qr"
     src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=EVENTIFY-<?= $ticketNo ?>">
</div>

<div class="row">
<div class="col-md-8">
<div class="row">
    <div class="col-6 info">📅 Date<br><b><?= date("d M Y",strtotime($data['date'])) ?></b></div>
    <div class="col-6 info">⏰ Time<br><b><?= $data['time'] ?></b></div>
    <div class="col-6 info">🎫 Ticket No<br><b><?= $ticketNo ?></b></div>
    <div class="col-6 info">📌 Status<br>
        <span class="badge bg-success"><?= $data['status'] ?></span>
    </div>
</div>
</div>

<div class="col-md-4 text-center">
    <p class="opacity-75">Scan at entry</p>
</div>
</div>

<div class="mt-3 no-print">
    <button onclick="window.print()" class="btn btn-outline-light btn-sm">
        Print / Save This Ticket
    </button>
</div>

</div>
<!-- ===== END TICKET ===== -->

<?php endfor; ?>

<a href="my-bookings.php" class="btn btn-outline-light no-print">
← Back to My Tickets
</a>

</div>

<script>
function toggleDropdown(){
    const d = document.getElementById("dropdown");
    d.style.display = d.style.display === "block" ? "none" : "block";
}
</script>

</body>
</html>
