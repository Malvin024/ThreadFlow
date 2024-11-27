<?php



ini_set('session.cookie_httponly', 1);  
ini_set('session.use_only_cookies', 1); 
session_start();
session_regenerate_id(true);             


include('controller/connection1.php');


if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$loggedInUser = $_SESSION['username'];


$query = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $loggedInUser); 
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found!";
    exit();
}


$profilePicture = $user['profile_picture'] && file_exists('uploads/' . $user['profile_picture']) ? $user['profile_picture'] : 'default-picture.jfif';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - ThreadFlow</title>
    <link rel="stylesheet" href="/CSS/index-styles.css">
    <style>
        .profile-pic {
            width: 100px; 
            height: 100px; 
            border-radius: 50%; 
            object-fit: cover; 
            border: 2px solid #fff; 
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); 
        }
    </style>
</head>
<body>
    <header>
        <h1>ThreadFlow</h1>
        <div class="header-right">
            <span class="user-name">Welcome, <?php echo htmlspecialchars($user['username']); ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <main>
        <h2>Your Profile</h2>
        <div class="profile-section">
           
            <img src="uploads/<?php echo htmlspecialchars($profilePicture); ?>?<?php echo time(); ?>" alt="Profile Picture" class="profile-pic">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Joined:</strong> <?php echo date('Y-m-d', strtotime($user['created_at'])); ?></p>
        </div>

        <button onclick="window.location.href='edit_profile.php'">Edit Profile</button>
    </main>

    <footer>
        <div class="footer-content">
            <h3>About Us</h3>
            <p>
                ThreadFlow is a modern discussion forum platform designed to connect people through meaningful conversations. 
                We provide a space for users to share ideas, news, and experiences across various fields such as technology, local events, and general discussions.
            </p>
            <p>&copy; 2024 ThreadFlow - Copyright</p>
        </div>
    </footer>

</body>
</html>

<?php

$stmt->close();
$conn->close();
?>
