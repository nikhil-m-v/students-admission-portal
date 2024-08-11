<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: user.php"); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
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
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            margin-bottom: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .logout-btn {
            background-color: #FF4D4D; /* Red color for logout button */
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
        <button onclick="window.location.href='welcome.php';">Create New Entry</button>
        <button onclick="window.location.href='status_check.php';">Status Check</button>
        <form method="POST" action="">
            <input type="submit" name="logout" value="Logout" class="logout-btn">
        </form>
    </div>

    <?php
    // Handle logout
    if (isset($_POST['logout'])) {
        session_destroy(); // Destroy the session
        header("Location: user.php"); // Redirect to user login
        exit();
    }
    ?>
</body>
</html>
