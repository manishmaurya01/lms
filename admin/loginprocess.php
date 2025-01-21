<?php
// Start session at the top
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL query to check if admin exists (use 'username' instead of 'email')
    $sql = "SELECT * FROM admin WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        // Output the error message if the query fails
        die('Query preparation failed: ' . $conn->error);
    }

    // Bind the parameters
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Admin login successful, start session
        $_SESSION['admin_username'] = $username;
        header("Location: admin_dashboard.php"); // Redirect to the dashboard
        exit(); // Ensure no further code is executed after redirect
    } else {
        // If login fails, show an error message
        $error = "Invalid username or password.";
        $_SESSION['error'] = $error; // Store error message in session for display in the login form
        header("Location: login_form.php"); // Redirect back to the login form
        exit();
    }
}

$conn->close();
?>
