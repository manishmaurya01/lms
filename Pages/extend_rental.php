<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rental_id'], $_POST['additional_days'])) {
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

    $rental_id = $_POST['rental_id'];
    $additional_days = $_POST['additional_days'];

    // Fetch the current rent price and duration
    $sql = "SELECT rent_price, rent_duration FROM rentals WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $rental_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rental_data = $result->fetch_assoc();

    if ($rental_data) {
        $current_rent_price = $rental_data['rent_price'];
        $current_rent_duration = $rental_data['rent_duration'];

        // Calculate the new rent price based on the additional days
        $new_rent_duration = $current_rent_duration + $additional_days;
        $new_rent_price = $current_rent_price / $current_rent_duration * $new_rent_duration;

        // Update the rental duration and rent price
        $sql_update = "UPDATE rentals SET rent_duration = ?, rent_price = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("idi", $new_rent_duration, $new_rent_price, $rental_id);
        
        if ($stmt_update->execute()) {
            echo "<script>alert('Rental extended successfully!');</script>";
        } else {
            echo "<script>alert('Error extending rental.');</script>";
        }

        $stmt_update->close();
    } else {
        echo "<script>alert('Rental not found.');</script>";
    }

    $stmt->close();
    $conn->close();

    // Redirect to profile page
    echo "<script>window.location.href = 'profilepage.php';</script>";
}
?>
