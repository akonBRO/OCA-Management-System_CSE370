<?php
require_once('DBconnect.php');
session_start();

if (!isset($_SESSION['club_id']) || !isset($_SESSION['club_name'])) {
    header("Location: index.html");
    exit();
}

if (isset($_GET['booking_id'])) {
    $booking_id = (int)$_GET['booking_id'];
    $query = "SELECT uname, uid, number, email FROM registered_std WHERE booking_id = $booking_id";
    $result = mysqli_query($conn, $query);

    $audienceList = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $audienceList[] = $row;
    }

    $count_query = "SELECT COUNT(*) AS total FROM registered_std WHERE booking_id = $booking_id";
    $count_result = mysqli_query($conn, $count_query);
    $totalAudience = mysqli_fetch_assoc($count_result)['total'];
    echo json_encode(['audienceList' => $audienceList, 'totalAudience' => $totalAudience]);
}
?>
