<?php
session_start();
include '../../backend/config/db.php';

$message = '';
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);

    $result = mysqli_query($conn, "SELECT * FROM admins WHERE email='$email' AND password='$password'");
    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        $_SESSION['admin_id']   = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        header("Location: admin-dashboard.php");
        exit;
    } else {
        $message = "Invalid Email or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="bg-light">

<!-- CENTER WRAPPER -->
<div class="d-flex justify-content-center align-items-center" style="min-height:100vh;">

    <div class="container" style="max-width:400px;">
        <h3 class="mb-3 text-center">Admin Login</h3>

        <?php if($message): ?>
            <div class="alert alert-danger text-center">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
            <input type="password" name="password" placeholder="Password" class="form-control mb-2" required>
            <button class="btn btn-primary w-100" name="login">Login</button>
        </form>
    </div>

</div>

</body>
</html>
