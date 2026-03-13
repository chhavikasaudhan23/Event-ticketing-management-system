<?php
session_start();
include '../../backend/config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit;
}

$admin_name = $_SESSION['admin_name'] ?? 'Admin';

$message = '';
if (isset($_POST['add'])) {
    $title = mysqli_real_escape_string($conn,$_POST['title']);
    $desc  = mysqli_real_escape_string($conn,$_POST['description']);
    $date  = $_POST['date'];
    $time  = $_POST['time'];
    $venue = mysqli_real_escape_string($conn,$_POST['venue']);
    $total = (int)$_POST['total_tickets'];
    $price = (float)$_POST['price'];

    mysqli_query($conn,"
        INSERT INTO events(title,description,date,time,venue,total_tickets,price)
        VALUES('$title','$desc','$date','$time','$venue','$total','$price')
    ");

    $message = "✅ Event added successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Event | Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins,sans-serif;}

body{
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    min-height:100vh;
    color:#fff;
}

/* ===== NAVBAR (SAME AS OTHER ADMIN PAGES) ===== */
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

/* ===== GLASS CARD ===== */
.glass-card{
    background: rgba(255,255,255,0.16);
    backdrop-filter: blur(16px);
    border-radius:22px;
    padding:35px;
    box-shadow:0 15px 40px rgba(0,0,0,0.35);
}

/* ===== FORM ===== */
.form-control, textarea{
    border-radius:10px;
    border:none;
}
.form-control:focus, textarea:focus{
    box-shadow:0 0 0 2px rgba(0,224,168,.5);
}

label{
    font-size:14px;
    opacity:.85;
}

.btn-dynamic{
    min-width:160px;
    border-radius:10px;
    font-weight:600;
    transition:.3s;
}
.btn-dynamic:hover{
    transform:translateY(-2px);
}
</style>
</head>

<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg">
<div class="container-fluid">
    <a class="navbar-brand text-white" href="admin-dashboard.php">
        🎫 Event Management Admin
    </a>

    <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="adminNav">
        <ul class="navbar-nav gap-2 align-items-center">
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

<!-- ===== CONTENT ===== -->
<div class="container py-5">
<div class="row justify-content-center">
<div class="col-lg-9 col-md-11">

<div class="glass-card">

<h3 class="text-center mb-4">➕ Add New Event</h3>

<?php if($message): ?>
<div class="alert alert-success text-center">
    <?= $message ?>
</div>
<?php endif; ?>

<form method="POST">
<div class="row g-3">

    <div class="col-md-6">
        <label>Event Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label>Venue</label>
        <input type="text" name="venue" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label>Event Date</label>
        <input type="date" name="date" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label>Event Time</label>
        <input type="time" name="time" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label>Total Tickets</label>
        <input type="number" name="total_tickets" class="form-control" min="1" required>
    </div>

    <div class="col-md-6">
        <label>Ticket Price (₹)</label>
        <input type="number" step="0.01" name="price" class="form-control" required>
    </div>

    <div class="col-12">
        <label>Description</label>
        <textarea name="description" rows="3" class="form-control" required></textarea>
    </div>

</div>

<div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
    <button type="submit" name="add" class="btn btn-success btn-dynamic">
        Save Event
    </button>
    <a href="view-events.php" class="btn btn-secondary btn-dynamic">
        Cancel
    </a>
</div>

</form>

</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
