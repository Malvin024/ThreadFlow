<?php
session_start();

// Include koneksi ke database
require_once './connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Menerima data dari form
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm-password']);

    // Validasi input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $_SESSION['error'] = "Password must be at least 8 characters long, contain at least one uppercase letter, one number, and one special character.";
    } else {
        // Cek apakah username sudah ada di database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['error'] = "Invalid username. This username is already taken.";
        } else {
            // Cek apakah email sudah ada di database
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $email_result = $stmt->get_result();

            if ($email_result->num_rows > 0) {
                $_SESSION['error'] = "Invalid email. This email is already registered.";
            } else {
                // Hash password untuk keamanan
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Siapkan query untuk insert data
                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hashed_password);

                // Eksekusi query
                if ($stmt->execute()) {
                    // Clear any previous error messages
                    unset($_SESSION['error']);
                    header('Location: ../login.php'); // Redirect ke halaman login setelah berhasil register
                    exit();
                } else {
                    $_SESSION['error'] = "There was an error registering your account.";
                }
            }
        }

        $stmt->close();
    }

    // Redirect back to register page with error
    header('Location: ../register.php');
    exit();
}

$conn->close();
?>
