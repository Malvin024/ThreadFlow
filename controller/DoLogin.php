<?php
// Mulai session
session_start();

// Include koneksi ke database
require_once './connection.php';

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
                header('Location: ../index.php'); // Ganti dengan halaman yang sesuai
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
