<?php
session_start(); // Start the session

// Check if the admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin.php"); // Redirect to login if not logged in
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname_final = "final";

// Create connection to the final database
$conn_final = new mysqli($servername, $username, $password, $dbname_final);

// Check connection
if ($conn_final->connect_error) {
    die("Connection failed: " . $conn_final->connect_error);
}

// Fetch all allowed entries from the final database
$sql = "SELECT * FROM data";
$result = $conn_final->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allowed Entries</title>
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
        .back-btn {
    background-color: #6c757d; /* Gray color for back button */
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
    border-radius: 5px;
    margin-top: 10px;
    display: block; /* Makes the button take up full width */
}

.back-btn:hover {
    background-color: #5a6268; /* Darker gray on hover */
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Allowed Entries</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Address</th>
                <th>TC File</th>
                <th>Mark List File</th>
            </tr>
            <?php
            // Check if there are any results
            if ($result->num_rows > 0) {
                // Output data for each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['gender']}</td>
                            <td>{$row['age']}</td>
                            <td>{$row['address']}</td>
                            <td><a href='{$row['tc_file']}' target='_blank'>View TC</a></td>
                            <td><a href='{$row['mark_list_file']}' target='_blank'>View Mark List</a></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No entries found.</td></tr>";
            }
            ?>
        </table>
        <form method="POST" action="">
    <input type="submit" name="logout" value="Logout" class="logout-btn">
    <button type="button" class="back-btn" onclick="window.location.href='admin_dashboard.php';">Back</button>
</form>

    </div>

    <?php
    // Handle logout
    if (isset($_POST['logout'])) {
        session_destroy(); // Destroy the session
        header("Location: admin.php"); // Redirect to admin login
        exit();
    }

    $conn_final->close(); // Close the database connection for final
    ?>
</body>
</html>
