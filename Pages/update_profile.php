<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch current user details
$sql_user = "SELECT name, email, phone, address FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_result = $stmt_user->get_result();
$user_data = $user_result->fetch_assoc();

// Handle form submission to update profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated values from the form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Update the user's profile in the database
    $sql_update = "UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssi", $name, $email, $phone, $address, $user_id);
    $stmt_update->execute();

    // Redirect to profile page after update
    header("Location: profilepage.php");
    exit();
}

$stmt_user->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #1e1e2f;
            color: #f0f0f0;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #3498db;
        }

        form {
            max-width: 500px;
            margin: 40px auto;
            background-color: #2c2c3c;
            border-radius: 10px;
            padding: 20px 30px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.4);
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            background-color: #3c3c4c;
            color: #f0f0f0;
        }

        input:focus {
            outline: none;
            border: 2px solid #3498db;
        }

        button {
            display: block;
            width: 100%;
            padding: 12px 0;
            background-color: #3498db;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #1b79d1;
        }

        @media screen and (max-width: 600px) {
            form {
                padding: 15px 20px;
            }

            button {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <h1>Update Profile</h1>

    <!-- Profile Update Form -->
    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>

        <label for="phone">Phone:</label>
        <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($user_data['phone']); ?>" required>

        <label for="address">Address:</label>
        <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($user_data['address']); ?>" required>

        <button type="submit">Save Changes</button>
    </form>

</body>
</html>
