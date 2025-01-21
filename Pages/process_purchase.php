<?php
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

// Process the purchase
if (isset($_POST['book_id'])) {
    $book_id = (int)$_POST['book_id'];

    // Fetch the book details
    $sql = "SELECT * FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if ($book) {
        // Assuming the user is logged in and we have a user ID (For example: user_id = 1)
        $user_id = 1; // Replace with actual logged-in user's ID
        $purchase_price = $book['buying_price'];

        // Insert the purchase record into the database
        $sql = "INSERT INTO purchases (user_id, book_id, purchase_price, purchase_date) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iid", $user_id, $book_id, $purchase_price);

        if ($stmt->execute()) {
            echo "<p>Thank you for your purchase of '" . htmlspecialchars($book['title']) . "'!</p>";
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
