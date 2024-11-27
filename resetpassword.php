<?php
require_once 'controller/connection1.php';


session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("<p style='color: #f44336;'>CSRF token mismatch. Request is invalid.</p>");
    }

    $token = $_POST['token'];
    $new_password = $_POST['new_password'];


    if (strlen($new_password) < 8 || !preg_match('/[A-Za-z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
        die("<p style='color: #f44336;'>Password must be at least 8 characters long and contain both letters and numbers.</p>");
    }

 
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

   
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE reset_token = ? AND reset_token_expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['user_id'];

       
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE user_id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);
        $stmt->execute();

        echo "<p style='color: #4caf50;'>Your password has been reset successfully.</p>";
    } else {
        echo "<p style='color: #f44336;'>Invalid or expired token.</p>";
    }

    $stmt->close();
} else if (isset($_GET['token'])) {
    $token = $_GET['token'];
    

    if (strlen($token) !== 64 || !ctype_xdigit($token)) {
        die("<p style='color: #f44336;'>Invalid token format.</p>");
    }

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
            <input type="hidden" name="csrf_token" value="{$_SESSION['csrf_token']}">  
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
