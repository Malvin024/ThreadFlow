<?php
session_start();
require_once 'controller/connection.php'; // Asumsi ada koneksi database di file ini

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
            header("Location: index.php"); // Redirect ke halaman utama setelah post berhasil
            exit();
        } else {
            $error = "There was an error submitting your post.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Post - ThreadFlow</title>
    <link rel="stylesheet" href="/CSS/createpost.css"> <!-- Asumsi ada file CSS terpisah -->
</head>
<body>

<header>
    <h1>ThreadFlow</h1>
    <div class="header-right">
        <input type="text" class="search-box" placeholder="Cari di ThreadFlow...">
        <nav>
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </nav>
    </div>
</header>

<main>
    <div class="section">
        <h2>Create New Post</h2>

        <?php if ($error): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="createpost.php" method="POST">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($title); ?>" required>
            
            <label for="category">Category:</label>
            <select name="category" id="category" required>
                <option value="">Select Category</option>
                <option value="Gaming">Gaming</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['category_id']; ?>">
                        <?php echo htmlspecialchars($category['category_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="content">Content:</label>
            <textarea name="content" id="content" rows="8" required><?php echo htmlspecialchars($content); ?></textarea>

            <button type="submit">Submit Post</button>
        </form>
    </div>
</main>

<footer>
    <p><a href="#about-us">About Us</a> | <a href="#contact-us">Contact Us</a></p>
</footer>

</body>
</html>
