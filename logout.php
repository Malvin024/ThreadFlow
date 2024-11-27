<?php
session_start();


session_unset();


session_destroy();

session_start();
session_regenerate_id(true); 

$redirect_delay = 3; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - ThreadFlow</title>
    <link rel="stylesheet" href="/CSS/styles.css"> 
</head>
<body>
    <div class="logout-container">
        <h1>You have successfully logged out</h1>
        <p>Thank you for using ThreadFlow. You will be redirected to the login page in a few seconds...</p>
        <p>If not, click <a href="login.php">here</a>.</p>
    </div>

   
    <?php
        
        header("refresh: $redirect_delay; url=login.php");
    ?>
</body>
</html>
