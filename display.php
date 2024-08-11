<?php
session_start(); // Start the session

// Check if the admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: admin.php"); // Redirect to admin login if not logged in
    exit();
}

$username = $_SESSION['username']; // Get the logged-in admin's username

// Database connection for student database
$servername = "localhost";  // Change if necessary
$username_db = "root";       // Change if necessary
$password_db = "";           // Change if necessary
$dbname_student = "student";

// Create connection for student database
$conn_student = new mysqli($servername, $username_db, $password_db, $dbname_student);

// Check connection
if ($conn_student->connect_error) {
    die("Connection failed: " . $conn_student->connect_error);
}

// Handle entry deletion
if (isset($_POST['delete'])) {
    $idToDelete = intval($_POST['id']); // Get the ID to delete
    $deleteSql = "DELETE FROM data WHERE id = ?";
    
    $stmt = $conn_student->prepare($deleteSql);
    $stmt->bind_param("i", $idToDelete);
    
    if ($stmt->execute()) {
        $deleteMessage = "Entry deleted successfully!";
    } else {
        $deleteMessage = "Error deleting entry: " . $stmt->error;
    }
    
    $stmt->close();
}

// Handle entry allowance
if (isset($_POST['allow'])) {
    $idToAllow = intval($_POST['id']); // Get the ID to allow

    // Fetch the entry data
    $fetchSql = "SELECT * FROM data WHERE id = ?";
    $fetchStmt = $conn_student->prepare($fetchSql);
    $fetchStmt->bind_param("i", $idToAllow);
    $fetchStmt->execute();
    $result = $fetchStmt->get_result();
    $entryData = $result->fetch_assoc();

    // Insert into the final database
    $dbname_final = "final"; // Final database name
    $conn_final = new mysqli($servername, $username_db, $password_db, $dbname_final);

    if ($conn_final->connect_error) {
        die("Connection to final database failed: " . $conn_final->connect_error);
    }

    // Insert the data into final database
    $insertSql = "INSERT INTO data (username, name, email, gender, age, address, tc_file, mark_list_file) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $insertStmt = $conn_final->prepare($insertSql);
    
    if (!$insertStmt) {
        die("Prepare failed: " . $conn_final->error);
    }
    
    // Bind parameters, including tc and mark_list
    $insertStmt->bind_param(
        "ssssssss", 
        $entryData['username'], 
        $entryData['name'], 
        $entryData['email'], 
        $entryData['gender'], 
        $entryData['age'], 
        $entryData['address'], 
        $entryData['tc_file'], // TC file path
        $entryData['mark_list_file'] // Mark List file path
    );
    
    // Execute the statement
    if ($insertStmt->execute()) {
        // Update the status in the student database
        $updateSql = "UPDATE data SET status = TRUE WHERE id = ?";
        $updateStmt = $conn_student->prepare($updateSql);
        
        if (!$updateStmt) {
            die("Prepare failed: " . $conn_student->error);
        }
        
        $updateStmt->bind_param("i", $idToAllow);
        
        if ($updateStmt->execute()) {
            $allowMessage = "Entry allowed successfully!";
        } else {
            $allowMessage = "Error updating status: " . $updateStmt->error;
        }
        
        $updateStmt->close();
    } else {
        $allowMessage = "Error inserting entry into final database: " . $insertStmt->error;
    }
    
    $insertStmt->close();
    $conn_final->close();
    
    $fetchStmt->close();
}

// Fetch data from the database, only where status is false
$sql = "SELECT * FROM data WHERE status = FALSE";
$result = $conn_student->query($sql);

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: admin.php"); // Redirect to admin login
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Data</title>
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
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            margin-top: 20px;
            display: block;
            width: 100%;
            text-align: center;
        }

        .logout-btn:hover {
            background-color: #CC0000; /* Darker red on hover */
        }

        .delete-btn {
            background-color: #DC3545; /* Bootstrap Danger color */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            text-align: center;
        }

        .delete-btn:hover {
            background-color: #C82333; /* Darker red on hover */
        }

        .allow-btn {
            background-color: #28A745; /* Green color for allow button */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            text-align: center;
        }

        .allow-btn:hover {
            background-color: #218838; /* Darker green on hover */
        }

        .success {
            color: green;
            text-align: center;
            margin-top: 20px;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 20px;
        }
        .back-btn {
    background-color: #6c757d; /* Gray color for back button */
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
    border-radius: 5px;
    margin-top: 10px;
    width: 100%; /* Make it the same width as the logout button */
}

.back-btn:hover {
    background-color: #5a6268; /* Darker gray on hover */
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome Admin, <?php echo htmlspecialchars($username); ?>!</h1>

        <?php if (isset($allowMessage)): ?>
            <div class="<?php echo strpos($allowMessage, 'Error') === false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($allowMessage); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($deleteMessage)): ?>
            <div class="<?php echo strpos($deleteMessage, 'Error') === false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($deleteMessage); ?>
            </div>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Address</th>
                    <th>Transfer Certificate (TC)</th>
                    <th>Mark List</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                        <td><?php echo htmlspecialchars($row['age']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td>
                            <a href="<?php echo htmlspecialchars($row['tc_file']); ?>" target="_blank">View TC</a>
                        </td>
                        <td>
                            <a href="<?php echo htmlspecialchars($row['mark_list_file']); ?>" target="_blank">View Mark List</a>
                        </td>
                        <td>
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <input type="submit" name="allow" value="Allow" class="allow-btn" onclick="return confirm('Are you sure you want to allow this entry?');">
                            </form>
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <input type="submit" name="delete" value="Delete" class="delete-btn" onclick="return confirm('Are you sure you want to delete this entry?');">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No data found.</p>
        <?php endif; ?>

        <form method="POST" action="">
    <input type="submit" name="logout" value="Logout" class="logout-btn">
    <button type="button" class="back-btn" onclick="window.location.href='admin_dashboard.php';">Back</button>
</form>
        
    </div>
</body>
</html>

<?php
$conn_student->close(); // Close the database connection for student
?>
