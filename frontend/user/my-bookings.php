<?php
include '../../backend/config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id   = (int)$_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? '';

$today = date('Y-m-d');

$query = "
SELECT 
    b.id,
    b.tickets_booked,
    b.total_price,
    b.status,
    b.booking_date,
    e.title,
    e.date AS event_date,
    e.time,
    e.venue
FROM bookings b
JOIN events e ON b.event_id = e.id
WHERE b.user_id = $user_id
ORDER BY b.booking_date DESC
";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Tickets | Event Management</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins,sans-serif;}

body{
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    min-height:100vh;
    color:#fff;
}

/* NAVBAR */
.navbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:16px 40px;
    background:rgba(255,255,255,0.12);
    backdrop-filter:blur(14px);
}

.logo{font-size:20px;font-weight:600;}

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
}
.navbar a:hover{
    background:rgba(255,255,255,0.25);
}

/* CONTENT */
.container{max-width:1200px;}

/* GLASS CARD */
.glass{
    background:rgba(255,255,255,.18);
    backdrop-filter:blur(16px);
    border-radius:22px;
    padding:30px;
}

.table{color:#fff;}

thead{
    background:rgba(255,255,255,.2);
}

.badge-paid{
    background:#28a745;
    padding:6px 12px;
    border-radius:10px;
}

.badge-expired{
    background:#6c757d;
    padding:6px 12px;
    border-radius:10px;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="nav-left">
        <div class="logo">🎫 Event Management</div>
        <a href="dashboard.php">Dashboard</a>
        <a href="events.php">Events</a>
        <a href="my-bookings.php">My Tickets</a>
    </div>

    <div class="nav-right">
        👤 <?= htmlspecialchars($user_name) ?>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container py-5">
<div class="glass">

<h3 class="mb-4">🎟 My Tickets</h3>

<?php if(mysqli_num_rows($result)>0){ ?>
<div class="table-responsive">
<table class="table table-borderless align-middle">
<thead>
<tr>
<th>Event</th>
<th>Event Date</th>
<th>Booking Date</th>
<th>Tickets</th>
<th>Total</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ 
    $isExpired = ($row['event_date'] < $today);
?>
<tr>
<td>
<b><?= htmlspecialchars($row['title']) ?></b><br>
<small><?= htmlspecialchars($row['venue']) ?></small>
</td>

<td>
<?= date("d M Y", strtotime($row['event_date'])) ?><br>
<small><?= $row['time'] ?></small>
</td>

<td>
<?= date("d M Y", strtotime($row['booking_date'])) ?><br>
<small><?= date("h:i A", strtotime($row['booking_date'])) ?></small>
</td>

<td><?= $row['tickets_booked'] ?></td>

<td>₹<?= number_format($row['total_price'],2) ?></td>

<td>
<?php if($isExpired): ?>
    <span class="badge-expired">Expired</span>
<?php else: ?>
    <span class="badge-paid"><?= $row['status'] ?></span>
<?php endif; ?>
</td>

<td>
<?php if($isExpired): ?>
    <button class="btn btn-sm btn-secondary" disabled>
        Event Ended
    </button>
<?php else: ?>
    <a href="view-tickets.php?booking_id=<?= $row['id'] ?>"
       class="btn btn-sm btn-success">
       View Ticket
    </a>
<?php endif; ?>
</td>
</tr>
<?php } ?>

</tbody>
</table>
</div>

<?php } else { ?>
<p class="opacity-75">No bookings yet.</p>
<a href="events.php" class="btn btn-outline-light">Browse Events</a>
<?php } ?>

</div>
</div>

</body>
</html>
