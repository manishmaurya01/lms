<?php
// Start the session
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $buying_price = $_POST['buying_price'];
    $renting_price = $_POST['renting_price'];
    
    // Get the image URL from the form
    $image_url = $_POST['image_url'];  // Save the URL input
    
    // Insert data into the database
    $query = "INSERT INTO books (title, author, description, image, buying_price, renting_price, created_at) 
              VALUES ('$title', '$author', '$description', '$image_url', '$buying_price', '$renting_price', NOW())";
    if (mysqli_query($conn, $query)) {
        echo "<p style='color: #4CAF50;'>Book added successfully!</p>";
    } else {
        echo "<p style='color: #f44336;'>Error: " . mysqli_error($conn) . "</p>";
    }
}

// Close the connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
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
            padding: 40px;
        }

        .container {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            background-color: #1e1e1e;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #e0e0e0;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 14px;
            margin-bottom: 8px;
            color: #bbb;
        }

        input[type="text"], input[type="number"], textarea {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #444;
            border-radius: 5px;
            font-size: 14px;
            background-color: #333;
            color: #e0e0e0;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        button {
            padding: 12px;
            background-color: #6200ea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #3700b3;
        }

        .form-message {
            text-align: center;
            font-size: 16px;
            margin-top: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            h1 {
                font-size: 24px;
            }

            input[type="text"], input[type="number"], textarea {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Add a New Book</h1>
    <form method="POST">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required>

        <label for="author">Author:</label>
        <input type="text" name="author" id="author" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>

        <label for="buying_price">Buying Price:</label>
        <input type="number" name="buying_price" id="buying_price" required>

        <label for="renting_price">Renting Price:</label>
        <input type="number" name="renting_price" id="renting_price" required>

        <label for="image_url">Image URL:</label>
        <input type="text" name="image_url" id="image_url" required>

        <button type="submit">Add Book</button>
    </form>

    <!-- Message display -->
    <div class="form-message">
        <!-- Success/Error messages will appear here -->
    </div>
</div>

</body>
</html>
