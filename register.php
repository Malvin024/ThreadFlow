<?php
// Include koneksi ke database
require_once 'controller/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Menerima data dari form
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm-password']);

    // Validasi input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $error = "Password must be at least 8 characters long, contain at least one uppercase letter, one number, and one special character.";
    } else {
        // Hash password untuk keamanan
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Siapkan query untuk insert data
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        // Eksekusi query
        if ($stmt->execute()) {
            header("Location: login.php"); // Redirect ke halaman login setelah berhasil register
            exit();
        } else {
            $error = "There was an error registering your account.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreadFlow - Register</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
</head>
<body>
    <div class="register-container">
        <div class="logo">
            <img src="/Images/logo.png" alt="Logo">
        </div>
        <h1>Create Account</h1>
        <p class="subtitle">Join ThreadFlow today!</p>

        <!-- Menampilkan error jika ada -->
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <div class="login-box">
            <form action="register.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm-password" placeholder="Confirm Password" required>
                
                <!-- Menambahkan pesan persyaratan password -->
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
