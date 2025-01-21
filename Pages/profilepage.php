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

// Fetch user details
$sql_user = "SELECT name, email FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_result = $stmt_user->get_result();
$user_data = $user_result->fetch_assoc();

// Fetch rented books
$sql_rentals = "SELECT rentals.id AS rental_id, books.title AS book_title, rentals.rent_duration, rentals.rental_date, rentals.rent_price FROM rentals JOIN books ON rentals.book_id = books.id WHERE rentals.user_id = ?";
$stmt_rentals = $conn->prepare($sql_rentals);
$stmt_rentals->bind_param("i", $user_id);
$stmt_rentals->execute();
$rentals_result = $stmt_rentals->get_result();

// Fetch purchased books
$sql_purchases = "SELECT books.title AS book_title, bookings.book_price, bookings.booking_date FROM bookings JOIN books ON bookings.book_id = books.id WHERE bookings.user_id = ?";
$stmt_purchases = $conn->prepare($sql_purchases);
$stmt_purchases->bind_param("i", $user_id);
$stmt_purchases->execute();
$purchases_result = $stmt_purchases->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../css/profile.css"> <!-- Assuming a separate CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

  <!-- Navigation Bar -->
  <header>
        <nav>
            <div class="logo">Library Portal</div>
            <ul>
                <li><a href="#home"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="search_books.php"><i class="fas fa-book"></i> Search Books</a></li>
                <li><a href="profilepage.php"><i class="fas fa-user"></i> Profile</a></li>
                <?php

                if (isset($_SESSION['user_id'])): ?>
                    <!-- User is logged in, show Logout link -->
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php else: ?>
                    <!-- User is not logged in, show Register link -->
                    <li><a href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                <?php endif; ?>
                <li><a href="#contact"><i class="fas fa-envelope"></i> Contact</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h1>User Profile</h1>
        
        <!-- User Info Section -->
        <div class="user-info">
            <h3>Name: <?php echo htmlspecialchars($user_data['name']); ?></h3>
            <h3>Email: <?php echo htmlspecialchars($user_data['email']); ?></h3>
            <!-- If needed, you can also display the password (but ensure it's handled securely) -->
        </div>

        <h2>Rented Books</h2>
<?php if ($rentals_result->num_rows > 0): ?>
    <table border="1">
        <thead>
            <tr>
                <th>Book Title</th>
                <th>Rent Duration (Days)</th>
                <th>Rental Date</th>
                <th>Rent Price</th>  <!-- Added Rent Price column -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($rental = $rentals_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($rental['book_title']); ?></td>
                    <td><?php echo htmlspecialchars($rental['rent_duration']); ?></td>
                    <td><?php echo htmlspecialchars($rental['rental_date']); ?></td>
                    <td>$<?php echo number_format($rental['rent_price'], 2); ?></td>  <!-- Display Rent Price -->
                    <td>
                        <!-- Buttons to remove or extend rent -->
                        <form action="remove_rental.php" method="POST" style="display:inline;">
                            <input type="hidden" name="rental_id" value="<?php echo $rental['rental_id']; ?>">
                            <button type="submit">Remove Rental</button>
                        </form>
                        <form action="extend_rental.php" method="POST" style="display:inline;">
                            <input type="hidden" name="rental_id" value="<?php echo $rental['rental_id']; ?>">
                            <input type="number" name="additional_days" min="1" value="1" required>
                            <button type="submit">Extend Rent</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No rented books found.</p>
<?php endif; ?>


        <!-- Purchased Books Section -->
        <h2>Purchased Books</h2>
        <?php if ($purchases_result->num_rows > 0): ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Price</th>
                        <th>Purchase Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($purchase = $purchases_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($purchase['book_title']); ?></td>
                            <td>$<?php echo number_format($purchase['book_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($purchase['booking_date']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No purchased books found.</p>
        <?php endif; ?>

    </div>
</body>
</html>

<?php
// Close the prepared statements and connection
$stmt_user->close();
$stmt_rentals->close();
$stmt_purchases->close();
$conn->close();
?>
