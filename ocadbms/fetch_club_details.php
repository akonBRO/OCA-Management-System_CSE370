<?php
require_once('DBconnect.php');

if (isset($_GET['club_id'])) {
    $club_id = (int)$_GET['club_id'];

    // Fetch club details
    $club_query = "SELECT * FROM clubs WHERE `Club ID` = $club_id";
    $club_result = mysqli_query($conn, $club_query);
    $club = mysqli_fetch_assoc($club_result);

    // Fetch approved bookings
    $bookings_query = "SELECT * FROM bookings WHERE `club_name` = '" . mysqli_real_escape_string($conn, $club['Club Name']) . "' AND `status` = 'approved'";
    $bookings_result = mysqli_query($conn, $bookings_query);

    if ($club) {
        echo "<h2>" . htmlspecialchars($club['Club Name']) . "</h2>";
        echo "<p><strong>Advisor:</strong> " . htmlspecialchars($club['Advisor']) . "</p>";
        echo "<p><strong>President:</strong> " . htmlspecialchars($club['President']) . "</p>";
        
        echo "<p><strong>Email:</strong> " . htmlspecialchars($club['Email']) . "</p>";
        echo "<p><strong>Mobile:</strong> " . htmlspecialchars($club['Mobile']) . "</p>";
        echo "<p><strong>Founded Date:</strong> " . htmlspecialchars($club['Founded Date']) . "</p>";
        echo "<p><strong>Total Members:</strong> " . htmlspecialchars($club['Number of Members']) . "</p>";
        echo "<p><strong>Social Media:</strong> <a href='" . htmlspecialchars($club['Social Media Links']) . "' target='_blank'>" . htmlspecialchars($club['Social Media Links']) . "</a></p>";

        echo "<p><strong>Achievements:</strong> " . htmlspecialchars($club['Achievements']) . "</p>";
        
        echo "<p><strong>Description:</strong> " . htmlspecialchars($club['Club Description']) . "</p>";

        echo "<h3>Events</h3>";
        if (mysqli_num_rows($bookings_result) > 0) {
            echo "<table class='booking-table'>";
echo "<tr><th>Event Name</th><th>Date</th><th>Time</th><th>Room</th><th>Description</th></tr>";
while ($booking = mysqli_fetch_assoc($bookings_result)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($booking['event_name']) . "</td>";
    echo "<td>" . htmlspecialchars($booking['event_date']) . "</td>";
    echo "<td>" . htmlspecialchars($booking['time_slot']) . "</td>";
    echo "<td>" . htmlspecialchars($booking['room_number']) . "</td>";
    echo "<td>" . htmlspecialchars($booking['event_details']) . "</td>";
    echo "</tr>";
}
echo "</table>";
} else {
    echo "<p>No upcoming events.</p>";
}
    } else {
        echo "Club not found.";
    }
} else {
    echo "Invalid Club ID.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    .booking-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-family: 'Roboto', sans-serif;
        border-radius: 10px;
        overflow: hidden;
    }

    .booking-table th, .booking-table td {
        padding: 12px 20px;
        text-align: left;
        font-size: 1.1rem;
        border-bottom: 1px solid #ddd;
    }

    .booking-table th {
        background-color: #3498db;
        color: white;
        text-transform: uppercase;
    }

    .booking-table td {
        background-color: #f9f9f9;
        color: #333;
    }

    .booking-table tr:nth-child(even) td {
        background-color: #f1f1f1;
    }

    .booking-table tr:hover td {
        background-color: #eaf3ff;
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    .booking-table td {
        font-weight: 400;
    }

    .booking-table th, .booking-table td {
        border-right: 1px solid #ddd;
    }

    .booking-table th:last-child, .booking-table td:last-child {
        border-right: none;
    }
</style>
</head>
<body>

</body>
</html>