<?php
session_start();
include('controller/connection1.php');

// Set the default page number and items per page for all posts
$items_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $items_per_page;

// Get the sort option (default to latest)
$sort_option = isset($_GET['order']) ? $_GET['order'] : 'latest';

// Determine the SQL ORDER BY clause based on the sort option
switch ($sort_option) {
    case 'popular':
        $order_by = 'ORDER BY posts.views DESC'; // Sort by most views (popularity)
        break;
    case 'trending':
        $order_by = 'ORDER BY posts.replies DESC'; // Sort by most replies (trending)
        break;
    case 'latest':
    default:
        $order_by = 'ORDER BY posts.created_at DESC'; // Sort by latest posts
        break;
}

// Query to get the latest posts (3 posts for the "Latest Posts" section)
$sql_latest = "
    SELECT 
        posts.post_id, 
        posts.title, 
        posts.content, 
        posts.created_at AS post_created_at,
        categories.category_name, 
        users.username AS author_name,
        users.profile_picture
    FROM 
        posts
    JOIN 
        categories ON posts.category_id = categories.category_id
    JOIN 
        users ON posts.user_id = users.user_id
    $order_by
    LIMIT 3
";

$latest_result = $conn->query($sql_latest);

// Query to get all posts from all users, sorted by creation date, with pagination
$sql_all_posts = "
    SELECT 
        posts.post_id, 
        posts.title, 
        posts.content, 
        posts.created_at AS post_created_at,
        categories.category_name, 
        users.username AS author_name,
        users.profile_picture
    FROM 
        posts
    JOIN 
        categories ON posts.category_id = categories.category_id
    JOIN 
        users ON posts.user_id = users.user_id
    $order_by
    LIMIT $items_per_page OFFSET $offset
";

$result_all_posts = $conn->query($sql_all_posts);

// Get total number of posts to calculate pagination for the all posts section
$sql_count = "SELECT COUNT(*) AS total_posts FROM posts";
$total_result = $conn->query($sql_count);
$total_row = $total_result->fetch_assoc();
$total_posts = $total_row['total_posts'];
$total_pages = ceil($total_posts / $items_per_page);

// Get all categories from the database for filtering
$category_sql = "SELECT category_id, category_name FROM categories ORDER BY category_name";
$category_result = $conn->query($category_sql);

// Check if user is logged in
$is_logged_in = isset($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="shortcut icon" href="Images/panda.ico" /> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreadFlow - Forum Diskusi Modern</title>
    <link rel="stylesheet" href="/CSS/index-styles.css">
    <link rel="stylesheet" href="/CSS/modal-styles.css"> <!-- Link to the new CSS file -->
</head>
<body>

    <!-- Header -->
    <header>
        <a href="home.php">
            <h1>ThreadFlow</h1>
        </a>
        <div class="header-right">
            <input type="text" class="search-box" placeholder="Cari di ThreadFlow...">
            <nav>
                <?php if ($is_logged_in): ?>
                    <div class="user-info">
                        <a href="profile.php">
                            <img src="uploads/<?php echo htmlspecialchars($_SESSION['profile_picture'] ?? 'default-picture.jfif'); ?>?<?php echo time(); ?>" alt="Profile Picture" class="profile-picture">
                        </a>
                        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> | <a href="/logout.php">Logout</a></p>
                    </div>
                <?php else: ?>
                    <a href="/login.php">Login</a> | <a href="/register.php">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <!-- Subheader -->
    <div class="subheader">
        <button onclick="window.location.href='createpost.php'">Create Post</button>
        <button onclick="refreshPage()">Refresh</button>
        <label for="order">Order:</label>
        <select id="order" onchange="window.location.href='home.php?order=' + this.value">
            <option value="latest" <?php echo ($sort_option == 'latest') ? 'selected' : ''; ?>>Latest</option>
            <option value="popular" <?php echo ($sort_option == 'popular') ? 'selected' : ''; ?>>Popular</option>
            <option value="trending" <?php echo ($sort_option == 'trending') ? 'selected' : ''; ?>>Trending</option>
        </select>
        <label for="categories">Categories:</label>
        <select id="categories" onchange="window.location.href='home.php?category=' + this.value">
            <option value="all">All Categories</option>
            <?php while ($category = $category_result->fetch_assoc()): ?>
                <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <!-- Latest Post Section -->
    <main>
        <?php if ($page == 1): ?>  <!-- Only show latest posts on the first page -->
        <div class="section" id="latest-posts">
            <h2>Latest Posts</h2>
            <div class="section-content">
                <table>
                    <thead>
                        <tr>
                            <th>Post</th>
                            <th>Author</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Display the latest 3 posts
                        if ($latest_result->num_rows > 0) {
                            while ($post = $latest_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><a href='post.php?id=" . htmlspecialchars($post['post_id']) . "'>" . htmlspecialchars($post['title']) . "</a></td>";
                                echo "<td>" . htmlspecialchars($post['author_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($post['post_created_at']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No posts available yet. Be the first to create a post!</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- All Posts Section with Pagination -->
        <div class="section" id="all-posts">
            <h2>All Posts</h2>
            <div class="section-content">
                <table>
                    <thead>
                        <tr>
                            <th>Post</th>
                            <th>Author</th>
                            <th>Date</th>
                            <th>Category</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Display all posts with pagination
                        if ($result_all_posts->num_rows > 0) {
                            while ($post = $result_all_posts->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><a href='post.php?id=" . htmlspecialchars($post['post_id']) . "'>" . htmlspecialchars($post['title']) . "</a></td>";
                                echo "<td>" . htmlspecialchars($post['author_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($post['post_created_at']) . "</td>";
                                echo "<td>" . htmlspecialchars($post['category_name']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No posts available.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="home.php?page=<?php echo $page - 1; ?>&order=<?php echo $sort_option; ?>">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="home.php?page=<?php echo $i; ?>&order=<?php echo $sort_option; ?>" <?php echo ($i == $page) ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="home.php?page=<?php echo $page + 1; ?>&order=<?php echo $sort_option; ?>">Next</a>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 ThreadFlow. All rights reserved.</p>
    </footer>

    <script src="JS/index.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
