<?php
session_start(); // Start the session

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Check password
        if (password_verify($password, $row['password'])) {
            // Store user data in session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];

            // Redirect to the dashboard after successful login
            header("Location: index.php");
            exit();
        } else {
            echo "<p style='color: red;'>Incorrect password!</p>";
        }
    } else {
        echo "<p style='color: red;'>No user found with that email address!</p>";
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background-color: #121212; color: #f5f5f5; padding: 50px; }
        .container { max-width: 400px; margin: 0 auto; background-color: #1e1e1e; padding: 30px; border-radius: 10px; }
        h2 { text-align: center; margin-bottom: 30px; }
        input[type="email"], input[type="password"] { 
            width: 100%; 
            padding: 12px; 
            margin: 10px 0; 
            background-color: #2e2e2e; 
            border: 1px solid #444; 
            border-radius: 5px; 
            color: #fff;
        }
        input[type="submit"] {
            width: 100%; 
            padding: 12px; 
            background-color: #3a82f6; 
            border: none; 
            color: #fff;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover { background-color: #2a6bc1; }
        .register-link { text-align: center; margin-top: 20px; }
        .register-link a { color: #ff9900; text-decoration: none; font-weight: bold; }
        .register-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Login to Your Account</h2>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
        <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>

</body>
</html>
