<?php
include '../../backend/config/db.php';
session_start();

$user_id   = $_SESSION['user_id'] ?? 0;
$user_name = $_SESSION['user_name'] ?? '';

$today = date('Y-m-d');

/* ================= USER STATS ================= */
$upcomingEvents = $totalBookings = $totalTickets = $totalSpent = 0;

if($user_id){

    $upcomingEvents = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT COUNT(DISTINCT e.id) total
        FROM bookings b
        JOIN events e ON e.id=b.event_id
        WHERE b.user_id='$user_id' AND e.date>=CURDATE()
    "))['total'] ?? 0;

    $totalBookings = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT COUNT(*) total FROM bookings WHERE user_id='$user_id'
    "))['total'] ?? 0;

    $totalTickets = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT SUM(tickets_booked) total FROM bookings WHERE user_id='$user_id'
    "))['total'] ?? 0;

    $totalSpent = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT SUM(total_price) total
        FROM bookings WHERE user_id='$user_id' AND status='Paid'
    "))['total'] ?? 0;
}

/* ================= RECENT BOOKINGS (GROUPED) ================= */
$recentBookings = [];
if($user_id){
    $q = mysqli_query($conn,"
        SELECT
            e.id,
            e.title,
            MAX(e.date) event_date,
            SUM(b.tickets_booked) tickets,
            SUM(b.total_price) amount,
            CASE WHEN MAX(e.date) < CURDATE() THEN 'Expired' ELSE 'Paid' END status
        FROM bookings b
        JOIN events e ON e.id=b.event_id
        WHERE b.user_id='$user_id'
        GROUP BY e.id
        ORDER BY event_date DESC
        LIMIT 5
    ");
    while($r=mysqli_fetch_assoc($q)){ $recentBookings[]=$r; }
}

/* ================= GUEST EVENTS ================= */
$guestEvents = mysqli_query($conn,"
    SELECT * FROM events
    WHERE date >= '$today'
    ORDER BY date ASC
    LIMIT 4
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins}
body{background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);color:#fff}

.navbar{display:flex;justify-content:space-between;align-items:center;padding:16px 40px;background:rgba(255,255,255,.12)}
.nav-left,.nav-right{display:flex;gap:20px;align-items:center}
.navbar a{color:#fff;text-decoration:none;padding:6px 12px;border-radius:8px}
.navbar a:hover{background:rgba(255,255,255,.25)}
.logo{font-size:20px;font-weight:600}

.dashboard{max-width:1200px;margin:60px auto;padding:0 20px}
.hero{background:rgba(255,255,255,.18);padding:35px;border-radius:20px;margin-bottom:40px}

.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:25px;margin-bottom:40px}
.stat-card{background:rgba(255,255,255,.16);padding:28px;border-radius:20px}

.table-card{background:rgba(255,255,255,.16);padding:25px;border-radius:20px}
table{width:100%;border-collapse:collapse}
th,td{padding:12px;text-align:center}
thead{background:linear-gradient(135deg,#00c6ff,#0072ff)}

.badge{padding:4px 10px;border-radius:12px;font-size:12px}
.badge-paid{background:#2ecc71;color:#000}
.badge-expired{background:#e74c3c}

.event-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px}
.event-card{background:rgba(255,255,255,.18);padding:22px;border-radius:18px}
.event-card a{display:block;margin-top:12px;padding:10px;background:#00e0a8;color:#000;text-align:center;border-radius:10px;text-decoration:none;font-weight:600}
/* VIEW ALL */ .view-all-wrap{text-align:center;margin-top:30px;} .view-all-btn{ display:inline-block;padding:12px 26px; background:linear-gradient(135deg,#00c6ff,#0072ff); color:#fff;text-decoration:none; border-radius:12px;font-weight:600; }
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
<div class="nav-left">
<div class="logo">🎫 Event Management System</div>
<a href="dashboard.php">Dashboard</a>
<a href="events.php">Events</a>
<?php if($user_id): ?><a href="my-bookings.php">My Tickets</a><?php endif; ?>
</div>
<div class="nav-right">
<?php if($user_id): ?>👤 <?= htmlspecialchars($user_name) ?> <a href="logout.php">Logout</a>
<?php else: ?><a href="login.php">Login</a><?php endif; ?>
</div>
</div>

<div class="dashboard">

<?php if($user_id): ?>

<!-- LOGGED IN VIEW -->
<div class="hero">
<h1>Welcome, <?= htmlspecialchars($user_name) ?> 👋</h1>
<p>Your booking overview</p>
</div>

<div class="stats">
<div class="stat-card"><h2><?= $upcomingEvents ?></h2><p>Upcoming Events</p></div>
<div class="stat-card"><h2><?= $totalBookings ?></h2><p>Total Bookings</p></div>
<div class="stat-card"><h2><?= $totalTickets ?></h2><p>Tickets Booked</p></div>
<div class="stat-card"><h2>₹<?= number_format($totalSpent,2) ?></h2><p>Total Spent</p></div>
</div>

<div class="table-card">
<h3>🧾 Recent Bookings</h3>
<table>
<thead>
<tr><th>Event</th><th>Date</th><th>Tickets</th><th>Amount</th><th>Status</th></tr>
</thead>
<tbody>
<?php if($recentBookings): foreach($recentBookings as $b): ?>
<tr>
<td><?= htmlspecialchars($b['title']) ?></td>
<td><?= date('d M Y',strtotime($b['event_date'])) ?></td>
<td><?= $b['tickets'] ?></td>
<td>₹<?= number_format($b['amount'],2) ?></td>
<td><span class="badge badge-<?= strtolower($b['status']) ?>"><?= $b['status'] ?></span></td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="5">No bookings yet</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>

<?php else: ?>

<!-- GUEST VIEW -->
<div class="hero">
<h1>Welcome to Event Management 🎉</h1>
<p>Login to book tickets instantly</p>
</div>

<div class="event-grid">
<?php while($e=mysqli_fetch_assoc($guestEvents)): ?>
<div class="event-card">
<h3><?= htmlspecialchars($e['title']) ?></h3>
<p>📅 <?= date('d M Y',strtotime($e['date'])) ?></p>
<p>📍 <?= htmlspecialchars($e['venue']) ?></p>
<p>💰 ₹<?= $e['price'] ?></p>
<a href="login.php">Login to Book</a>
</div>
<?php endwhile; ?>
</div>

<div class="view-all-wrap"> <p>Showing 4 featured events</p> <a href="events.php" class="view-all-btn">View All Events</a> </div>

<?php endif; ?>

</div>
</body>
</html>
