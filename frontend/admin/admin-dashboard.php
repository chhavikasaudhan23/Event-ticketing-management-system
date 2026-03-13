<?php
session_start();
include '../../backend/config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit;
}

$admin_name = $_SESSION['admin_name'] ?? 'Admin';

/* ===== DASHBOARD STATS (example) ===== */
$totalEvents = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS t FROM events"))['t'];
$totalUsers  = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS t FROM users"))['t'];
$totalBookings = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS t FROM bookings"))['t'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard | Eventify</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins,sans-serif;}

body{
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    min-height:100vh;
    color:#fff;
}

/* ===== NAVBAR ===== */
.navbar{
    padding:16px 40px;
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
    transition:.3s;
}
.nav-link:hover{
    background:rgba(255,255,255,0.25);
}

/* ===== CARDS (UNCHANGED STYLE) ===== */
.card-glass{
    background:rgba(255,255,255,.18);
    backdrop-filter:blur(16px);
    border-radius:18px;
    padding:25px;
    box-shadow:0 10px 30px rgba(0,0,0,.3);
    transition:.3s;
}
.card-glass:hover{
    transform:translateY(-6px);
}

.stat{
    font-size:28px;
    font-weight:600;
}
</style>
</head>

<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="#">
            🎫 Event Management Admin
        </a>

        <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="adminNav">
            <ul class="navbar-nav gap-2">
                <li class="nav-item">
                    <a class="nav-link" href="admin-dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view-events.php">Manage Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view-users.php">View Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view-bookings.php">View Bookings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="admin-logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- ===== DASHBOARD CONTENT ===== -->
<div class="container py-5">

<h4 class="mb-4">Welcome, <?= htmlspecialchars($admin_name) ?> 👋</h4>

<!-- ===== STATS CARDS (AS IT IS CONCEPT) ===== -->
<div class="row g-4">

    <div class="col-md-4">
        <div class="card-glass text-center">
            <h6>Total Events</h6>
            <div class="stat"><?= $totalEvents ?></div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-glass text-center">
            <h6>Total Users</h6>
            <div class="stat"><?= $totalUsers ?></div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-glass text-center">
            <h6>Total Bookings</h6>
            <div class="stat"><?= $totalBookings ?></div>
        </div>
    </div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
