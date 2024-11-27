<?php


ini_set('session.cookie_httponly', 1);  
ini_set('session.use_only_cookies', 1); 
session_start();
session_regenerate_id(true);            


include('controller/connection1.php');


$items_per_page = 10;
$page = isset($_GET['page']) ? filter_var($_GET['page'], FILTER_VALIDATE_INT) : 1;
$page = $page ? $page : 1; 
$offset = ($page - 1) * $items_per_page;


$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_query = htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8'); 


$sort_option = isset($_GET['order']) ? $_GET['order'] : 'latest';


switch ($sort_option) {
    case 'popular':
        $order_by = 'ORDER BY posts.views DESC'; 
        break;
    case 'trending':
        $order_by = 'ORDER BY posts.replies DESC'; 
        break;
    case 'latest':
    default:
        $order_by = 'ORDER BY posts.created_at DESC'; 
        break;
}


$search_condition = $search_query ? "WHERE posts.title LIKE ? OR posts.content LIKE ?" : '';


$latest_result = null; 
if (!$search_query) {  
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
        $search_condition
        $order_by
        LIMIT 3
    ";

    $stmt = $conn->prepare($sql_latest);
    if ($search_query) {
        $search_like = "%$search_query%";
        $stmt->bind_param('ss', $search_like, $search_like); 
    }
    $stmt->execute();
    $latest_result = $stmt->get_result();
}


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
    $search_condition
    $order_by
    LIMIT ? OFFSET ?
";

$stmt_all_posts = $conn->prepare($sql_all_posts);
if ($search_query) {
    $search_like = "%$search_query%";
    
    $stmt_all_posts->bind_param('ssii', $search_like, $search_like, $items_per_page, $offset);
} else {
    
    $stmt_all_posts->bind_param('ii', $items_per_page, $offset);
}
$stmt_all_posts->execute();
$result_all_posts = $stmt_all_posts->get_result();


$sql_count = "SELECT COUNT(*) AS total_posts FROM posts $search_condition";
$stmt_count = $conn->prepare($sql_count);
if ($search_query) {
    $stmt_count->bind_param('ss', $search_like, $search_like); 
}
$stmt_count->execute();
$total_result = $stmt_count->get_result();
$total_row = $total_result->fetch_assoc();
$total_posts = $total_row['total_posts'];
$total_pages = ceil($total_posts / $items_per_page);


$category_sql = "SELECT category_id, category_name FROM categories ORDER BY category_name";
$category_result = $conn->query($category_sql);


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
    <link rel="stylesheet" href="/CSS/modal-styles.css"> 
</head>
<body>

    
    <header>
        <a href="home.php">
            <h1>ThreadFlow</h1>
        </a>
        <div class="header-right">
            <form method="GET" action="home.php">
                <input type="text" class="search-box" name="search" placeholder="Search here..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit" class="search-button">Search</button>
            </form>
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

   
    <main>
        
        <?php if ($page == 1 && !$search_query): ?>  
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
                        
                        if ($latest_result && $latest_result->num_rows > 0) {
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

    
    <footer>
        <p>&copy; 2024 ThreadFlow. All rights reserved.</p>
    </footer>

    <script src="JS/index.js"></script>
</body>
</html>

<?php

$conn->close();
?>
