<?php
// Mulai session
session_start();

// Include koneksi ke database
require_once 'controller/connection.php';

// Cek apakah form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Menerima data dari form
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validasi input
    if (empty($username) || empty($password)) {
        $error = "Username and Password are required.";
    } else {
        // Siapkan query untuk mengambil data user berdasarkan username
        $stmt = $conn->prepare("SELECT user_id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username); // Bind parameter untuk username
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Ambil data user
            $user = $result->fetch_assoc();

            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Set session jika login berhasil
                $_SESSION['user_id'] = $user['user_id'];  // Gunakan user_id sebagai ID
                $_SESSION['username'] = $user['username'];

                // Redirect ke halaman utama atau dashboard
                header("Location: index.php"); // Ganti dengan halaman yang sesuai
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
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
    <title>ThreadFlow - Login</title>
    <link rel="stylesheet" href="/CSS/styles.css">
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="/Images/logo.png" alt="ThreadFlow Logo">
        </div>
        <div class="login-box">
            <h1>Welcome to ThreadFlow</h1>
            <p class="subtitle">Connect & Collaborate</p>

            <!-- Menampilkan error jika ada -->
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>

            <form action="login.php" method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
                <div class="extra-links">
                    <a href="/forgetpassword.php">Forgot Password?</a>
                    <span>|</span>
                    <a href="/register.php">Sign Up</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
