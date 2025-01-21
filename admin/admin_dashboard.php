<?php
// Start the session to access session variables
session_start();

// Database connection details
$servername = "localhost";  // or your server name (e.g., localhost)
$username = "root";         // your database username (default is usually 'root' for XAMPP)
$password = "";             // your database password (default is empty for XAMPP)
$dbname = "library_db";     // your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

// Get the admin's username from the session
$admin_username = $_SESSION['admin_username'];

// Query to get the total number of users
$query_users = "SELECT COUNT(*) AS total_users FROM users";
$result_users = mysqli_query($conn, $query_users);

// Fetch the result and store the total number of users
$row_users = mysqli_fetch_assoc($result_users);
$total_users = $row_users['total_users'];

// Query to get the total number of books
$query_books = "SELECT COUNT(*) AS total_books FROM books";
$result_books = mysqli_query($conn, $query_books);

// Fetch the result and store the total number of books
$row_books = mysqli_fetch_assoc($result_books);
$total_books = $row_books['total_books'];

// Query to get all bookings
$query_bookings = "SELECT * FROM bookings";
$result_bookings = mysqli_query($conn, $query_bookings);

// Check if the form for updating payment status was submitted
if (isset($_GET['mark_paid'])) {
    $booking_id = $_GET['mark_paid'];
    // Update the payment status to 'Paid' for the selected booking
    $update_query = "UPDATE bookings SET payment_status = 'Paid' WHERE id = $booking_id";
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Payment status updated to Paid.'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating payment status.');</script>";
    }
}

// Check if the form for removing a booking was submitted
if (isset($_GET['remove_booking'])) {
    $booking_id = $_GET['remove_booking'];
    // Delete the selected booking
    $delete_query = "DELETE FROM bookings WHERE id = $booking_id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Booking removed successfully.'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error removing booking.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    /* Global Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Roboto', sans-serif;
    }

    body {
        background-color: #121212;
        color: #e0e0e0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 50px;
    }

    .container {
        width: 100%;
        max-width: 1400px;
    }

    /* Header */
    .header {
        background: linear-gradient(135deg, #ff3d00, #6200ea);
        color: white;
        padding: 70px 40px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 6px 30px rgba(0, 0, 0, 0.3);
        margin-bottom: 40px;
    }

    .header h1 {
        font-size: 50px;
        font-weight: bold;
        letter-spacing: 1.5px;
    }

    /* Section for Welcome, Total Users, and Total Books */
    .section {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 30px;
        margin-bottom: 40px;
    }

    /* Section Card */
    .card {
        background: #1e1e1e;
        border-radius: 15px;
        padding: 40px;
        width: 48%;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
    }

    .card h3 {
        font-size: 28px;
        color: #6200ea;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .card p {
        font-size: 22px;
        color: #bbb;
        margin-bottom: 30px;
    }

    .total-users, .total-books {
        font-size: 32px;
        font-weight: 700;
        color: #ffffff;
    }

    .card a {
        display: inline-block;
        background-color: #6200ea;
        color: white;
        padding: 15px 30px;
        border-radius: 30px;
        text-decoration: none;
        font-size: 18px;
        transition: background-color 0.3s ease;
    }

    .card a:hover {
        background-color: #3700b3;
    }

    /* Bookings Table */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 30px;
        background-color: #1e1e1e;
        border-radius: 15px;
        margin: 20px 0px;
    }

    table th, table td {
        padding: 15px;
        text-align: center;
        border: 1px solid #444;
    }

    table th {
        background-color: #6200ea;
        color: white;
    }

    table td {
        color: #bbb;
    }

    .mark-paid {
        background-color: #4caf50;
        color: white;
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .mark-paid:hover {
        background-color: #388e3c;
    }

    .remove {
        background-color: #f44336;
        color: white;
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .remove:hover {
        background-color: #d32f2f;
    }

    /* Footer */
    .footer {
        text-align: center;
        margin-top: 50px;
        font-size: 16px;
        color: #bbb;
    }
</style>
<body>

<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>Admin Dashboard</h1>
    </div>

    <!-- Section for Welcome, Total Users, and Total Books -->
    <div class="section">
        <!-- Welcome Section -->
        <div class="card">
            <h3>Welcome, <?php echo htmlspecialchars($admin_username); ?>!</h3>
            <p>You are logged in as an admin.</p>
        </div>

        <!-- Total Users Section -->
        <div class="card">
            <h3>Total Registered Users</h3>
            <p class="total-users"><?php echo $total_users; ?></p>
            <a href="view_users.php">View Users</a>
        </div>

        <!-- Total Books Section -->
        <div class="card">
            <h3>Total Books in Library</h3>
            <p class="total-books"><?php echo $total_books; ?></p>
            <a href="view_books.php">View Books</a>
        </div>
    </div>


    <?php
// Query to get all rentals with calculated remaining days
// Query to get all rentals with calculated remaining and expired days
$query_rentals = "SELECT *, 
                        DATEDIFF(DATE_ADD(rental_date, INTERVAL rent_duration DAY), CURDATE()) AS remaining_days,
                        DATEDIFF(CURDATE(), DATE_ADD(rental_date, INTERVAL rent_duration DAY)) AS expired_days
                FROM rentals";
$result_rentals = mysqli_query($conn, $query_rentals);


// Handle form actions for rentals
if (isset($_GET['mark_rent_paid'])) {
    $rental_id = $_GET['mark_rent_paid'];
    $update_query = "UPDATE rentals SET payment_status = 'Paid' WHERE id = $rental_id";
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Payment status updated to Paid.'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating payment status.');</script>";
    }
}

