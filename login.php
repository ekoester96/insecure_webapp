<?php
session_start();
require_once 'database.php';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// WARNING: THIS CODE IS DELIBERATELY VULNERABLE TO SQL INJECTION.
// DO NOT USE IN A REAL APPLICATION.
  $username = $_POST['username'];
  $password_plaintext = $_POST['password'];

// Build the SQL query by directly inserting user input. This is the vulnerability.
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password_plaintext'";

// Execute the query
  $stmt = $pdo->query($sql);
  $user = $stmt->fetch();

  if ($user) {
     $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    header("Location: dashboard.php");
    exit;
  } else {
    $error_message = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <form method="post">
            <h2>Login</h2>
            <?php if(!empty($error_message)): ?>
                <p class="error"><?= $error_message ?></p>
            <?php endif; ?>
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </form>
    </div>
</body>
</html>
