<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rental_id'])) {
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

    $rental_id = $_POST['rental_id'];

    // Delete the rental
    $sql = "DELETE FROM rentals WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $rental_id);
    if ($stmt->execute()) {
        echo "<script>alert('Rental removed successfully!');</script>";
    } else {
        echo "<script>alert('Error removing rental.');</script>";
    }

    $stmt->close();
    $conn->close();

    // Redirect to profile page
    echo "<script>window.location.href = 'profilepage.php';</script>";
}
?>
