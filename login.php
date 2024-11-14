<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThreadFlow - Login</title>
    <link rel="stylesheet" href="/CSS/styles2.css">
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="/Images/logo.png" alt="ThreadFlow Logo">
        </div>
        <div class="login-box">
            <h1>Welcome to ThreadFlow</h1>
            <p class="subtitle">Connect & Collaborate</p>
            <form action="login.php" method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
                <div class="extra-links">
                    <a href="#">Forgot Password?</a>
                    <span>|</span>
                    <a href="/register.php">Sign Up</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
