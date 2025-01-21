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

// Handle search and filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$author = isset($_GET['author']) ? $_GET['author'] : '';
$price_min = isset($_GET['price_min']) ? (float)$_GET['price_min'] : 0;
$price_max = isset($_GET['price_max']) ? (float)$_GET['price_max'] : 500;

// Build SQL query
$sql = "SELECT * FROM books WHERE title LIKE ? AND author LIKE ? AND buying_price BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$search_param = "%$search%";
$author_param = "%$author%";
$stmt->bind_param("ssdd", $search_param, $author_param, $price_min, $price_max);
$stmt->execute();
$result = $stmt->get_result();

$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books</title>
    <link rel="stylesheet" href="../css/search.css">
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
                <?php

                if (isset($_SESSION['user_id'])): ?>
                    <!-- User is logged in, show Logout link -->
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php else: ?>
                    <!-- User is not logged in, show Register link -->
                    <li><a href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                <?php endif; ?>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="search-container">   
        <form action="" method="GET">
            <input type="text" name="search" placeholder="Search by title..." value="<?php echo htmlspecialchars($search); ?>">
            <input type="text" name="author" placeholder="Filter by author..." value="<?php echo htmlspecialchars($author); ?>">
            <button type="submit"><i class="fas fa-search"></i> Search</button>

            <div class="price-filter">
            <label for="price_min">Min Price: ₹<span id="min-price-val"><?php echo htmlspecialchars($price_min); ?></span></label>
            <input type="range" name="price_min" id="price_min" min="0" max="500" step="0.01" value="<?php echo htmlspecialchars($price_min); ?>" oninput="updatePrice()">

            <label for="price_max">Max Price: ₹<span id="max-price-val"><?php echo htmlspecialchars($price_max); ?></span></label>
            <input type="range" name="price_max" id="price_max" min="0" max="500" step="0.01" value="<?php echo htmlspecialchars($price_max); ?>" oninput="updatePrice()">
        </div>

        </form>
    </div>

    <div class="books-container">
        <?php foreach ($books as $book): ?>
            <div class="book-card">
                <img src="<?php echo $book['image']; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($book['description']); ?></p>
                <p><strong>Buy Price:</strong> ₹<?php echo htmlspecialchars(number_format($book['buying_price'], 2)); ?></p>
                <p><strong>Rent Price:</strong> ₹<?php echo htmlspecialchars(number_format($book['renting_price'], 2)); ?></p>
                <div class="book-actions">
                    <!-- Inside the book-actions div -->
                    <button class="rent-button" onclick="openRentModal(<?php echo $book['id']; ?>, <?php echo $book['renting_price']; ?>)">Rent for ₹<?php echo htmlspecialchars(number_format($book['renting_price'], 2)); ?></button>
                    <button class="buy-button" onclick="openModal(<?php echo $book['id']; ?>, <?php echo $book['buying_price']; ?>)">Buy for ₹<?php echo htmlspecialchars(number_format($book['buying_price'], 2)); ?></button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>



    <!-- Buy Book Modal -->
    <div id="buyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Confirm Purchase</h2>
            </div>

            <div class="modal-body">
                <p>Are you sure you want to buy this book?</p>
                <form action="buy.php" method="POST">
                    <input type="hidden" name="book_id" id="book_id">
                    <input type="hidden" name="book_price" id="book_price">
                    <input type="hidden" name="user_id" value="1"> <!-- Dynamically set this value based on session -->
                    <button type="submit">Confirm Purchase</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Rent Book Modal -->
    <div id="rentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeRentModal()">&times;</span>
                <h2>Confirm Rent</h2>
            </div>
            <div class="modal-body">
                <p>How many days do you want to rent this book?</p>
                <form action="rent.php" method="POST">
                    <!-- Hidden inputs to store book details -->
                    <input type="hidden" name="book_id" id="rent_book_id">
                    <input type="hidden" name="rent_price" id="rent_book_price">

                    <!-- Duration input -->
                    <label for="rent_duration">Duration (days):</label>
                    <input type="number" name="rent_duration" id="rent_duration" min="1" value="1" required>

                    <!-- Display total rent -->
                    <p>Total Rent: ₹<span id="total_rent">0</span></p>

                    <!-- Confirm button -->
                    <button type="submit">Confirm Rent</button>
                </form>
            </div>
        </div>
    </div>


    <script>
        function openModal(bookId, bookPrice) {
            document.getElementById('book_id').value = bookId;
            document.getElementById('book_price').value = bookPrice;
            document.getElementById('buyModal').style.display = "block";
        }

        function closeModal() {
            document.getElementById('buyModal').style.display = "none";
        }

        function openRentModal(bookId, bookPrice) {
            document.getElementById('rent_book_id').value = bookId;
            document.getElementById('rent_book_price').value = bookPrice;
            document.getElementById('total_rent').innerText = bookPrice; // Initial total rent
            document.getElementById('rentModal').style.display = "block";
        }

        function closeRentModal() {
            document.getElementById('rentModal').style.display = "none";
        }

        // Update the rent total cost as user inputs the duration
        document.getElementById('rent_duration').addEventListener('input', function() {
            let duration = this.value;
            let pricePerDay = document.getElementById('rent_book_price').value;
            let totalCost = duration * pricePerDay;
            document.getElementById('total_rent').innerText = totalCost.toFixed(2);
        });
        // Update price values dynamically without refreshing the form
    function updatePrice() {
        var minPrice = document.getElementById("price_min").value;
        var maxPrice = document.getElementById("price_max").value;
        
        // Update the displayed values
        document.getElementById("min-price-val").innerText = minPrice;
        document.getElementById("max-price-val").innerText = maxPrice;

        // Update the form values to reflect the selected range
        document.querySelector("input[name='price_min']").value = minPrice;
        document.querySelector("input[name='price_max']").value = maxPrice;

        // Trigger the form submission to apply the filters
        document.getElementById("search-form").submit();
    }
    </script>
</body>

</html>