<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreadFlow - Forum Diskusi Modern</title>
    <style>
        /* Styling dasar untuk tata letak dan tema */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        /* Header styling */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #0056b3;
            padding: 15px;
            color: #fff;
        }
        header h1 {
            margin: 0;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .search-box {
            background-color: #ffffff;
            border: none;
            border-radius: 3px;
            padding: 5px;
            width: 200px;
        }
        .search-box::placeholder {
            color: #888;
        }
        header nav a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }
        /* Subheader styling */
        .subheader {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            background-color: #f1f1f1;
            gap: 10px;
        }
        .subheader button, .subheader select {
            padding: 8px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .subheader button {
            background-color: #0056b3;
            color: #fff;
            font-weight: bold;
        }
        .subheader select {
            background-color: #ffffff;
        }
        /* Main content styling */
        main {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .section {
            margin-bottom: 30px;
        }
        h2 {
            color: #0056b3;
            margin-bottom: 10px;
        }
        .section-content {
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #0078d4;
            color: #fff;
        }
        /* Pagination styling */
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination a {
            padding: 8px 12px;
            margin: 0 4px;
            border: 1px solid #0078d4;
            border-radius: 3px;
            color: #0078d4;
            text-decoration: none;
        }
        .pagination a.active, .pagination a:hover {
            background-color: #0078d4;
            color: #fff;
        }
        /* Footer styling */
        footer {
            background-color: #0078d4;
            color: #fff;
            text-align: center;
            padding: 10px;
            position: relative;
            bottom: 0;
            width: 100%;
            margin-top: 30px;
        }
        footer a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }
        /* Who is Online styling */
        .online-info {
            font-size: 0.9em;
            color: #555;
            background-color: #f1f1f1;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>ThreadFlow</h1>
        <div class="header-right">
            <input type="text" class="search-box" placeholder="Cari di ThreadFlow...">
            <nav>
                <a href="#login">Login</a> | <a href="#register">Register</a>
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
                        <!-- Tambahkan lebih banyak baris postingan terbaru jika diperlukan -->
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
                        <!-- Sample subjects; repeat as necessary to simulate multiple pages -->
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
                        <!-- Tambahkan lebih banyak row sesuai kebutuhan untuk simulasi pagination -->
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
        <p><a href="#about-us">About Us</a> | <a href="#contact-us">Contact Us</a></p>
    </footer>

    <script>
        function createPost() {
            alert("Navigasi ke halaman buat post baru.");
        }
        function refreshPage() {
            location.reload();
        }
    </script>

</body>
</html>
