<?php
session_start();
include '../../backend/config/db.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: admin-login.php");
    exit;
}

$admin_name = $_SESSION['admin_name'] ?? 'Admin';
$today = date('Y-m-d');

/* ===== CATEGORY LOGIC (MODIFIED) ===== */
function getCategory($title){
    $title = strtolower($title);

    if(strpos($title,'music')!==false || strpos($title,'dj')!==false || strpos($title,'concert')!==false)
        return '🎵 Music & Entertainment';

    if(strpos($title,'cricket')!==false || strpos($title,'football')!==false || strpos($title,'tournament')!==false || strpos($title,'sports')!==false)
        return '🏏 Sports & Fitness';

    if(strpos($title,'yoga')!==false || strpos($title,'wellness')!==false || strpos($title,'fitness')!==false)
        return '🧘 Wellness & Fitness';

    if(strpos($title,'seminar')!==false || strpos($title,'workshop')!==false || strpos($title,'training')!==false || strpos($title,'education')!==false)
        return '🎓 Education';

    if(strpos($title,'corporate')!==false || strpos($title,'business')!==false || strpos($title,'startup')!==false)
        return '🏢 Corporate & Business';

    if(strpos($title,'expo')!==false || strpos($title,'exhibition')!==false || strpos($title,'trade')!==false)
        return '🛍️ Exhibition';

    if(
   strpos($title,'social')!==false || 
   strpos($title,'community')!==false ||
   strpos($title,'ngo')!==false ||
   strpos($title,'empowerment')!==false ||
   strpos($title,'charity')!==false ||
   strpos($title,'awareness')!==false ||
   strpos($title,'donation')!==false ||
   strpos($title,'women')!==false
)
    return '🤝 Social & Community';

    if(strpos($title,'college')!==false || strpos($title,'youth')!==false || strpos($title,'fest')!==false)
        return '🎉 College & Youth Events';

    if(strpos($title,'dance')!==false || strpos($title,'art')!==false || strpos($title,'cultural')!==false)
        return '🎭 Cultural & Arts';

    return '✨ Other';
}

/* ===== FETCH EVENTS ===== */
$events_q = mysqli_query($conn, "SELECT * FROM events ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Events | Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins,sans-serif;}

body{
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    min-height:100vh;
    color:#fff;
}

.navbar{
    padding:16px 40px;
    background:rgba(255,255,255,0.14);
    backdrop-filter:blur(14px);
}
.navbar-brand{font-weight:600;font-size:20px;}
.nav-link{color:#fff !important;font-size:14px;}

.card-glass{
    border-radius:14px;
    padding:18px;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(12px);
}

.table-glass{
    background: rgba(255,255,255,0.12);
    border-radius:12px;
}

.badge-status{
    padding:6px 12px;
    border-radius:20px;
    font-size:0.75rem;
    font-weight:600;
}
.badge-active{background:#55efc4;color:#000;}
.badge-sold{background:#ff7675;color:#000;}
.badge-expired{background:#b2bec3;color:#000;}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="admin-dashboard.php">
            🎫 Event Management Admin
        </a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav gap-2">
                <li class="nav-item"><a class="nav-link" href="admin-dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="view-events.php">Manage Events</a></li>
                <li class="nav-item"><a class="nav-link" href="view-users.php">View Users</a></li>
                <li class="nav-item"><a class="nav-link" href="view-bookings.php">View Bookings</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="admin-logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5">

<h3 class="mb-4">📋 Manage Events</h3>

<!-- ✅ ADD EVENT BUTTON -->
<div class="mb-4 text-end">
    <a href="add-event.php" class="btn btn-success">
        + Add New Event
    </a>
</div>

<div class="card-glass">
<table class="table table-glass table-hover text-center align-middle">
<thead>
<tr>
    <th>Sr. No.</th>
    <th>Event</th>
    <th>Category</th>
    <th>Date</th>
    <th>Tickets</th>
    <th>Status</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>

<?php $i=1; while($ev = mysqli_fetch_assoc($events_q)): ?>

<?php
if($ev['date'] < $today){
    $status = '<span class="badge-status badge-expired">Expired</span>';
}elseif($ev['total_tickets'] > 0){
    $status = '<span class="badge-status badge-active">Active</span>';
}else{
    $status = '<span class="badge-status badge-sold">Sold Out</span>';
}
?>

<tr>
    <td><?= $i++ ?></td>
    <td><?= htmlspecialchars($ev['title']) ?></td>
    <td><?= getCategory($ev['title']) ?></td>
    <td><?= date('d M Y',strtotime($ev['date'])) ?></td>
    <td><?= $ev['total_tickets'] ?></td>
    <td><?= $status ?></td>
    <td>
        <a href="edit-event.php?id=<?= $ev['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
        <a href="delete-event.php?id=<?= $ev['id'] ?>"
           onclick="return confirm('Delete this event?')"
           class="btn btn-sm btn-danger">Delete</a>
    </td>
</tr>

<?php endwhile; ?>

</tbody>
</table>
</div>

</div>
</body>
</html>
