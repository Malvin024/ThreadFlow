<?php
session_start();

session_unset();
session_destroy();

$redirect_delay = 3;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - ThreadFlow</title>
    <link rel="stylesheet" href="/CSS/logout-styles.css"> <!-- Tambahkan link ke file CSS jika diperlukan -->
</head>
<body>
    <div class="logout-container">
        <h1>Anda telah berhasil logout</h1>
        <p>Terima kasih sudah menggunakan ThreadFlow. Anda akan diarahkan kembali ke halaman login dalam beberapa detik...</p>
        <p>Jika tidak otomatis, klik <a href="login.php">di sini</a>.</p>
    </div>

    <script>
        setTimeout(function() {
            window.location.href = 'login.php';
        }, <?php echo $redirect_delay * 1000; ?>);
    </script>
</body>
</html>
