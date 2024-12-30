<?php
require_once('DBconnect.php');
session_start(); // Start the session

// Check if the user is logged in by verifying the session variables
if (!isset($_SESSION['club_id']) || !isset($_SESSION['club_name'])) {
    // If not logged in, redirect to the login page
    header("Location: index.html");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking Page</title>
    <link rel="stylesheet" href="css/styledemo.css">
    <style>
        /* Reset some styles for consistency */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }
/* General reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Header Styling */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #2c3e50; /* Dark blue background */
    color: white;
    padding: 5px 30px;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2); /* Adds some depth */
}

/* Website Logo (Permanent Logo) */
.header .website-logo img {
    width: 70px; /* Adjust size */
    height: 70px;
    border-radius: 5px;
    margin-right: 10px;
}

/* Navigation Links */
.header nav {
    display: flex;
    align-items: center;
    justify-content: center;
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

/* Hover effects for links */
.header nav a:hover {
    color: #3498db; /* Bright blue color on hover */
    transform: translateY(-5px); /* Slight lift effect */
}

/* Underline effect for navigation links */
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
    width: 100%; /* Underline expands on hover */
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
    color: #f1f1f1;
    text-decoration: underline;
}
.header .user-info a:hover {
    color: #76c7c0; /* Light cyan on hover */
}

/* Responsive Design */
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

    .header .right-bar {
        width: 80px;
        margin-left: 0;
    }
    .container {
        padding: 20px 15px;
    }

    h1 {
        font-size: 1.8rem;
    }

    button[type="submit"] {
        font-size: 1rem;
    }
}


        /* Content Styling */
        /* Container Styling */
.container {
    max-width: 600px;
    width: 100%;
    margin-top: 350px;
    background: #f9f9f9;
    padding: 25px 30px;
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    border: 1px solid #e0e0e0;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.container:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
}
/* Form Title */
h1 {
    text-align: center;
    font-size: 2rem;
    color: #333;
    margin-bottom: 20px;
    font-weight: 600;
    letter-spacing: 1px;
}
        /* Form Styling */
form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* Input, Select, Textarea Styling */
input, select, textarea {
    padding: 12px 15px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 10px;
    background: #ffffff;
    box-shadow: inset 0 3px 6px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

input:focus, select:focus, textarea:focus {
    border-color: #3498db;
    box-shadow: 0 0 8px rgba(52, 152, 219, 0.3);
    outline: none;
}

/* Placeholder Style */
input::placeholder, textarea::placeholder {
    color: #aaa;
    font-size: 0.9rem;
}

/* Label Styling */
label {
    font-weight: bold;
    color: #555;
    margin-bottom: 5px;
}

/* Submit Button */
button[type="submit"] {
    background: linear-gradient(135deg, #3498db, #8e44ad);
    color: white;
    font-size: 1.1rem;
    font-weight: bold;
    border: none;
    border-radius: 12px;
    padding: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

button[type="submit"]:hover {
    background: linear-gradient(135deg, #2980b9, #6c3483);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}
    </style>
</head>
<body>
   <!-- Header Section -->
<div class="header">
    <!-- Website Logo (Permanent Logo) -->
    <div class="website-logo">
        <img src="images/oca.jpg" alt="Website Logo"> <!-- Permanent logo of the website -->
         <!-- Optionally, you can add text if needed -->
    </div>
    
    <!-- Navigation Links -->
    <nav>
        <a href="room_booking.php">Home</a>
        <a href="my_bookings.php">My Bookings</a>
        <a href="audience.php">Audience</a>
        <a href="#">Contact</a>
        <a href="logout.php">Logout</a>
    </nav>

    <!-- Right Bar for Club Logo -->
    <div class="user-info">
        <span>Welcome,</span>
        <a href="club_profile.php">
            <?php
            if (isset($_SESSION['club_name'])) {
                echo htmlspecialchars($_SESSION['club_name']);
            } else {
                echo "Guest";
            }
            ?>
        </a>
    </div>
</div>



    <!-- Main Content -->
    <div class="container">
        <h1>Room Booking</h1>
        <?php
        //require_once('DBconnect.php');
        
        ?>
        <form method="post" action="check_availability.php">
            <!-- Club Name -->
            <label for="club_name">Club Name</label>
            <input type="text" name="club_name" id="club_name" value="<?php echo htmlspecialchars($_SESSION['club_name']); ?>" readonly>

            <!-- Event Name -->
            <label for="event_name">Event Name</label>
            <input type="text" name="event_name" id="event_name" placeholder="Enter event name" required>

            <!-- Event Date -->
<label for="event_date">Event Date</label>
<input type="date" name="event_date" id="event_date" required>

<script>
    // Get today's date
    const today = new Date();

    // Calculate the date 7 days from today
    const sevenDaysLater = new Date();
    sevenDaysLater.setDate(today.getDate() + 7);

    // Format the date as YYYY-MM-DD
    const minDateString = sevenDaysLater.toISOString().split('T')[0];

    // Set the min attribute for the input
    const eventDateInput = document.getElementById('event_date');
    eventDateInput.min = minDateString;
</script>


            <!-- Time Slot -->
            <label for="time_slot">Time Slot</label>
            <select name="time_slot" id="time_slot" required>
                <option value="">Select Time Slot</option>
                <?php
                $time_query = "SELECT * FROM time_slots";
                $result = mysqli_query($conn, $time_query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$row['time_slot']}'>{$row['time_slot']}</option>";
                }
                ?>
            </select>

            <!-- Room Number -->
            <label for="room_number">Room Number</label>
            <select name="room_number" id="room_number" required>
                <option value="">Select Room</option>
                <?php
                $room_query = "SELECT * FROM rooms";
                $result = mysqli_query($conn, $room_query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$row['room_number']}'>{$row['room_number']}</option>";
                }
                ?>
            </select>

                 <!-- REGISTRATION -->
            <label for="registration">Student Registration?</label>
            <select name="registration" id="registration" required>
            <option value="">Select</option>
            <option>No</option>
            <option>Yes</option>
            </select>

            <!-- Event Details -->
            <label for="event_details">Event Details</label>
            <textarea name="event_details" id="event_details" rows="5" placeholder="Enter event details here..." required></textarea>

            <!-- Submit Button -->
            <button type="submit">Check Availability</button>
        </form>
    </div>
</body>
</html>
