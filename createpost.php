<?php
session_start();

// Set error and form values from the session, if available
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
$title = isset($_SESSION['title']) ? $_SESSION['title'] : '';
$content = isset($_SESSION['content']) ? $_SESSION['content'] : '';
$category_id = isset($_SESSION['category_id']) ? $_SESSION['category_id'] : '';

// Mendapatkan daftar kategori untuk pilihan dalam form
require_once './controller/connection.php'; // Include koneksi ke database

$query = "SELECT * FROM categories";
$result = $conn->query($query);

$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Clear session data after loading
unset($_SESSION['error'], $_SESSION['title'], $_SESSION['content'], $_SESSION['category_id']);
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
    <form action="controller/DoCreatePost.php" method="POST">
        <h2>Create New Post</h2>

        <!-- Menampilkan error jika ada -->
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <label for="title">Title:</label>
        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($title); ?>" required>
        
        <label for="category">Category:</label>
        <select name="category" id="category" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['category_id']; ?>" <?php if ($category['category_id'] == $category_id) echo 'selected'; ?>>
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
