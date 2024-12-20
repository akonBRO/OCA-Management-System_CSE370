<?php
require_once('DBconnect.php');
session_start(); // Start the session

// Check if the user is logged in by verifying the session variables
if (!isset($_SESSION['club_id']) || !isset($_SESSION['club_name'])) {
    // If not logged in, redirect to the login page
    header("Location: index.html");
    exit();
}

// Check if booking_id is passed
if (isset($_GET['booking_id'])) {
    $booking_id = (int)$_GET['booking_id'];

    // Fetch audience details from the `registered_std` table
    $query = "SELECT uname, uid, number, email FROM registered_std WHERE booking_id = $booking_id";
    $result = mysqli_query($conn, $query);

    $audienceList = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $audienceList[] = $row;
    }

    // Fetch total audience count
    $count_query = "SELECT COUNT(*) AS total FROM registered_std WHERE booking_id = $booking_id";
    $count_result = mysqli_query($conn, $count_query);
    $totalAudience = mysqli_fetch_assoc($count_result)['total'];

    // Return data as JSON
    echo json_encode(['audienceList' => $audienceList, 'totalAudience' => $totalAudience]);
}
?>
