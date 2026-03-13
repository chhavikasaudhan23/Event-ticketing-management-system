<?php
session_start();
include '../../backend/config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin-login.php");
    exit;
}

$admin_name = $_SESSION['admin_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Bookings | Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Poppins,sans-serif;
}
html,body{
    width:100%;
    overflow-x:hidden;
}

body{
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    min-height:100vh;
    color:#fff;
}

/* ===== NAVBAR ===== */
.navbar{
    padding:14px 28px;
    background:rgba(255,255,255,0.14);
    backdrop-filter:blur(14px);
    box-shadow:0 6px 20px rgba(0,0,0,.25);
}
.navbar-brand{
    font-weight:600;
    font-size:20px;
}
.nav-link{
    color:#fff !important;
    font-size:14px;
    padding:6px 12px;
    border-radius:6px;
}
.nav-link:hover{
    background:rgba(255,255,255,0.25);
}

/* ===== GLASS CARD ===== */
.glass-card{
    background:rgba(255,255,255,0.16);
    backdrop-filter:blur(16px);
    border-radius:18px;
    padding:28px;
    box-shadow:0 12px 25px rgba(0,0,0,.35);
}

/* ===== TABLE ===== */
.table{
    margin:0;
    color:#fff;
}
.table thead{
    background:linear-gradient(135deg,#6a11cb,#2575fc);
}
.table th, .table td{
    text-align:center;
    vertical-align:middle;
    padding:14px 10px;
    white-space:normal;
}
.table tbody tr:hover{
    background:rgba(255,255,255,0.08);
}

/* ===== BADGES ===== */
.badge{
    padding:6px 10px;
    font-size:12px;
    border-radius:10px;
}
</style>
</head>

<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar">
<div class="container d-flex justify-content-between align-items-center">

    <a class="navbar-brand text-white" href="admin-dashboard.php">
        🎫 Event Management Admin
    </a>

    <div class="d-flex gap-2 align-items-center">
        <a class="nav-link" href="admin-dashboard.php">Dashboard</a>
        <a class="nav-link" href="view-events.php">Manage Events</a>
        <a class="nav-link" href="view-users.php">View Users</a>
        <a class="nav-link" href="view-bookings.php">View Bookings</a>
        <a class="nav-link text-danger" href="admin-logout.php">Logout</a>
    </div>

</div>
</nav>

<!-- ===== CONTENT ===== -->
<div class="container py-5">

<div class="glass-card">
    <h3 class="mb-4 text-center">📑 All Bookings</h3>

<?php
$bookings_q = mysqli_query($conn, "
    SELECT b.id, b.status, b.tickets_booked, b.total_price, b.booking_date,
           u.name AS user_name, u.email AS user_email,
           e.title AS event_title, e.date AS event_date
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN events e ON b.event_id = e.id
    ORDER BY b.booking_date DESC
");
?>

<div class="table-responsive">
<table class="table table-borderless align-middle">
<thead>
<tr>
    <th>Sr. No.</th>
    <th>User</th>
    <th>Email</th>
    <th>Event</th>
    <th>Event Date</th>
    <th>Tickets</th>
    <th>Amount</th>
    <th>Booking Date</th>
    <th>Status</th>
</tr>
</thead>
<tbody>

<?php
$i=1;
if(mysqli_num_rows($bookings_q)>0):
while($b=mysqli_fetch_assoc($bookings_q)):
?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= htmlspecialchars($b['user_name']) ?></td>
    <td><?= htmlspecialchars($b['user_email']) ?></td>
    <td><?= htmlspecialchars($b['event_title']) ?></td>
    <td><?= date('d M Y',strtotime($b['event_date'])) ?></td>
    <td><?= $b['tickets_booked'] ?></td>
    <td>₹<?= number_format($b['total_price'],2) ?></td>
    <td><?= date('d M Y h:i A',strtotime($b['booking_date'])) ?></td>
    <td>
        <span class="badge 
            <?= $b['status']=='Paid'?'bg-success':'' ?>
            <?= $b['status']=='Cancelled'?'bg-danger':'' ?>
            <?= $b['status']=='Completed'?'bg-info':'' ?>
            <?= $b['status']=='Expired'?'bg-secondary':'' ?>">
            <?= $b['status'] ?>
        </span>
    </td>
</tr>
<?php endwhile; else: ?>
<tr>
    <td colspan="9" class="text-center">No bookings found</td>
</tr>
<?php endif; ?>

</tbody>
</table>
</div>

</div>

</div>

</body>
</html>
