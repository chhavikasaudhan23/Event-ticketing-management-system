<?php
session_start();
include '../../backend/config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin-login.php");
    exit;
}

$admin_name = $_SESSION['admin_name'] ?? 'Admin';

/* Delete user */
if(isset($_GET['delete_id'])){
    $delete_id = (int)$_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM users WHERE id='$delete_id'");
    header("Location: view-users.php");
    exit;
}

/* Fetch users */
$users = [];
$q = mysqli_query($conn,"SELECT * FROM users ORDER BY id DESC");
while($row = mysqli_fetch_assoc($q)){
    $users[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Users | Admin</title>
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

/* ===== GLASS CARD (FIXED) ===== */
.glass-card{
    background:rgba(255,255,255,0.16);
    backdrop-filter:blur(16px);
    border-radius:18px;
    padding:28px;              /* ✅ IMPORTANT FIX */
    box-shadow:0 12px 25px rgba(0,0,0,.35);
}

/* ===== TABLE ===== */
.table{
    margin:0;
    color:#fff;
}
.table thead{
    background:linear-gradient(135deg,#00c6ff,#0072ff);
}
.table th, .table td{
    text-align:center;
    vertical-align:middle;
    padding:14px 10px;
    white-space:normal;        /* ✅ TEXT WRAP FIX */
}
.table tbody tr:hover{
    background:rgba(255,255,255,0.08);
}

/* ===== BUTTON ===== */
.btn-dynamic{
    border-radius:10px;
    font-size:13px;
    padding:6px 14px;
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
    <h3 class="mb-4 text-center">👥 Registered Users</h3>

    <div class="table-responsive"> <!-- safe & clean -->
    <table class="table table-borderless align-middle">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Registered On</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if($users): ?>
            <?php foreach($users as $i=>$u): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td>
                    <?= isset($u['created_at']) 
                        ? date('d M Y, h:i A',strtotime($u['created_at'])) 
                        : '—' ?>
                </td>
                <td>
                    <a href="view-users.php?delete_id=<?= $u['id'] ?>"
                       onclick="return confirm('Delete this user?');"
                       class="btn btn-danger btn-sm btn-dynamic">
                       Delete
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">No users found</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

</div>

</body>
</html>
