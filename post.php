<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $username = htmlspecialchars($_POST['username']);
    $comment = htmlspecialchars($_POST['comment']);

    if (!empty($username) && !empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO comments (username, comment) VALUES (:username, :comment)");
        $stmt->execute(['username' => $username, 'comment' => $comment]);
    }
}

$stmt = $conn->prepare("SELECT * FROM comments ORDER BY created_at DESC");
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreadFlow - Post & Comment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="comment-section">
        <h1>Post & Comment</h1>
        <form method="POST" action="post.php">
            <input type="text" name="username" placeholder="Enter your name" required>
            <textarea name="comment" rows="4" placeholder="Write a comment..." required></textarea>
            <button type="submit">Submit</button>
        </form>

        <div class="comments-list">
            <h2>Comments:</h2>
            <?php if (!empty($comments)) : ?>
                <?php foreach ($comments as $comment) : ?>
                    <div class="comment-box">
                        <p><strong><?= htmlspecialchars($comment['username']) ?></strong> (<?= $comment['created_at'] ?>):</p>
                        <p><?= htmlspecialchars($comment['comment']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No comments yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
