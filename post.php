<?php
// Menyertakan file koneksi
include 'controller/connection.php';

// Memulai sesi pengguna
session_start();

// Mendapatkan ID posting dari URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mendapatkan detail posting
$sql_post = "SELECT * FROM posts WHERE post_id = ?";
$stmt = $conn->prepare($sql_post);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$post_result = $stmt->get_result();
$post = $post_result->fetch_assoc();

if (!$post) {
    echo "Post not found.";
    exit;
}

// Update jumlah views
$sql_update_views = "UPDATE posts SET views = views + 1 WHERE post_id = ?";
$stmt_update_views = $conn->prepare($sql_update_views);
$stmt_update_views->bind_param("i", $post_id);
$stmt_update_views->execute();

// Proses pengiriman komentar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['username'])) {
    $comment = trim($_POST['comment']);
    if (!empty($comment)) {
        // Tambahkan komentar ke konten postingan dengan pemisah
        $updated_content = $post['content'] . "\n<!-- COMMENTS -->\n<strong>" . htmlspecialchars($_SESSION['username']) . ":</strong> " . htmlspecialchars($comment) . "\n";
        
        // Update konten di database
        $sql_update_content = "UPDATE posts SET content = ?, replies = replies + 1 WHERE post_id = ?";
        $stmt_update_content = $conn->prepare($sql_update_content);
        $stmt_update_content->bind_param("si", $updated_content, $post_id);
        $stmt_update_content->execute();

        // Redirect ulang ke halaman untuk menghindari pengiriman ulang form
        header("Location: post.php?id=$post_id");
        exit;
    }
}

// Memisahkan isi postingan dan komentar
list($post_content, $comments) = explode("<!-- COMMENTS -->", $post['content'] . "\n<!-- COMMENTS -->");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - ThreadFlow</title>
    <link rel="stylesheet" href="/CSS/post-styles.css">
</head>
<body>

    <!-- Header -->
    <header>
        <h1>ThreadFlow</h1>
        <div class="header-right">
            <?php
            if (isset($_SESSION['username'])) {
                $loggedInUser = htmlspecialchars($_SESSION['username']);
                echo "<span class='user-name'>Welcome, $loggedInUser</span>";
            } else {
                echo "<span class='user-name'><a href='login.php'>Login</a> / <a href='register.php'>Register</a></span>";
            }
            ?>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <article>
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            <p><em>By User ID <?php echo htmlspecialchars($post['user_id']); ?> on <?php echo htmlspecialchars($post['created_at']); ?></em></p>
            <div class="post-content">
                <?php echo nl2br(htmlspecialchars($post_content)); ?>
            </div>
            <p><strong>Views:</strong> <?php echo $post['views']; ?> | <strong>Replies:</strong> <?php echo $post['replies']; ?></p>
        </article>

        <!-- Comment Section -->
        <section id="comments">
            <h3>Comments</h3>
            <?php if (isset($_SESSION['username'])): ?>
                <!-- Form untuk menambahkan komentar -->
                <form method="POST">
                    <textarea name="comment" rows="4" placeholder="Write your comment here..." required></textarea>
                    <button type="submit">Post Comment</button>
                </form>
            <?php else: ?>
                <p><strong>You must <a href="login.php">login</a> to comment.</strong></p>
            <?php endif; ?>

            <!-- Daftar Komentar -->
            <ul class="comment-list">
                <?php
                if (!empty(trim($comments))) {
                    $comment_lines = explode("\n", trim($comments));
                    foreach ($comment_lines as $line) {
                        if (!empty(trim($line))) {
                            echo "<li>" . nl2br($line) . "</li>";
                        }
                    }
                } else {
                    echo "<p>No comments yet.</p>";
                }
                ?>
            </ul>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 ThreadFlow</p>
    </footer>

</body>
</html>
