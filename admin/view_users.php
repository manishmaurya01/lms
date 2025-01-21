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

// Query to get all users
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users - Admin Panel</title>
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
            background: linear-gradient(135deg, #ff3d00, #6200ea);
            color: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 45px;
            font-weight: bold;
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
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>View Registered Users</h1>
    </div>

    <!-- Table to display user information -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>register date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch the results and display them in the table
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No users found</td></tr>";
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
