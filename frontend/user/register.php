<?php
include '../../backend/config/db.php';
session_start();

$message = '';
if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, "INSERT INTO users (name,email,password) VALUES ('$name','$email','$password')");
        // 🔥 Redirect to login page after 2 seconds
        header("refresh:2;url=login.php");
        $message = "Registration Successful! Redirecting to login...";
    } else {
        $message = "Email already exists!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Register</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
    <div class="card" style="max-width:400px; width:100%;">
        
        <h3>User Registration</h3>

        <?php if($message): ?>
            <div class="alert alert-info text-center">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="name" class="form-control mb-3" placeholder="Name" required>
            <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
            <button type="submit" name="register" class="btn btn-primary w-100">
                Register
            </button>
        </form>

        <p class="auth-text">
            Already have an account?
            <a href="login.php">Login</a>
        </p>

    </div>
</div>

</body>
</html>
