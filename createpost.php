<!DOCTYPE html>
<html lang="id">
<head>  
    <link rel="shortcut icon" href="Images/panda.ico" /> 
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
        <?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
            <p style="color: red;"><?php echo htmlspecialchars($_SESSION['error']); ?></p>
        <?php endif; ?>

        <label for="title">Title:</label>
        <input type="text" name="title" id="title" value="<?php echo isset($_SESSION['title']) ? htmlspecialchars($_SESSION['title']) : ''; ?>" required>
        
        <label for="category">Category:</label>
        <select name="category" id="category" required>
            <option value="">Select Category</option>
            <!-- Assuming categories are preloaded via PHP -->
            <?php foreach ($_SESSION['categories'] as $category): ?>
                <option value="<?php echo $category['category_id']; ?>" <?php echo (isset($_SESSION['category_id']) && $_SESSION['category_id'] == $category['category_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['category_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="content">Content:</label>
        <textarea name="content" id="content" rows="8" required><?php echo isset($_SESSION['content']) ? htmlspecialchars($_SESSION['content']) : ''; ?></textarea>

        <button type="submit">Submit Post</button>
    </form>
    </div>
</main>

<footer>
    <p><a href="#about-us">About Us</a> | <a href="#contact-us">Contact Us</a></p>
</footer>

</body>
</html>
