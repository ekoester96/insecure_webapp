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
    <title>Insecure Login Page</title>
    <style>
        /* Basic styling for the form and error messages */
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f4f4f4; }
        .login-container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; }
        input { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; }
        button { width: 100%; padding: 0.75rem; border: none; background-color: #007bff; color: white; border-radius: 4px; cursor: pointer; }
        .error { color: red; font-size: 0.9em; margin-top: 0.5rem; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <form id="loginForm" action="login.php" method="post" novalidate>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div id="client-error" class="error"></div>
        
        <?php if (!empty($error_message)): ?>
            <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <button type="submit">Login</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('loginForm');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const errorContainer = document.getElementById('client-error');

        form.addEventListener('submit', function(event) {
            errorContainer.textContent = ''; // Clear previous errors

            const username = usernameInput.value;
            const password = passwordInput.value;
            let errorMessage = '';

            // Regex to find forbidden special characters: ' # ( ) -
            const specialCharRegex = /[ '`#()\-;/*=<>|&\\$!{}]/;

            // 1. Check for empty fields
            if (username.trim() === '' || password.trim() === '') {
                errorMessage = 'Username and password are required.';
            } 
            // 2. Check for special characters in username
            else if (specialCharRegex.test(username)) {
                errorMessage = "Invalid Username";
            } 
            // 3. Check for special characters in password
            else if (specialCharRegex.test(password)) {
                errorMessage = "Password cannot contain special characters";
            }

            // If there is an error, prevent form submission and display the message
            if (errorMessage) {
                event.preventDefault(); // Stop the form submission
                errorContainer.textContent = errorMessage;
            }
        });
    });
</script>

</body>
</html>
