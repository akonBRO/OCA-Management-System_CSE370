<?php
require_once('DBconnect.php');
session_start(); // Start the session

// Check if the user is logged in by verifying the session variables
if (!isset($_SESSION['uname']) || !isset($_SESSION['uid'])) {
    // If not logged in, redirect to the login page
    header("Location: index.html");
    exit();
}

// Handle the registration logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register_event'])) {
        $booking_id = $_POST['booking_id']; // Booking ID from the form
        $uid = $_SESSION['uid']; // User ID from session
        $uname = $_SESSION['uname']; // Username from session
        $email = $_SESSION['umail']; // User email (assumed to be in the session)
        $number = $_SESSION['umobile']; // User number (assumed to be in the session)

        // Insert or update the registration information in the database
        $sql = "INSERT INTO registered_std (uname, uid, email, number, booking_id) 
                VALUES ('$uname', '$uid', '$email', '$number', '$booking_id') 
                ON DUPLICATE KEY UPDATE uname='$uname', email='$email', number='$number'";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Registration successful!');</script>";
        } else {
            echo "<script>alert('Error during registration.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Homepage</title>
    <style>
        /* Add your existing styles here */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            margin: 0;
            padding-top: 100px; /* Prevents content from being hidden under the header */
            color: #333;
            overflow-x: hidden; /* Prevents horizontal scrolling */
        }

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
        .container {
            max-width: 1500px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 15px;
            text-align: center;
            border-bottom: 2px solid #ddd;
            border-right: 2px solid #ddd;
        }

        table th {
            background-color: #4A90E2;
            color: white;
            font-size: 1.2em;
        }

        table td {
            font-size: 1.1em;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        table td:last-child, table th:last-child {
            border-right: none;
        }
        /* Styling for the Register Button */
.register-btn {
    background-color:rgb(0, 151, 8); 
    color: white; 
    padding: 10px 20px; 
    font-size: 1em; 
    border: none; 
    border-radius: 5px; 
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease; 
}

/* Hover effect for the Register Button */
.register-btn:hover {
    background-color: #3498db; 
    transform: translateY(-3px);
}
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                padding: 20px 15px;
            }

            .header .website-logo {
                text-align: center;
                margin-bottom: 10px;
            }

            .header nav {
                flex-direction: column;
                margin-top: 15px;
            }

            .header nav a {
                margin: 8px 0;
            }
        }

       


        footer {
            text-align: center;
            padding: 10px;
            background-color: #4A90E2;
            color: white;
            position: fixed;
            width: 100%;
            bottom: 0;
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
        <h2>Upcoming Events</h2>

        <table>
            <thead>
                <tr>
                    <th>Serial</th>
                    <th>Club Name</th>
                    <th>Event Name</th>
                    <th>Event Date</th>
                    <th>Time Slot</th>
                    <th>Room Number</th>
                    <th>Event Details</th>
                    <th>Registration</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once('DBconnect.php');

                $sql = "SELECT club_name, event_name, event_date, time_slot, room_number, event_details, std_reg, booking_id 
                        FROM bookings 
                        WHERE status = 'approved'  ORDER BY `event_date`";

                $result = mysqli_query($conn, $sql);
                $serial = 1;

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $serial++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['club_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['event_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['event_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['time_slot']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['room_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['event_details']) . "</td>";
                        
                        // Check if the event is open for registration
if ($row['std_reg'] === 'Yes') {
    echo "<td>
            <form method='POST' action=''>
                <input type='hidden' name='booking_id' value='" . $row['booking_id'] . "' />
                <button type='submit' name='register_event' class='register-btn' onclick='return confirmRegistration()'>Register</button>
            </form>
            <script>
                function confirmRegistration() {
                    return confirm('Are you sure you want to register for this event?');
                }
            </script>
          </td>";


                        } else {
                            echo "<td>N/A</td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No upcoming events found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>