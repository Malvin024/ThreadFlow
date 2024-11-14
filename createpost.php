<?php
    $error = '';
    $title = '';
    $content = '';
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

        <?php if ($error): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <!-- <form action="createpost.php" method="POST"> -->
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
