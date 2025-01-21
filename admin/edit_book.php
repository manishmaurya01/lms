<?php
// Start the session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}

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

// Get book ID from the URL
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    // Fetch book details
    $query = "SELECT * FROM books WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        echo "Book not found.";
        exit();
    }
} else {
    echo "No book ID provided.";
    exit();
}

// Handle form submission to update book
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    $buying_price = $_POST['buying_price'];
    $renting_price = $_POST['renting_price'];

    $update_query = "UPDATE books SET title = ?, author = ?, description = ?, image = ?, buying_price = ?, renting_price = ? WHERE id = ?";
    $stmt_update = $conn->prepare($update_query);
    $stmt_update->bind_param("ssssddi", $title, $author, $description, $image, $buying_price, $renting_price, $book_id);

    if ($stmt_update->execute()) {
        header("Location: view_books.php");
        exit();
    } else {
        echo "Error updating book.";
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        form {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background: #1e1e1e;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            background: #292929;
            color: #e0e0e0;
            border: 1px solid #444;
            border-radius: 5px;
        }

        button {
            padding: 10px 15px;
            background-color: #6200ea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #3700b3;
        }
    </style>
</head>
<body>
    <h1>Edit Book</h1>
    <form method="POST">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>

        <label for="author">Author</label>
        <input type="text" name="author" id="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>

        <label for="description">Description</label>
        <textarea name="description" id="description" required><?php echo htmlspecialchars($book['description']); ?></textarea>

        <label for="image">Image URL</label>
        <input type="text" name="image" id="image" value="<?php echo htmlspecialchars($book['image']); ?>" required>

        <label for="buying_price">Buying Price</label>
        <input type="number" step="0.01" name="buying_price" id="buying_price" value="<?php echo htmlspecialchars($book['buying_price']); ?>" required>

        <label for="renting_price">Renting Price</label>
        <input type="number" step="0.01" name="renting_price" id="renting_price" value="<?php echo htmlspecialchars($book['renting_price']); ?>" required>

        <button type="submit">Save Changes</button>
    </form>
</body>
</html>
