<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Library Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #121212;
            color: #f5f5f5;
        }

        /* Header */
        header {
            width: 100%;
            background-color: #333;
            padding: 15px 0;
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

        /* Contact Us Page */
        .contact-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background-color: #1c1c1c;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            text-align: center;
            color: #fff;
        }

        .contact-form {
            display: flex;
            flex-direction: column;
        }

        .contact-form input,
        .contact-form textarea {
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #444;
            background-color: #333;
            color: #fff;
        }

        .contact-form textarea {
            resize: vertical;
            min-height: 150px;
        }

        .contact-form button {
            background-color: #3498db;
            color: #fff;
            padding: 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
        }

        .contact-form button:hover {
            background-color: #2980b9;
        }

        .contact-info {
            text-align: center;
            margin-top: 30px;
        }

        .contact-info p {
            font-size: 1rem;
            margin: 10px 0;
        }

        .contact-info i {
            font-size: 1.3rem;
            margin-right: 8px;
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

<!-- Contact Us Page -->
<div class="contact-container">
    <h1>Contact Us</h1>
    <form action="submit_contact_form.php" method="POST" class="contact-form">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <textarea name="message" placeholder="Your Message" required></textarea>
        <button type="submit"><i class="fas fa-paper-plane"></i> Send Message</button>
    </form>

    <div class="contact-info">
        <p><i class="fas fa-map-marker-alt"></i> Address: 123 Library St., Booktown, 12345</p>
        <p><i class="fas fa-phone"></i> Phone: +91-123-456-7890</p>
        <p><i class="fas fa-envelope"></i> Email: contact@libraryportal.com</p>
    </div>
</div>

</body>
</html>
