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

// Get book ID from query string
$book_id = isset($_GET['book_id']) ? (int)$_GET['book_id'] : 0;

// Fetch book details
if ($book_id > 0) {
    $sql = "SELECT * FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if ($book) {
        // Display the book details
        echo "<h1>Buy Book: " . htmlspecialchars($book['title']) . "</h1>";
        echo "<p><strong>Author:</strong> " . htmlspecialchars($book['author']) . "</p>";
        echo "<p><strong>Description:</strong> " . htmlspecialchars($book['description']) . "</p>";
        echo "<p><strong>Price:</strong> $" . htmlspecialchars(number_format($book['buying_price'], 2)) . "</p>";

        // Purchase form
        echo "<form action='process_purchase.php' method='POST'>
                <input type='hidden' name='book_id' value='" . $book['id'] . "'>
                <input type='submit' value='Confirm Purchase'>
              </form>";
    } else {
        echo "<p>Book not found.</p>";
    }
} else {
    echo "<p>Invalid book.</p>";
}

$conn->close();
?>
