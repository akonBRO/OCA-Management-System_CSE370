<?php
session_start();
require_once('DBconnect.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Process updates from the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['event_name'] as $booking_id => $event_name) {
        // Sanitize and retrieve all updated values
        $event_name = mysqli_real_escape_string($conn, $event_name);
        $event_date = mysqli_real_escape_string($conn, $_POST['event_date'][$booking_id]);
        $time_slot = mysqli_real_escape_string($conn, $_POST['time_slot'][$booking_id]);
        $room_number = mysqli_real_escape_string($conn, $_POST['room_number'][$booking_id]);
        $event_details = mysqli_real_escape_string($conn, $_POST['event_details'][$booking_id]);
        $status = mysqli_real_escape_string($conn, $_POST['status'][$booking_id]);
        $comments = mysqli_real_escape_string($conn, $_POST['comments'][$booking_id]);

        if ($status === 'rejected') {
            $sql = "
                UPDATE bookings 
                SET 
                    event_name = '$event_name',
                    event_date = '$event_date',
                    time_slot = NULL, -- Set time_slot to NULL
                    room_number = '$room_number',
                    event_details = '$event_details',
                    status = '$status',
                    comments = '$comments'
                WHERE booking_id = $booking_id
            ";
        } else {
            $sql = "
                UPDATE bookings 
                SET 
                    event_name = '$event_name',
                    event_date = '$event_date',
                    time_slot = '$time_slot',
                    room_number = '$room_number',
                    event_details = '$event_details',
                    status = '$status',
                    comments = '$comments'
                WHERE booking_id = $booking_id
            ";
        }

        // Execute the query
        if (!mysqli_query($conn, $sql)) {
            echo "Error updating booking ID $booking_id: " . mysqli_error($conn);
        }
    }

    // Redirect back to manage bookings page with success message
    header("Location: manage_bookings.php?success=1");
    exit();
} else {
    // Redirect if accessed directly
    header("Location: manage_bookings.php");
    exit();
}
?>
