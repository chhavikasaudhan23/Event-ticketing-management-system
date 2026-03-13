<?php
session_start();
include '../../backend/config/db.php';
if (!isset($_SESSION['admin_id'])) { 
    header("Location: admin-login.php"); 
    exit; 
}

$id = intval($_GET['id']);
$message = '';

if (isset($_POST['update'])) {
    $title = mysqli_real_escape_string($conn,$_POST['title']);
    $desc = mysqli_real_escape_string($conn,$_POST['description']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $venue = mysqli_real_escape_string($conn,$_POST['venue']);
    $total = $_POST['total_tickets'];
    $price = $_POST['price'];

    mysqli_query($conn,"UPDATE events SET title='$title', description='$desc', date='$date', time='$time', venue='$venue', total_tickets='$total', price='$price' WHERE id='$id'");
    $message = "Event Updated Successfully!";
}

$event = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM events WHERE id='$id'"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Event</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
<style>
/* ===== Glass Card ===== */
.card-glass{
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(12px);
    border-radius:14px;
    box-shadow:0 6px 20px rgba(0,0,0,0.15);
    padding:25px;
    max-width:600px;
    margin:30px auto;
}

/* Input & textarea */
.form-control{
    margin-bottom:12px;
}

/* Buttons */
.btn-dynamic{
    font-size:0.95rem;
    padding:10px 16px;
    border-radius:8px;
    margin-top:5px;
    transition:0.3s;
}
.btn-dynamic:hover{
    transform:scale(1.05);
    opacity:0.9;
}

/* Alert */
.alert-success{
    border-radius:12px;
    backdrop-filter: blur(6px);
    background: rgba(40,167,69,0.15);
    border:1px solid #28a745;
}

/* Header */
h3{
    text-align:center;
    margin-bottom:20px;
}
</style>
</head>
<body>

<div class="container">
    <div class="card-glass">
        <h3>Edit Event</h3>
        <?php if($message): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($event['title']) ?>" placeholder="Event Title" required>
            <textarea name="description" class="form-control" placeholder="Event Description" required><?= htmlspecialchars($event['description']) ?></textarea>
            <input type="date" name="date" class="form-control" value="<?= $event['date'] ?>" required>
            <input type="time" name="time" class="form-control" value="<?= $event['time'] ?>" required>
            <input type="text" name="venue" class="form-control" value="<?= htmlspecialchars($event['venue']) ?>" placeholder="Venue" required>
            <input type="number" name="total_tickets" class="form-control" value="<?= $event['total_tickets'] ?>" placeholder="Total Tickets" required>
            <input type="number" step="0.01" name="price" class="form-control" value="<?= $event['price'] ?>" placeholder="Price" required>

            <button class="btn btn-success btn-dynamic w-100" name="update">Update Event</button>
        </form>

        <a href="view-events.php" class="btn btn-primary btn-dynamic w-100 mt-2">Back to Events</a>
    </div>
</div>

</body>
</html>
