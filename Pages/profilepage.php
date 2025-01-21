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

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user_id from session
$user_id = $_SESSION['user_id'];

// Fetch user details
$sql_user = "SELECT name, email,phone,address FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_result = $stmt_user->get_result();
$user_data = $user_result->fetch_assoc();

// Fetch rented books with payment status
$sql_rentals = "SELECT rentals.id AS rental_id, books.title AS book_title, rentals.rent_duration, rentals.rental_date, rentals.rent_price, rentals.payment_status 
                FROM rentals 
                JOIN books ON rentals.book_id = books.id 
                WHERE rentals.user_id = ?";
$stmt_rentals = $conn->prepare($sql_rentals);
$stmt_rentals->bind_param("i", $user_id);
$stmt_rentals->execute();
$rentals_result = $stmt_rentals->get_result();

// Fetch purchased books with payment status
$sql_purchases = "SELECT books.title AS book_title, bookings.book_price, bookings.booking_date, bookings.payment_status 
                  FROM bookings 
                  JOIN books ON bookings.book_id = books.id 
                  WHERE bookings.user_id = ?";
$stmt_purchases = $conn->prepare($sql_purchases);
$stmt_purchases->bind_param("i", $user_id);
$stmt_purchases->execute();
$purchases_result = $stmt_purchases->get_result();

$current_date = date('Y-m-d'); // Current date to calculate remaining days
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../css/profile_page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Additional styling for tabs */
        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        .tab {
            padding: 15px 30px;
            cursor: pointer;
            background-color: #333;
            color: #fff;
            font-size: 1.1rem;
            border: none;
            margin: 0 10px;
            transition: background-color 0.3s ease;
        }
        .tab:hover {
            background-color: #3498db;
        }
        .tab-content {
            display: none;
        }
        .active-tab {
            background-color: #3498db;
        }
        .active-content {
            display: block;
        }
        .overdue-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <header>
        <nav>
            <div class="logo">Library Portal</div>
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="search_books.php"><i class="fas fa-book"></i> Search Books</a></li>
                <li><a href="profilepage.php"><i class="fas fa-user"></i> Profile</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php else: ?>
                    <li><a href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                <?php endif; ?>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h1>User Profile</h1>

        <!-- Tab Navigation -->
        <div class="tabs">
            <button class="tab active-tab" onclick="showTab('profile')">Profile</button>
            <button class="tab" onclick="showTab('rentals')">Rented Books</button>
            <button class="tab" onclick="showTab('purchases')">Purchased Books</button>
        </div>

      <!-- Profile Section -->
<div id="profile" class="tab-content active-content">
    <?php if ($user_data): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Name</td>
                    <td><?php echo htmlspecialchars($user_data['name']); ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?php echo htmlspecialchars($user_data['email']); ?></td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td><?php echo htmlspecialchars($user_data['phone']); ?></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td><?php echo htmlspecialchars($user_data['address']); ?></td>
                </tr>
            </tbody>
        </table>
        
        <!-- Edit Button -->
        <a href="update_profile.php" class="edit-btn">
            <button>Edit Profile</button>
        </a>

    <?php else: ?>
        <p>No user details found. Please contact support.</p>
    <?php endif; ?>
</div>


        <!-- Rented Books Section -->
        <div id="rentals" class="tab-content">
            <h2>Rented Books</h2>
            <?php if ($rentals_result->num_rows > 0): ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Book Title</th>
                            <th>Rent Duration (Days)</th>
                            <th>Rental Date</th>
                            <th>Rent Price</th>
                            <th>Payment Status</th>
                            <th>Actions</th>
                            <th>Remaining Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($rental = $rentals_result->fetch_assoc()): ?>
                            <?php
                                $rental_end_date = date('Y-m-d', strtotime($rental['rental_date'] . ' + ' . $rental['rent_duration'] . ' days'));
                                $remaining_days = (strtotime($rental_end_date) - strtotime($current_date)) / (60 * 60 * 24);
                                $remaining_days = max(0, $remaining_days);

                                $overdue_message = ($remaining_days <= 0) ? 'Your rental period has ended. Please return the book.' : '';
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($rental['book_title']); ?></td>
                                <td><?php echo htmlspecialchars($rental['rent_duration']); ?></td>
                                <td><?php echo htmlspecialchars($rental['rental_date']); ?></td>
                                <td>₹<?php echo number_format($rental['rent_price'], 2); ?></td>
                                <td><?php echo htmlspecialchars($rental['payment_status']); ?></td>
                                <td>
                                    <form action="remove_rental.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="rental_id" value="<?php echo $rental['rental_id']; ?>">
                                        <button type="submit">Return</button>
                                    </form>
                                </td>
                                <td>
                                    <?php if ($overdue_message): ?>
                                        <strong class="overdue-message"><?php echo $overdue_message; ?></strong>
                                    <?php else: ?>
                                        <strong>Remaining Days: <?php echo $remaining_days; ?></strong>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No rented books found.</p>
            <?php endif; ?>
        </div>

        <!-- Purchased Books Section -->
        <div id="purchases" class="tab-content">
            <h2>Purchased Books</h2>
            <?php if ($purchases_result->num_rows > 0): ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Book Title</th>
                            <th>Price</th>
                            <th>Purchase Date</th>
                            <th>Payment Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($purchase = $purchases_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($purchase['book_title']); ?></td>
                                <td>₹<?php echo number_format($purchase['book_price'], 2); ?></td>
                                <td><?php echo htmlspecialchars($purchase['booking_date']); ?></td>
                                <td><?php echo htmlspecialchars($purchase['payment_status']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No purchased books found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function showTab(tabId) {
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.remove('active-content'));

            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => tab.classList.remove('active-tab'));

            document.getElementById(tabId).classList.add('active-content');
            event.target.classList.add('active-tab');
        }
    </script>
</body>
</html>

<?php
$stmt_user->close();
$stmt_rentals->close();
$stmt_purchases->close();
$conn->close();
?>
