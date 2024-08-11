<?php
session_start(); // Start the session

// Check if the admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin.php"); // Redirect to login if not logged in
    exit();
}

$username = $_SESSION['username']; // Get the logged-in admin's username
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

        .btn {
            display: inline-block;
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            margin: 10px 0;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .logout-btn {
            background-color: #FF4D4D; /* Red color for logout button */
        }

        .logout-btn:hover {
            background-color: #CC0000; /* Darker red on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <a href="display.php" class="btn">New Entries</a>
        <a href="allowed_entries.php" class="btn">Allowed Entries</a>
        <form method="POST" action="">
            <input type="submit" name="logout" value="Logout" class="btn logout-btn">
        </form>
    </div>

    <?php
    // Handle logout
    if (isset($_POST['logout'])) {
        session_destroy(); // Destroy the session
        header("Location: admin.php"); // Redirect to admin login
        exit();
    }
    ?>
</body>
</html>
