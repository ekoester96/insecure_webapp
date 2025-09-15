<?php
session_start();

// If the user is not logged in, redirect them to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
        <p>This is your protected dashboard. You have successfully logged in.</p>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
