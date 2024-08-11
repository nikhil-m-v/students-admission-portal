<?php
session_start(); // Start the session

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: user_dashboard.php"); // Redirect to user dashboard if already logged in
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username and password are correct for any user
    if (in_array($username, ['user1', 'user2', 'user3', 'user4', 'user5']) && $password === $username) {
        $_SESSION['username'] = $username; // Set session variable
        header("Location: user_dashboard.php"); // Redirect to the user dashboard
        exit();
    } else {
        $error_message = "Incorrect username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            text-align: center;
        }

        .back-btn {
            background-color: #6c757d; /* Gray color for back button */
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            margin-top: 10px;
        }

        .back-btn:hover {
            background-color: #5a6268; /* Darker gray on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Login</h2>
        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
            <button type="button" class="back-btn" onclick="window.location.href='index.html';">Back</button>
        </form>
    </div>
</body>
</html>