if (isset($_GET['remove_rental'])) {
    $rental_id = $_GET['remove_rental'];
    $delete_query = "DELETE FROM rentals WHERE id = $rental_id";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Rental removed successfully.'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error removing rental.');</script>";
    }
}
?>
<h1>Recent Rented Books</h1>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Book ID</th>
            <th>Rent Price</th>
            <th>Rent Duration (Days)</th>
            <th>Rental Date</th>
            <th>Remaining Days</th>
            <th>Expired Days</th> <!-- New Column for Expired Days -->
            <th>Payment Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row_rental = mysqli_fetch_assoc($result_rentals)) { ?>
            <tr>
                <td><?php echo $row_rental['id']; ?></td>
                <td><?php echo $row_rental['user_id']; ?></td>
                <td><?php echo $row_rental['book_id']; ?></td>
                <td><?php echo $row_rental['rent_price']; ?></td>
                <td><?php echo $row_rental['rent_duration']; ?></td>
                <td><?php echo $row_rental['rental_date']; ?></td>
                <td>
                    <?php 
                    if ($row_rental['remaining_days'] > 0) {
                        echo $row_rental['remaining_days'] . " Days";
                    } else {
                        echo "<span style='color: red;'>Expired</span>";
                    }
                    ?>
                </td>
                <td>
                    <?php 
                    if ($row_rental['expired_days'] > 0) {
                        echo $row_rental['expired_days'] . " Days";
                    } else {
                        echo "-"; // If rental is not expired
                    }
                    ?>
                </td>
                <td><?php echo $row_rental['payment_status']; ?></td>
                <td>
                    <?php if ($row_rental['payment_status'] != 'Paid') { ?>
                        <a href="?mark_rent_paid=<?php echo $row_rental['id']; ?>" class="mark-paid">Mark as Paid</a>
                    <?php } ?>
                    <a href="?remove_rental=<?php echo $row_rental['id']; ?>" class="remove">Remove</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>


<br>
<br>
<br>


    <h1>Recent Sold Books</h1>
    <!-- Bookings Table (Full Width) -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Book ID</th>
                <th>Book Price</th>
                <th>Booking Date</th>
                <th>Payment Status</th>
                <th>Action</th>
            </tr>
        </thead>
        
        <tbody>
            <?php while ($row_booking = mysqli_fetch_assoc($result_bookings)) { ?>
                <tr>
                    <td><?php echo $row_booking['id']; ?></td>
                    <td><?php echo $row_booking['user_id']; ?></td>
                    <td><?php echo $row_booking['book_id']; ?></td>
                    <td><?php echo $row_booking['book_price']; ?></td>
                    <td><?php echo $row_booking['booking_date']; ?></td>
                    <td><?php echo $row_booking['payment_status']; ?></td>
                    <td>
                        <?php if ($row_booking['payment_status'] != 'paid') { ?>
                            <a href="?mark_paid=<?php echo $row_booking['id']; ?>" class="mark-paid">Mark as Paid</a>
                        <?php } else { ?>
                            <a href="?remove_booking=<?php echo $row_booking['id']; ?>" class="remove">Remove</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>


    

    <!-- Footer -->
    <div class="footer">
        <p>Library Management System - Admin Panel</p>
    </div>
</div>

</body>
</html>
