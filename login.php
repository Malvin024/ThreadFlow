<?php


session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_start();


if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lockout_time'] = null;
}


$lockout_duration = 15 * 60; 
$lockout_warning = '';
if ($_SESSION['lockout_time'] && time() < $_SESSION['lockout_time'] + $lockout_duration) {
    $remaining_time = ($_SESSION['lockout_time'] + $lockout_duration) - time();
    $lockout_warning = "Too many failed login attempts. Please try again in " . ceil($remaining_time / 60) . " minutes.";
} elseif ($_SESSION['lockout_time'] && time() >= $_SESSION['lockout_time'] + $lockout_duration) {
   
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lockout_time'] = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="Images/panda.ico" />    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreadFlow - Login</title>
    <link rel="stylesheet" href="/CSS/styles.css">
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="/Images/logo.png" alt="ThreadFlow Logo">
        </div>
        <div class="login-box">
            <h1>Welcome to ThreadFlow</h1>
            <p class="subtitle">Connect & Collaborate</p>

           
            <?php if (isset($_SESSION['error'])): ?>
                <p style="color: red;"><?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); ?></p>
                <?php unset($_SESSION['error']);  ?>
            <?php endif; ?>

           
            <form action="/controller/DoLogin.php" method="POST">
                
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

               
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>

                <div class="form-actions">
                    <button type="submit">Login</button>
                   
                    <?php if (!empty($lockout_warning)): ?>
                        <p style="color: red; display: inline-block; margin-left: 10px;"><?php echo htmlspecialchars($lockout_warning, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="extra-links">
                    <a href="/forgetpassword.php">Forgot Password?</a>
                    <span>|</span>
                    <a href="/register.php">Sign Up</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
