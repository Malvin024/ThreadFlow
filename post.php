<?php
// Include the connection file
include 'controller/connection1.php';

// Start the session
session_start();

// Get the post ID from the URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query to get the post details
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

// Update view count
$sql_update_views = "UPDATE posts SET views = views + 1 WHERE post_id = ?";
$stmt_update_views = $conn->prepare($sql_update_views);
$stmt_update_views->bind_param("i", $post_id);
$stmt_update_views->execute();

// Process comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['username'])) {
    $comment = trim($_POST['comment']);
    if (!empty($comment)) {
        // Get the current time for the comment
        $comment_time = date('Y-m-d H:i:s');
        $username = $_SESSION['username'];

        // Add the comment to the content with proper formatting
        $new_comment = "<strong>" . htmlspecialchars($username) . ":</strong> " . htmlspecialchars($comment) . " <em>on " . $comment_time . "</em>\n";
        
        // Update the post's content with the new comment
        $sql_update_content = "UPDATE posts SET content = CONCAT(content, '\n<!-- COMMENTS -->', ?) WHERE post_id = ?";
        $stmt_update_content = $conn->prepare($sql_update_content);
        $stmt_update_content->bind_param("si", $new_comment, $post_id);
        $stmt_update_content->execute();

        // Redirect to avoid form resubmission
        header("Location: post.php?id=$post_id");
        exit;
    }
}

// Separate post content and comments
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
            <div class="post-meta">
                <span><strong>By:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <span class="post-date"><?php echo htmlspecialchars($post['created_at']); ?></span>
            </div>
            <div class="post-content">
                <?php echo nl2br(htmlspecialchars($post_content)); ?>
            </div>
            <p><strong>Views:</strong> <?php echo $post['views']; ?> | <strong>Replies:</strong> <?php echo $post['replies']; ?></p>
        </article>


        <!-- Comment Section -->
        <!-- Comment Section -->
<section id="comments">
    <h3>Comments</h3>
    <?php if (isset($_SESSION['username'])): ?>
        <!-- Form for adding a comment -->
        <form method="POST">
            <textarea name="comment" rows="4" placeholder="Write your comment here..." required></textarea>
            <button type="submit">Post Comment</button>
        </form>
    <?php else: ?>
        <p><strong>You must <a href="login.php">login</a> to comment.</strong></p>
    <?php endif; ?>

    <!-- Display Comments -->
    <ul class="comment-list">
        <?php
        if (!empty(trim($comments))) {
            $comment_lines = explode("\n", trim($comments));
            foreach ($comment_lines as $line) {
                if (!empty(trim($line))) {
                    // Split comment and timestamp
                    $parts = explode(" <em>", $line);
                    $comment_text = $parts[0]; // Comment text
                    $comment_time = isset($parts[1]) ? $parts[1] : ''; // Date part
                    echo "<li><div class='comment-text'>" . nl2br($comment_text) . "</div><div class='comment-time'>" . htmlspecialchars($comment_time) . "</div></li>";
                }
            }
        } else {
            echo "<p>No comments yet.</p>";
        }
        ?>
    </ul>
</section>

    <!-- Footer -->
    <footer>
        <t>&copy; 2024 ThreadFlow</t>
    </footer>

</body>
</html>
