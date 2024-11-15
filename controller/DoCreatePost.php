<?php
session_start();
require_once './connection.php'; // Asumsi ada koneksi database di file ini

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Inisialisasi variabel untuk error handling
$title = '';
$content = '';
$category_id = '';
$error = '';

// Proses saat form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = $_POST['category'];

    // Validasi input
    if (empty($title) || empty($content)) {
        $_SESSION['error'] = "Title and Content are required.";
    } elseif (!ctype_digit($category_id)) {
        $_SESSION['error'] = "Invalid category selected.";
    } else {
        // Siapkan statement untuk menambahkan post
        $stmt = $conn->prepare("INSERT INTO posts (user_id, category_id, title, content) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $_SESSION['user_id'], $category_id, $title, $content);

        // Eksekusi statement
        if ($stmt->execute()) {
            // Clear any previous error messages
            unset($_SESSION['error']);
            header('Location: index.php'); // Redirect ke halaman utama setelah post berhasil
            exit();
        } else {
            $_SESSION['error'] = "There was an error submitting your post.";
        }
        $stmt->close();
    }

    // Store submitted form data in session to repopulate in case of error
    $_SESSION['title'] = $title;
    $_SESSION['content'] = $content;
    $_SESSION['category_id'] = $category_id;

    // Redirect back to the form if there's an error
    header("Location: ../createpost.php");
    exit();
}
?>
