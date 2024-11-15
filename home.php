<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreadFlow - Forum Diskusi Modern</title>
    <link rel="stylesheet" href="/CSS/index-styles.css">
</head>
<body>

    <!-- Header -->
    <header>
        <h1>ThreadFlow</h1>
        <div class="header-right">
            <?php
            session_start(); // Memulai sesi
            if (isset($_SESSION['username'])) {
                $loggedInUser = htmlspecialchars($_SESSION['username']);
                echo "<span class='user-name'>Welcome, $loggedInUser</span>";
            } else {
                echo "<span class='user-name'><a href='login.php'>Login</a> / <a href='register.php'>Register</a></span>";
            }
            ?>
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
                        <tr>
                            <td><a href="#post1">Post Terbaru 1</a></td>
                            <td>User A</td>
                            <td>2024-11-14</td>
                        </tr>
                        <tr>
                            <td><a href="#post2">Post Terbaru 2</a></td>
                            <td>User B</td>
                            <td>2024-11-13</td>
                        </tr>
                        <tr>
                            <td><a href="#post3">Post Terbaru 3</a></td>
                            <td>User C</td>
                            <td>2024-11-12</td>
                        </tr>
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

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <h3>About Us</h3>
            <p>
                ThreadFlow is a modern discussion forum platform designed to connect people through meaningful conversations. 
                We provide a space for users to share ideas, news, and experiences across various fields such as technology, local events, and general discussions.
            </p>
            <h4>Contact Us</h4>
            <p>
                If you have any questions or need assistance, feel free to contact us:
            </p>
            <p>
                <strong>Email:</strong> support@threadflow.com <br>
                <strong>Phone:</strong> +123-456-7890
            </p>
            <p>&copy; 2024 ThreadFlow - Copyright</p>
        </div>
    </footer>

    <script>
        function createPost() {
            window.location.href = 'createpost.php';
        }
        function refreshPage() {
            location.reload();
        }
    </script>

</body>
</html>
