<?php
session_start();
require_once 'connection.php'; 


if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit(); 
}


$title = '';
$content = '';
$category_id = '';
$error = '';


$query = "SELECT category_id, category_name FROM categories";
$result = $conn->query($query);

$categories = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row; 
    }
} else {
    $_SESSION['error'] = "Failed to fetch categories from the database.";
    header('Location: ../createpost.php');
    exit(); 
}


$_SESSION['categories'] = $categories;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed.");
    }

 
    $title = htmlspecialchars(trim($_POST['title']));
    $content = htmlspecialchars(trim($_POST['content']));
    $category_id = $_POST['category'];


    if (empty($title) || empty($content)) {
        $_SESSION['error'] = "Title and Content are required."; 
    } elseif (!ctype_digit($category_id)) {
        $_SESSION['error'] = "Invalid category selected."; 
    } else {
     
        $stmt = $conn->prepare("INSERT INTO posts (user_id, category_id, title, content) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("iiss", $_SESSION['user_id'], $category_id, $title, $content);

           
            if ($stmt->execute()) {
               
                unset($_SESSION['error'], $_SESSION['title'], $_SESSION['content'], $_SESSION['category_id'], $_SESSION['categories']);
                header('Location: ../index.php'); 
                exit();
            } else {
                $_SESSION['error'] = "Failed to submit your post. Please try again.";
            }
            $stmt->close();
        } else {
            $_SESSION['error'] = "Failed to prepare the database query."; 
        }
    }

   
    $_SESSION['title'] = $title;
    $_SESSION['content'] = $content;
    $_SESSION['category_id'] = $category_id;

 
    header('Location: ../createpost.php');
    exit();
}
?>
