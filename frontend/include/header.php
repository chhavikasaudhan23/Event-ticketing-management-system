<?php
session_start();
?>
<link rel="stylesheet" href="/event-ticketing-management-system/assets/style.css">

<div class="navbar glass">
  <div class="logo">
    🎟️ <strong>Event Management System</strong>
  </div>

  <div class="menu">
    <?php if(isset($_SESSION['user_id'])){ ?>

        <?php if($_SESSION['role']=='admin'){ ?>
            <a href="/event-ticketing-management-system/admin/dashboard.php">Dashboard</a>
            <a href="/event-ticketing-management-system/admin/add-event.php">Add Event</a>
        <?php } else { ?>
            <a href="/event-ticketing-management-system/user/dashboard.php">Dashboard</a>
            <a href="/event-ticketing-management-system/user/events.php">Events</a>
            <a href="/event-ticketing-management-system/user/my-bookings.php">My Bookings</a>
        <?php } ?>

        <a href="/event-ticketing-management-system/auth/logout.php">Logout</a>

    <?php } else { ?>
        <a href="/event-ticketing-management-system/auth/login.php">Login</a>
    <?php } ?>
  </div>
</div>
