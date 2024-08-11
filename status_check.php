<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: user.php"); // Redirect to login if not logged in
    exit();
}

// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "student"; // Database name for status check

// Create connection to the student database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch entries for the logged-in user
$current_user = $_SESSION['username'];
$sql = "SELECT * FROM data WHERE username = '$current_user'"; // Fetch entries for the logged-in user
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Check</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        .status-admitted {
            color: green;
            font-weight: bold;
        }

        .status-not-admitted {
            color: red;
            font-weight: bold;
        }

        .logout-btn {
            background-color: #FF4D4D; /* Red color for logout button */
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            margin: 10px 0;
            float: right;
        }

        .logout-btn:hover {
            background-color: #CC0000; /* Darker red on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Status Check for <?php echo htmlspecialchars($current_user); ?></h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Address</th>
                <th>Status</th>
            </tr>
            <?php
            // Check if there are any results
            if ($result->num_rows > 0) {
                // Output data for each row
                while($row = $result->fetch_assoc()) {
                    $status = $row['status'] ? "Admitted" : "Not Admitted"; // Check status
                    $status_class = $row['status'] ? "status-admitted" : "status-not-admitted"; // Assign class for styling

                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['gender']}</td>
                            <td>{$row['age']}</td>
                            <td>{$row['address']}</td>
                            <td class='$status_class'>{$status}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No entries found.</td></tr>";
            }
            ?>
        </table>
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

    $conn->close(); // Close the database connection
    ?>
</body>
</html>
