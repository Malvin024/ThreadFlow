<?php
session_start();
require_once 'connection.php'; // Menghubungkan ke database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Inisialisasi variabel untuk error handling
$title = '';
$content = '';
$category_id = '';
$error = '';

// Ambil daftar kategori untuk dropdown
$query = "SELECT category_id, category_name FROM categories";
$result = $conn->query($query);

$categories = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Simpan kategori ke session untuk digunakan di halaman create.php
$_SESSION['categories'] = $categories;

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
        if ($stmt) {
            $stmt->bind_param("iiss", $_SESSION['user_id'], $category_id, $title, $content);

            // Eksekusi statement
            if ($stmt->execute()) {
                // Clear previous session data
                unset($_SESSION['error'], $_SESSION['title'], $_SESSION['content'], $_SESSION['category_id']);
                header('Location: ../index.php'); // Redirect ke halaman utama setelah post berhasil
                exit();
            } else {
                $_SESSION['error'] = "Failed to submit your post. Please try again.";
            }
            $stmt->close();
        } else {
            $_SESSION['error'] = "Failed to prepare the database query.";
        }
    }

    // Simpan input form sebelumnya ke session untuk repopulasi jika terjadi error
    $_SESSION['title'] = $title;
    $_SESSION['content'] = $content;
    $_SESSION['category_id'] = $category_id;

    // Redirect kembali ke form jika ada error
    header('Location: ../create.php');
    exit();
}
?>
