<?php
session_start();
require_once './controller/connection.php'; // Asumsi ada koneksi database di file ini

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Mendapatkan daftar kategori untuk pilihan dalam form
$query = "SELECT * FROM categories";
$result = $conn->query($query);

$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Inisialisasi variabel untuk error handling
$error = '';
$title = '';
$content = '';

// Proses saat form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = $_POST['category'];

    // Validasi input
    if (empty($title) || empty($content)) {
        $error = "Title and Content are required.";
    } elseif (!ctype_digit($category_id)) {
        $error = "Invalid category selected.";
    } else {
        // Siapkan statement untuk menambahkan post
        $stmt = $conn->prepare("INSERT INTO posts (user_id, category_id, title, content) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $_SESSION['user_id'], $category_id, $title, $content);

        // Eksekusi statement
        if ($stmt->execute()) {
            header('Location: index.php'); // Redirect ke halaman utama setelah post berhasil
            exit();
        } else {
            $error = "There was an error submitting your post.";
        }
        $stmt->close();
    }
}
?>
