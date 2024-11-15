<?php
require_once 'controller/connection1.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Validate the reset token
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE reset_token = ? AND reset_token_expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['user_id'];

        // Update the user's password and clear the reset token
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE user_id = ?");
        $stmt->bind_param("si", $new_password, $user_id);
        $stmt->execute();

        echo "<p style='color: #4caf50;'>Your password has been reset successfully.</p>";
    } else {
        echo "<p style='color: #f44336;'>Invalid or expired token.</p>";
    }

    $stmt->close();
} else if (isset($_GET['token'])) {
    $token = $_GET['token'];
    // Render the password reset form
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password - ThreadFlow</title>
    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body>
    <div class="reset-password-container">
        <h1>Reset Password</h1>
        <form action="resetpassword.php" method="POST">
            <input type="hidden" name="token" value="$token">
            <input type="password" name="new_password" placeholder="Enter new password" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
HTML;
} else {
    echo "<p style='color: #f44336;'>Invalid request.</p>";
}
?>
