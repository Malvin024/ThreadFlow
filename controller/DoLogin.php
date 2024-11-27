<?php
session_start();
include('connection.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = 'Invalid CSRF token.';
        header('Location: /login.php');
        exit();
    }

   
    $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
    $password = trim($_POST['password']);

  
    if ($_SESSION['login_attempts'] >= 5) {
        $_SESSION['lockout_time'] = time(); 
        $_SESSION['error'] = 'Too many failed login attempts. Please try again later.';
        header('Location: /login.php');
        exit();
    }


    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
      
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $username;
        $_SESSION['login_attempts'] = 0; 
        $_SESSION['lockout_time'] = null; 
        header('Location: /home.php'); 
        exit();
    } else {
      
        $_SESSION['login_attempts'] += 1; 
        $_SESSION['error'] = 'Invalid username or password.';
        header('Location: /login.php');
        exit();
    }
}
?>