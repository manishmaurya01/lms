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

// Query to get all books
$query = "SELECT id, title, author, description, image, buying_price, renting_price, created_at FROM books";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
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
            padding: 50px;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #ff3d00, #6200ea);
            color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 45px;
            font-weight: bold;
        }

        .add-book-btn {
            padding: 10px 20px;
            background-color: #00c853;
            color: white;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .add-book-btn:hover {
            background-color: #00a844;
        }

        /* Table Styles */
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: #1e1e1e;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        th, td {
            padding: 15px;
            text-align: left;
            font-size: 16px;
        }

        th {
            background-color: #6200ea;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #292929;
        }

        tr:hover {
            background-color: #333;
            cursor: pointer;
        }

        img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }

        td img {
            display: block;
            margin: auto;
        }

        a {
            color: #ff3d00;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 16px;
            color: #bbb;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 32px;
            }

            th, td {
                font-size: 14px;
                padding: 10px;
            }

            img {
                max-width: 70px;
            }

            .add-book-btn {
                padding: 8px 15px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>View Available Books</h1>
        <a href="add_book.php" class="add-book-btn">Add Book</a>
    </div>

    <!-- Table to display books -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Title</th>
                <th>Author</th>
                <th>Description</th>
                <th>Buying Price</th>
                <th>Renting Price</th>
                <th>Added On</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch the results and display them in the table
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td><img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['title']) . "'></td>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['buying_price']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['renting_price']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                    echo "<td>
                            <a href='edit_book.php?id=" . $row['id'] . "'>Edit</a> | 
                            <a href='delete_book.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this book?\")'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No books found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Library Management System - Admin Panel</p>
    </div>
</div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
