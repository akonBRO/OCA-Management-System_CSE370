<?php
require_once('DBconnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $time_slot = $_POST['time_slot'];
    $room_number = $_POST['room_number'];
    $event_details = $_POST['event_details'];
    $club_name = $_POST['club_name'];
    $std_reg = $_POST['registration'];

    // Check if room is available
    $check_query = "SELECT * FROM bookings 
                    WHERE event_date = '$event_date' 
                    AND time_slot = '$time_slot' 
                    AND room_number = '$room_number'";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        // Room is already booked
        echo "<script>alert('Room $room_number is already booked for this date and time.'); window.location.href='room_booking.php';</script>";
    } else {
        // Insert new booking
        $insert_query = "INSERT INTO bookings (event_name, event_date, time_slot, room_number, event_details, club_name, std_reg, comments) 
                         VALUES ('$event_name', '$event_date', '$time_slot', '$room_number', '$event_details', '$club_name', '$std_reg', NULL)";
        if (mysqli_query($conn, $insert_query)) {
            echo "<script>alert('Room successfully booked!'); window.location.href='room_booking.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
