<?php
session_start();
include 'db_connection.php'; // Menyertakan file koneksi database (jika ada)

// Fungsi untuk mencegah XSS
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Mengambil input dari form dan melakukan sanitasi untuk mencegah XSS
$username = isset($_POST['username']) ? sanitize_input($_POST['username']) : '';
$email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirmPassword = isset($_POST['confirm-password']) ? $_POST['confirm-password'] : '';

// Validasi password
if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[0-9]/", $password) || !preg_match("/[\W_]/", $password)) {
    $_SESSION['error'] = 'Password must be at least 8 characters long, contain at least one uppercase letter, one number, and one special character.';
    header("Location: /register.php");
    exit();
}

// Validasi jika password dan konfirmasi password cocok
if ($password !== $confirmPassword) {
    $_SESSION['error'] = 'Passwords do not match.';
    header("Location: /register.php");
    exit();
}

// Hash password sebelum disimpan
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Koneksi ke database menggunakan PDO
try {
    $conn = new PDO("mysql:host=localhost;dbname=your_db", "your_username", "your_password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Periksa apakah username atau email sudah terdaftar
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $_SESSION['error'] = 'Username or Email already exists.';
        header("Location: /register.php");
        exit();
    }

    // Menyimpan data pengguna
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    $_SESSION['success'] = 'Registration successful. You can now login.';
    header("Location: /login.php");
    exit();
} catch (PDOException $e) {
    $_SESSION['error'] = 'Error: ' . $e->getMessage();
    header("Location: /register.php");
    exit();
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
        <?php if (isset($_SESSION['error'])): ?>
            <p style="color: red;"><?php echo htmlspecialchars($_SESSION['error']); ?></p>
            <?php unset($_SESSION['error']); // Clear error after displaying ?>
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
