<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    echo "<p style='color: #4caf50;'>We have sent a password reset link to your email.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreadFlow - Forgot Password</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
</head>
<body>
    <div class="forgot-password-container">
        <div class="logo">
            <img src="/Images/logo.png" alt="Logo">
        </div>
        <h1>Forgot Password</h1>
        <p class="subtitle">Enter your email to receive a password reset link.</p>
        <div class="forgot-password-box">
            <form action="forgotpassword.php" method="POST">
                <input type="email" name="email" placeholder="Enter your email" required>
                <button type="submit">Send Reset Link</button>
            </form>
        </div>
        <div class="extra-links">
            <a href="login.php">Remember your password? Login</a>
        </div>
    </div>
</body>
</html>
