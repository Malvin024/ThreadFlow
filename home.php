<?php
session_start();
include('controller/connection1.php');

// Query to get all posts from all users, sorted by creation date
$sql = "
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
    ORDER BY 
        posts.created_at DESC
";

$result = $conn->query($sql);

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
        <h1>ThreadFlow</h1>
        <div class="header-right">
            <input type="text" class="search-box" placeholder="Cari di ThreadFlow...">
            <nav>
                <?php if ($is_logged_in): ?>
                    <div class="user-info">
                        <a href="profile.php">
                            <img src="uploads/<?php echo htmlspecialchars($_SESSION['profile_picture'] ?? 'default-picture.jfif'); ?>" alt="Profile Picture" class="profile-picture">
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
        <button onclick="createPost()">Create Post</button>
        <button onclick="refreshPage()">Refresh</button>
        <label for="order">Order:</label>
        <select id="order">
            <option value="latest">Latest</option>
            <option value="popular">Popular</option>
            <option value="trending">Trending</option>
        </select>
        <label for="categories">Categories:</label>
        <select id="categories">
            <option value="all">All Categories</option>
            <option value="tech">Technology</option>
            <option value="news">News</option>
            <option value="local">Local</option>
            <option value="general">General Discussion</option>
        </select>
    </div>

    <!-- Latest Post Section -->
    <main>
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
                        // Display all posts dynamically
                        if ($result->num_rows > 0) {
                            while ($post = $result->fetch_assoc()) {
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

        <!-- Subject Section -->
        <div id="subjects" class="section">
            <h2>Subject</h2>
            <div class="section-content">
                <table>
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Author</th>
                            <th>Date</th>
                            <th>Views</th>
                            <th>Replies</th>
                            <th>Category</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Subject 1</td>
                            <td>User A</td>
                            <td>2024-11-14</td>
                            <td>100</td>
                            <td>10</td>
                            <td>Technology</td>
                        </tr>
                        <tr>
                            <td>Subject 2</td>
                            <td>User B</td>
                            <td>2024-11-13</td>
                            <td>250</td>
                            <td>20</td>
                            <td>News</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination Links -->
                <div class="pagination">
                    <a href="#">&laquo;</a>
                    <a href="#" class="active">1</a>
                    <a href="#">2</a>
                    <a href="#">3</a>
                    <a href="#">4</a>
                    <a href="#">5</a>
                    <a href="#">&raquo;</a>
                </div>
            </div>
        </div>

        <!-- Who is Online Section -->
        <div class="online-info">
            <p><strong>Who is Online</strong></p>
            <p>Pengguna Online: <strong>5</strong></p>
            <p>Pengguna Terdaftar: <strong>150</strong></p>
            <p>Total Posts: <strong>2300</strong></p>
        </div>
    </main>

    <!-- Modal Popup -->
    <div id="loginModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>You need to login to create a post.</p>
            <a href="login.php" class="login-btn">Login</a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 ThreadFlow</p>
    </footer>

    <script src="JS/script.js"></script>
</body>
</html>
