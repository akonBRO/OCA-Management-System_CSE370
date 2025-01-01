<?php
require_once('DBconnect.php');
session_start(); // Start the session

// Get the User ID from the session
$user_id = (int)$_SESSION['uid'];

// Query to fetch the user's details and bookings
$query_registered = "SELECT * FROM registered_std WHERE `uid` = $user_id";
$result_registered = mysqli_query($conn, $query_registered);

if ($result_registered && mysqli_num_rows($result_registered) > 0) {
    $registered_data = mysqli_fetch_assoc($result_registered); // Fetch user data
    
    // Fetch booking_ids associated with the user
    $booking_ids = [];
    do {
        $booking_ids[] = $registered_data['booking_id'];
    } while ($registered_data = mysqli_fetch_assoc($result_registered));  // Collect all booking_ids for the user

    // Convert array of booking_ids to a comma-separated string
    $booking_ids_str = implode(",", $booking_ids);

    // Fetch bookings from the bookings table, sorted by event_date
    $query_bookings = "SELECT * FROM bookings WHERE `booking_id` IN ($booking_ids_str) ORDER BY `event_date`";
    $result_bookings = mysqli_query($conn, $query_bookings);
} else {
    echo "No registered data found for the user.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Bookings</title>
    <style>
        /* Header Styling */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #2c3e50;
            color: white;
            padding: 10px 20px; /* Adjusted padding for consistent spacing */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%; /* Ensures the header spans the full width */
            box-sizing: border-box; /* Includes padding in the width calculation */
            z-index: 1000;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }

        .header .website-logo img {
            width: 70px;
            height: 70px;
            border-radius: 5px;
            margin-right: 10px;
        }

        .header nav {
            display: flex;
            align-items: center;
        }

        .header nav a {
            color: white;
            text-decoration: none;
            font-size: 1.1em;
            margin: 0 20px;
            font-weight: 500;
            text-transform: capitalize;
            letter-spacing: 1px;
            position: relative;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .header nav a:hover {
            color: #3498db;
            transform: translateY(-5px);
        }

        .header nav a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #3498db;
            transition: width 0.3s ease;
        }

        .header nav a:hover::after {
            width: 100%;
        }

        .header .user-info {
            font-size: 1.1em;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .header .user-info a {
            color: white;
            text-decoration: none;
            margin-left: 10px;
            font-weight: 600;
        }

        .header .user-info a:hover {
            color: #76c7c0;
            text-decoration: underline;
        }
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 120px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            text-align: left;
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4A90E2;
            color: white;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 1.5rem;
            }

            th, td {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <!-- Website Logo -->
        <div class="website-logo">
            <img src="images/oca.jpg" alt="Website Logo"> 
        </div>
        
        <!-- Navigation Links -->
        <nav>
            <a href="home.php">Home</a>
            <a href="show_clubs.php">Clubs</a>
            <a href="user_bookings.php">My Events</a>
            <a href="#">Contact</a>
            <a href="logout.php">Logout</a>
        </nav>

        <!-- User Info -->
        <div class="user-info">
            <span>Welcome,</span>
            <a href="user_profile.php">
                <?php
                if (isset($_SESSION['uname'])) {
                    echo htmlspecialchars($_SESSION['uname']);
                } else {
                    echo "Guest";
                }
                ?>
            </a>
        </div>
    </div>
<div class="container">
    <h1>Your Event Bookings</h1>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Club Name</th>
                    <th>Event Name</th>
                    <th>Event Date</th>
                    <th>Time Slot</th>
                    <th>Room Number</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_bookings && mysqli_num_rows($result_bookings) > 0) {
                    while ($row = mysqli_fetch_assoc($result_bookings)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['club_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['event_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['event_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['time_slot']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['room_number']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No bookings found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
