<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: user.php"); // Redirect to login page if not logged in
    exit();
}

$username = $_SESSION['username']; // Get the logged-in user's name

// Database connection
$servername = "localhost";  // Change if necessary
$username_db = "root";       // Change if necessary
$password_db = "";           // Change if necessary
$dbname = "student";

// Create connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $address = $_POST['address'];

    // Handle file uploads
    $uploadDir = 'uploads/';
    $tcFile = $uploadDir . basename($_FILES['tc']['name']);
    $markListFile = $uploadDir . basename($_FILES['markList']['name']);

    $uploadOk = true;

    // Check if uploads directory exists, if not, create it
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Validate and move TC file
    if ($_FILES['tc']['size'] > 0) {
        $tcFileType = strtolower(pathinfo($tcFile, PATHINFO_EXTENSION));
        if (in_array($tcFileType, ['jpg', 'jpeg', 'png', 'pdf'])) {
            if (!move_uploaded_file($_FILES['tc']['tmp_name'], $tcFile)) {
                echo "<p class='error'>Failed to upload TC.</p>";
                $uploadOk = false;
            }
        } else {
            echo "<p class='error'>Only JPG, JPEG, PNG, and PDF files are allowed for TC.</p>";
            $uploadOk = false;
        }
    }

    // Validate and move Mark List file
    if ($_FILES['markList']['size'] > 0) {
        $markListFileType = strtolower(pathinfo($markListFile, PATHINFO_EXTENSION));
        if (in_array($markListFileType, ['jpg', 'jpeg', 'png', 'pdf'])) {
            if (!move_uploaded_file($_FILES['markList']['tmp_name'], $markListFile)) {
                echo "<p class='error'>Failed to upload Mark List.</p>";
                $uploadOk = false;
            }
        } else {
            echo "<p class='error'>Only JPG, JPEG, PNG, and PDF files are allowed for Mark List.</p>";
            $uploadOk = false;
        }
    }

    // Insert data into the database if uploads are successful
    if ($uploadOk) {
        $stmt = $conn->prepare("INSERT INTO data (username, name, email, gender, age, address, tc_file, mark_list_file) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $username, $name, $email, $gender, $age, $address, $tcFile, $markListFile);

        if ($stmt->execute()) {
            echo "<p class='success'>Data inserted successfully!</p>";
        } else {
            echo "<p class='error'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }
}

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: user.php"); // Redirect to login page
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }

        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            margin-top: 20px;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .success {
            color: green;
            margin-top: 20px;
        }

        .error {
            color: red;
            margin-top: 20px;
        }

        label {
            display: block;
            text-align: left;
            margin: 10px 0 5px 0;
            font-weight: bold;
        }

        .logout-btn {
            background-color: #FF4D4D; /* Red color for logout button */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            margin-top: 10px;
            width: 100%;
        }

        .logout-btn:hover {
            background-color: #CC0000; /* Darker red on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
            <form method="POST" action="" enctype="multipart/form-data">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Name" required><br>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Email" required><br>

                <label for="gender">Gender</label>
                <select name="gender" id="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select><br>

                <label for="age">Age</label>
                <input type="number" name="age" id="age" placeholder="Age" required><br>

                <label for="address">Address</label>
                <textarea name="address" id="address" placeholder="Address" required></textarea><br>

                <label for="tc">Upload Transfer Certificate (TC)</label>
                <input type="file" name="tc" id="tc" required><br>

                <label for="markList">Upload Mark List</label>
                <input type="file" name="markList" id="markList" required><br>

                <input type="submit" value="Submit">
            </form>
            <form method="POST" action="">
                <input type="submit" name="logout" value="Logout" class="logout-btn">
            </form>
        </div>
    </div>
</body>
</html>
