<?php
require_once('DBconnect.php');
mysqli_query($conn, "SET time_zone = '+00:00'"); 

$sql = "DELETE FROM users
        WHERE otp IS NOT NULL
          AND status = 0
          AND TIMESTAMPDIFF(MINUTE, updated_at, NOW()) > 2";

if (mysqli_query($conn, $sql)) {
    echo "Expired users deleted successfully.";
} else {
    echo "Error deleting expired users: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
