<?php
session_start();

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

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("<p>You must be logged in to make a purchase.</p>");
}

$user_id = $_SESSION['user_id'];

// Process the purchase
if (isset($_POST['book_id'])) {
    $book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);
    if (!$book_id) {
        die("<p>Invalid book ID.</p>");
    }

    // Fetch the book details
    $sql = "SELECT * FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if ($book) {
        $purchase_price = $book['buying_price'];

        // Set payment status (default to 'pending')
        $payment_status = 'pending';

        // Insert the purchase record into the database
        $sql = "INSERT INTO purchases (user_id, book_id, purchase_price, purchase_date, payment_status) VALUES (?, ?, ?, NOW(), ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iids", $user_id, $book_id, $purchase_price, $payment_status);

        if ($stmt->execute()) {
            echo "<p>Thank you for your purchase of '" . htmlspecialchars($book['title']) . "'!</p>";
            echo "<p>Your payment status is currently: <strong>" . htmlspecialchars($payment_status) . "</strong>.</p>";
            echo "<a href='user_dashboard.php'>Go to Dashboard</a>";
        } else {
            echo "<p>Error processing your purchase. Please try again.</p>";
        }
    } else {
        echo "<p>Book not found.</p>";
    }
} else {
    echo "<p>No book selected.</p>";
}

$conn->close();
?>
