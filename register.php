<?php
session_start();

// Menampilkan error jika ada
if (isset($_SESSION['error'])) {
    $errorMessage = $_SESSION['error'];
    unset($_SESSION['error']); // Clear error after displaying
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="Images/panda.ico" /> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreadFlow - Register</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
</head>
<body>
    <div class="register-container">
        <div class="logo1">
            <img src="/Images/logo.png" alt="Logo">
        </div>
        <h1>Create Account</h1>
        <p class="subtitle">Join ThreadFlow today!</p>

        <!-- Menampilkan error jika ada -->
        <?php if (isset($errorMessage)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>

        <div class="login-box">
            <form action="DoRegister.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm-password" placeholder="Confirm Password" required>
                
                <p class="password-requirements">
                    Password must be at least 8 characters long, contain at least one uppercase letter, one number, and one special character.
                </p>

                <button type="submit">Register</button>
            </form>
        </div>
        <div class="extra-links">
            <a href="/login.php">Already have an account? Login</a>
        </div>
    </div>
</body>
</html>
