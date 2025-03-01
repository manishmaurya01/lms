<?php
session_start(); 

// Database connection
$servername = "localhost"; // Change if your DB is hosted elsewhere
$username = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password
$dbname = "library_db"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="overlay">
            <h1>Welcome, to te library managment system!</h1>
            <p>Browse our collection of books, borrow, and track your reading progress!</p>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="about-section">
        <h2><i class="fas fa-info-circle"></i> About Us</h2>
        <p>Welcome to the Library Portal! We offer a wide selection of books for both rent and purchase. Whether you're looking for the latest bestsellers or classic literature, we have something for everyone.</p>
        <p>Our mission is to make books accessible to all readers and foster a love of reading. We are constantly updating our collection, so you’ll always find something new and exciting!</p>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <h2><i class="fas fa-phone-alt"></i> Contact Us</h2>
        <p>If you have any questions, feel free to reach out to us!</p>
        <button class="contact-button"><i class="fas fa-envelope"></i> Contact Support</button>
    </section>

    <footer>
        <p>&copy; 2025 Library Portal. All rights reserved.</p>
    </footer>
</body>
</html>
