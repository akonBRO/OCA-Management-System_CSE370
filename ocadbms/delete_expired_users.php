<?php
require_once('DBconnect.php');

// Set MySQL time zone explicitly (optional, uses server time zone by default)
mysqli_query($conn, "SET time_zone = '+00:00'"); // Adjust to your server time zone if needed

// SQL query to delete rows
$sql = "DELETE FROM users
        WHERE otp IS NOT NULL
          AND status = 0
          AND TIMESTAMPDIFF(MINUTE, updated_at, NOW()) > 2";

// Execute the query
if (mysqli_query($conn, $sql)) {
    echo "Expired users deleted successfully.";
} else {
    echo "Error deleting expired users: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
