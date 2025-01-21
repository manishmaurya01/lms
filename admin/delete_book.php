<?php
// Start the session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get book ID from the URL
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    // Delete book query
    $query = "DELETE FROM books WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $book_id);

    if ($stmt->execute()) {
        header("Location: view_books.php");
        exit();
    } else {
        echo "Error deleting book.";
    }
} else {
    echo "No book ID provided.";
}

$stmt->close();
$conn->close();
?>
