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


// Fetch the first four books from the database
$sql = "SELECT title, author, description, image FROM books LIMIT 4"; // Added LIMIT 4
$result = $conn->query($sql);

$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
} else {
    echo "No books found!";
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Dashboard</title>
    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        
/* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: #121212;
    color: #f5f5f5;
    line-height: 1.6;
}

/* Header */
header {
    position: fixed;
    width: 100%;
    background-color: #333;
    padding: 15px 0;
    top: 0;
    z-index: 100;
}

nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: auto;
}

nav .logo {
    font-size: 1.8rem;
    color: #fff;
    font-weight: 700;
}

nav ul {
    display: flex;
    list-style-type: none;
}

nav ul li {
    margin: 0 20px;
}

nav ul li a {
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    font-size: 1.1rem;
}

nav ul li a:hover {
    color: #ff9900;
}

/* Hero Section with Gradient Background Animation */
.hero-section {
    position: relative;
    height: 100vh;
    background: linear-gradient(90deg, #2c3e50, #34495e, #1abc9c, #2ecc71);
    background-size: 400% 400%;
    animation: gradientBG 6s ease infinite;
}

@keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.hero-section .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #fff;
    z-index: 10;
    text-align: center;
}

.hero-section h1 {
    font-size: 3.5rem;
    margin-bottom: 20px;
}

.hero-section p {
    font-size: 1.5rem;
}

/* Books Section */
.books-section {
    padding: 60px 20px;
    background-color: #121212; /* Dark background */
    text-align: center;
    color: #f5f5f5; /* Light text */
}

.books-section h2 {
    font-size: 2.5rem;
    margin-bottom: 40px;
    color: #ffffff; /* Bright heading for contrast */
}

.books-container {
    display: flex;
    justify-content: center; /* Center books horizontally */
    align-items: stretch;
    gap: 20px;
    flex-wrap: nowrap; /* Prevent wrapping for a single row */
    overflow-x: auto; /* Allow horizontal scrolling */
    padding-bottom: 15px; /* Space below the row */
    scroll-snap-type: x mandatory; /* Smooth snapping for horizontal scroll */
}

.book-card {
    background-color: #1e1e1e; /* Dark card background */
    padding: 20px;
    border-radius: 10px;
    width: 280px; /* Fixed width */
    height: 450px; /* Fixed height */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4); /* Subtle shadow */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    text-align: left;
    scroll-snap-align: center; /* Align cards during horizontal scroll */
}

.book-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.6); /* Enhanced shadow on hover */
}

.book-card img {
    width: 100%;
    height: 180px; /* Consistent image height */
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #2e2e2e; /* Border to enhance focus on dark mode */
}

.book-card h3 {
    font-size: 1.5rem;
    margin-top: 15px;
    color: #ffffff; /* Bright text for readability */
}

.book-card p {
    font-size: 1rem;
    color: #b0b0b0; /* Softer light gray for descriptions */
    margin: 10px 0;
    flex-grow: 1; /* Push buttons to the bottom */
}

.book-actions {
    display: flex;
    justify-content: space-between;
    margin-top: auto; /* Push buttons to the bottom */
}

.rent-button, .buy-button {
    background-color: #3a82f6; /* Blue buttons for visibility */
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    font-size: 0.9rem;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    text-transform: uppercase;
    font-weight: bold;
}

.rent-button:hover, .buy-button:hover {
    background-color: #2a6bc1; /* Darker blue on hover */
}

/* Scrollbar Styling (for modern browsers) */
.books-container::-webkit-scrollbar {
    height: 10px;
}

.books-container::-webkit-scrollbar-thumb {
    background: #3a82f6;
    border-radius: 5px;
}

.books-container::-webkit-scrollbar-track {
    background: #2e2e2e; /* Match dark mode background */
}

/* Responsive Design */
@media (max-width: 768px) {
    .book-card {
        width: 240px;
        height: 420px;
    }
}

@media (max-width: 576px) {
    .book-card {
        width: 200px;
        height: 380px;
    }

    .book-card h3 {
        font-size: 1.4rem;
    }

    .book-card p {
        font-size: 0.9rem;
    }

    .rent-button, .buy-button {
        padding: 8px 15px;
        font-size: 0.8rem;
    }
}

/* Contact Section */
.contact-section {
    padding: 60px 20px;
    background-color: #333;
    text-align: center;
}

.contact-section h2 {
    font-size: 2.5rem;
    margin-bottom: 20px;
}

.contact-section p {
    font-size: 1.5rem;
}

.contact-button {
    background-color: #ff9900;
    color: #fff;
    border: none;
    padding: 15px 30px;
    font-size: 1.2rem;
    cursor: pointer;
    border-radius: 5px;
    margin-top: 20px;
    transition: background-color 0.3s ease;
}

.contact-button:hover {
    background-color: #ff6600;
}

/* Footer */
footer {
    text-align: center;
    padding: 20px;
    background-color: #121212;
    color: #fff;
}

/* General styling for icons */
nav ul li a i, .contact-button i {
    margin-right: 8px; /* Space between icon and text */
}

/* Make icons slightly larger for better visibility */
nav ul li a i, .contact-button i, h2 i {
    font-size: 1.5rem;
}

/* Optional: Hover effect for icons */
nav ul li a:hover i, .contact-button:hover i {
    color: #ff9900;
}
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <header>
        <nav>
            <div class="logo">Library Portal</div>
            <ul>
                <li><a href="#home"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="search_books.php"><i class="fas fa-book"></i> Search Books</a></li>
                <li><a href="#profile"><i class="fas fa-user"></i> Profile</a></li>
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

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="overlay">
            <h1>Welcome, John Doe!</h1>
            <p>Browse our collection of books, borrow, and track your reading progress!</p>
        </div>
    </section>

    <!-- Books Section -->
    <section id="books" class="books-section">
        <h2><i class="fas fa-book-reader"></i> Featured Books</h2>
        <div class="books-container">
            <?php foreach ($books as $book): ?>
            <div class="book-card">
                <img src="<?php echo $book['image']; ?>" alt="<?php echo $book['title']; ?>">
                <h3><?php echo $book['title']; ?></h3>
                <p><strong>Author:</strong> <?php echo $book['author']; ?></p>
                <p><?php echo $book['description']; ?></p>
                <div class="book-actions">
                    <button class="rent-button"><i class="fas fa-shopping-cart"></i> Rent</button>
                    <button class="buy-button"><i class="fas fa-money-bill"></i> Buy</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
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
