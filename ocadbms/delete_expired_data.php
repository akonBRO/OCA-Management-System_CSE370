<?php

$conn = new mysqli('sql104.infinityfree.com', 'if0_37960572', 'Akon2024', 'if0_37960572_oca');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$today = date('Y-m-d');
$sql1 = "DELETE FROM registered_std WHERE booking_id IN (
            SELECT booking_id FROM bookings WHERE event_date < DATE_SUB('$today', INTERVAL 1 DAY)
         )";
$sql2 = "DELETE FROM budget_items WHERE booking_id IN (
            SELECT booking_id FROM bookings WHERE event_date < DATE_SUB('$today', INTERVAL 1 DAY)
         )";
$sql3 = "DELETE FROM budget WHERE booking_id IN (
            SELECT booking_id FROM bookings WHERE event_date < DATE_SUB('$today', INTERVAL 1 DAY)
         )";
$sql4 = "DELETE FROM bookings WHERE event_date < DATE_SUB('$today', INTERVAL 1 DAY)";
if ($conn->query($sql1) === TRUE) {
    echo "Expired data deleted from registered_std.<br>";
} else {
    echo "Error deleting from registered_std: " . $conn->error . "<br>";
}

if ($conn->query($sql2) === TRUE) {
    echo "Expired data deleted from budget_item.<br>";
} else {
    echo "Error deleting from budget_item: " . $conn->error . "<br>";
}

if ($conn->query($sql3) === TRUE) {
    echo "Expired data deleted from budget.<br>";
} else {
    echo "Error deleting from budget: " . $conn->error . "<br>";
}

if ($conn->query($sql4) === TRUE) {
    echo "Expired data deleted from bookings.<br>";
} else {
    echo "Error deleting from bookings: " . $conn->error . "<br>";
}

$conn->close();
?>
