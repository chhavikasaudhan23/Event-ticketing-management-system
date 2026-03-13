<?php
session_start();
include '../../backend/config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: view-events.php");
    exit;
}

$event_id = intval($_GET['id']);

/* ===== TRANSACTION START ===== */
mysqli_begin_transaction($conn);

try {

    // 1️⃣ Delete bookings of this event first
    $deleteBookings = mysqli_query(
        $conn,
        "DELETE FROM bookings WHERE event_id = '$event_id'"
    );

    if (!$deleteBookings) {
        throw new Exception("Failed to delete bookings");
    }

    // 2️⃣ Delete the event
    $deleteEvent = mysqli_query(
        $conn,
        "DELETE FROM events WHERE id = '$event_id'"
    );

    if (!$deleteEvent) {
        throw new Exception("Failed to delete event");
    }

    // ✅ All good → commit
    mysqli_commit($conn);

    header("Location: view-events.php?msg=deleted");
    exit;

} catch (Exception $e) {

    // ❌ Something failed → rollback
    mysqli_rollback($conn);
    echo "Error deleting event!";
}
