<?php
require_once 'database.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password_plaintext = $_POST['password']; // Storing the password as is

    // THIS IS THE VULNERABLE PART: STORING PLAINTEXT PASSWORD
    // Removed the password_hash() function entirely.

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $password_plaintext]);
        $message = "Registration successful! You can now <a href='login.php'>log in</a>.";
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') { // Check for duplicate username
            $message = "Username already exists. Please choose another one.";
        } else {
            $message = "An error occurred: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <form method="post">
            <h2>Register</h2>
            <?php if(!empty($message)): ?>
                <p class="message"><?= $message ?></p>
            <?php endif; ?>
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Register</button>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>
