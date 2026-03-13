<?php
include '../../backend/config/db.php';
session_start();

$message = '';

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    
    if (mysqli_num_rows($query) == 1) {
        $user = mysqli_fetch_assoc($query);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: dashboard.php");
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
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>User Login</title>

<!-- Bootstrap CDN (MUST) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Main CSS -->
<link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
    <div class="card" style="max-width:400px; width:100%;">

        <h3>User Login</h3>

        <?php if($message): ?>
            <div class="alert alert-danger text-center">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

            <button type="submit" name="login" class="btn btn-primary w-100">
                Login
            </button>
        </form>

        <!-- 🔥 TEXT BREAK FIXED PROPERLY -->
        <p class="auth-text">
            Don't have an account?
            <a href="register.php">Register</a>
        </p>

    </div>
</div>

</body>
</html>
