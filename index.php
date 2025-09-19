<?php
// This script will act as the entry point on your LAMP server.

// Retrieve the user agent from the current request
$user_agent = $_SERVER['HTTP_USER_AGENT'];

// Sanitize the user agent string to prevent command injection vulnerabilities
$escaped_user_agent = escapeshellarg($user_agent);

// --- MODIFIED LINE ---
// Reverted to the system's default Python executable.
// The required packages have been installed globally.
$python_executable = '/usr/bin/python3';
$prediction_script = 'predict_spam.py';

// Construct the command to execute the Python script
$command = $python_executable . ' ' . $prediction_script . ' ' . $escaped_user_agent;

// Execute the command and capture the output
$output = shell_exec($command);

// Decode the JSON output from the Python script
$result = json_decode($output, true);

// --- Decision Logic ---
// You can set a custom threshold for the spam probability.
$spam_threshold = 0.75;

if (isset($result['prediction']) && $result['prediction'] == 1 && $result['spam_probability'] > $spam_threshold) {
    // If the prediction is spam and exceeds the probability threshold, block the request.
    header('HTTP/1.1 403 Forbidden');
    // You could also log the attempt here or redirect a warning page.
    echo "<h1>403 Forbidden</h1>";
    echo "<p>Your connection has been flagged as suspicious and has been blocked.</p>";
    // Log the blocked connection attempt for analysis
    error_log("Blocked spam connection from User-Agent: " . $user_agent . " with probability: " . $result['spam_probability']);
    exit();
}

// --- Regular Page Content ---
// If the connection is not flagged as spam, the rest of your page content will be displayed.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Server</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 2em; }
    </style>
</head>
<body>
    <h1>Welcome!</h1>
    <p>Your connection appears to be legitimate.</p>
    <p>Your User Agent: <?php echo htmlspecialchars($user_agent, ENT_QUOTES, 'UTF-8'); ?></p>
</body>
</html>

