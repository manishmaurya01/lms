<?php
// Start the session to get user data (assuming session is started when the user logs in)
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

// Assuming user_id is stored in session after login
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'], $_POST['book_price'], $user_id)) {
    // Get the POST data
    $book_id = $_POST['book_id'];
    $book_price = $_POST['book_price'];

    // Check if the user has already rented this book
    $sql_check_rental = "SELECT * FROM rentals WHERE user_id = ? AND book_id = ?";
    $stmt_check_rental = $conn->prepare($sql_check_rental);
    $stmt_check_rental->bind_param("ii", $user_id, $book_id);
    $stmt_check_rental->execute();
    $result_rental = $stmt_check_rental->get_result();

    if ($result_rental->num_rows > 0) {
        // User has rented the book, so they cannot purchase it
        echo "<script>alert('You have already rented this book. You cannot purchase it.');</script>";
        echo "<script>window.location.href = 'search_books.php';</script>";
    } else {
        // Check if the user has already purchased this book
        $sql_check_purchase = "SELECT * FROM bookings WHERE user_id = ? AND book_id = ?";
        $stmt_check_purchase = $conn->prepare($sql_check_purchase);
        $stmt_check_purchase->bind_param("ii", $user_id, $book_id);
        $stmt_check_purchase->execute();
        $result_purchase = $stmt_check_purchase->get_result();

        if ($result_purchase->num_rows > 0) {
            echo "<script>alert('You have already purchased this book.');</script>";
            echo "<script>window.location.href = 'search_books.php';</script>";
        } else {
            // Retrieve user details from users table
            $sql_user = "SELECT name, email FROM users WHERE id = ?";
            $stmt_user = $conn->prepare($sql_user);
            $stmt_user->bind_param("i", $user_id);
            $stmt_user->execute();
            $user_result = $stmt_user->get_result();
            $user_data = $user_result->fetch_assoc();

            // Check if user data is retrieved
            if ($user_data) {
                $user_name = $user_data['name'];
                $user_email = $user_data['email'];

                // Insert the booking into the bookings table
                $stmt = $conn->prepare("INSERT INTO bookings (user_id, book_id, book_price, booking_date) VALUES (?, ?, ?, NOW())");
                $stmt->bind_param("iid", $user_id, $book_id, $book_price);

                // Execute the query and check for success
                if ($stmt->execute()) {
                    echo "<script>alert('Book purchase successful!');</script>";
                    echo "<script>window.location.href = 'search_books.php';</script>";
                } else {
                    echo "<script>alert('Error during booking. Please try again.');</script>";
                }

                // Close the prepared statement for user and booking
                $stmt_user->close();
                $stmt->close();
            } else {
                echo "<script>alert('User not found. Please log in again.');</script>";
            }
        }
        $stmt_check_purchase->close();
    }

    // Close the prepared statement for the rental check
    $stmt_check_rental->close();
}
$conn->close();
?>
