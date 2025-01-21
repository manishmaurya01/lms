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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'], $_POST['rent_price'], $_POST['rent_duration'], $user_id)) {
    // Get the POST data
    $book_id = intval($_POST['book_id']);
    $rent_duration = intval($_POST['rent_duration']); // Assuming rent duration is in days
    $rent_price = $rent_duration * floatval($_POST['rent_price']);

    // Check if the user has already rented or purchased this book
    $sql_check_rental = "SELECT * FROM rentals WHERE user_id = ? AND book_id = ?";
    $stmt_check_rental = $conn->prepare($sql_check_rental);
    $stmt_check_rental->bind_param("ii", $user_id, $book_id);
    $stmt_check_rental->execute();
    $result_rental = $stmt_check_rental->get_result();

    $sql_check_purchase = "SELECT * FROM bookings WHERE user_id = ? AND book_id = ?";
    $stmt_check_purchase = $conn->prepare($sql_check_purchase);
    $stmt_check_purchase->bind_param("ii", $user_id, $book_id);
    $stmt_check_purchase->execute();
    $result_purchase = $stmt_check_purchase->get_result();

    // If a rental or purchase is found, prevent further rental
    if ($result_rental->num_rows > 0) {
        echo "<script>alert('You have already rented this book.');</script>";
        echo "<script>window.location.href = 'search_books.php';</script>";
    } elseif ($result_purchase->num_rows > 0) {
        echo "<script>alert('You have already purchased this book. Rental is not allowed.');</script>";
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
            // Insert the rental into the rentals table
            $stmt = $conn->prepare("INSERT INTO rentals (user_id, book_id, rent_price, rent_duration, rental_date) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("iidi", $user_id, $book_id, $rent_price, $rent_duration);

            // Execute the query and check for success
            if ($stmt->execute()) {
                echo "<script>alert('Book rented successfully!');</script>";
                echo "<script>window.location.href = 'search_books.php';</script>";
            } else {
                echo "<script>alert('Error during rental. Please try again.');</script>";
            }

            // Close the prepared statement for user and rental
            $stmt_user->close();
            $stmt->close();
        } else {
            echo "<script>alert('User not found. Please log in again.');</script>";
        }
    }

    // Close the prepared statements
    $stmt_check_rental->close();
    $stmt_check_purchase->close();
}
$conn->close();
?>
