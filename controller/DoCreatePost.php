<?php
session_start();
require_once 'connection.php'; // Ensure the correct path to the connection file

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit(); // Redirect to login page if the user is not logged in
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
        $categories[] = $row; // Store categories in an array
    }
} else {
    $_SESSION['error'] = "Failed to fetch categories from the database.";
    header('Location: ../create.php');
    exit(); // Redirect if categories can't be fetched
}

// Simpan kategori ke session untuk digunakan di halaman create.php
$_SESSION['categories'] = $categories;

// Proses saat form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Trim and sanitize user inputs
    $title = htmlspecialchars(trim($_POST['title']));
    $content = htmlspecialchars(trim($_POST['content']));
    $category_id = $_POST['category'];

    // Validasi input
    if (empty($title) || empty($content)) {
        $_SESSION['error'] = "Title and Content are required."; // Error message
    } elseif (!ctype_digit($category_id)) {
        $_SESSION['error'] = "Invalid category selected."; // Invalid category ID
    } else {
        // Siapkan statement untuk menambahkan post
        $stmt = $conn->prepare("INSERT INTO posts (user_id, category_id, title, content) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("iiss", $_SESSION['user_id'], $category_id, $title, $content);

            // Eksekusi statement
            if ($stmt->execute()) {
                // Clear previous session data
                unset($_SESSION['error'], $_SESSION['title'], $_SESSION['content'], $_SESSION['category_id'], $_SESSION['categories']);
                header('Location: ../index.php'); // Redirect to homepage after successful post
                exit();
            } else {
                $_SESSION['error'] = "Failed to submit your post. Please try again.";
            }
            $stmt->close();
        } else {
            $_SESSION['error'] = "Failed to prepare the database query."; // Error preparing statement
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