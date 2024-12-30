<?php
require_once('DBconnect.php');
session_start(); // Start the session

// Get the Club ID from session
$club_id = (int)$_SESSION['club_id'];

// Query to fetch the club details using Club ID
$query = "SELECT * FROM clubs WHERE `Club ID` = $club_id";

// Execute the query
$result = mysqli_query($conn, $query);

// Check if any data is returned
if ($result && mysqli_num_rows($result) > 0) {
    $club_data = mysqli_fetch_assoc($result); // Fetch the club details
} else {
    echo "No club found with the given Club ID.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Profile</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .profile-container {
            background: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            max-width: 900px;
            width: 100%;
            padding: 20px;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-header img {
            max-width: 150px;
            height: auto;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .profile-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #4CAF50;
            margin-bottom: 5px;
        }

        .profile-header p {
            font-size: 1rem;
            color: #555;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .profile-info div {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f9f9f9;
            padding: 10px 15px;
            border-radius: 5px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .profile-info div label {
            font-weight: 500;
            color: #555;
            width: 40%;
        }

        .profile-info div span {
            font-weight: 700;
            color: #333;
            width: 60%;
            text-align: right;
        }

        .edit-button {
            text-align: center;
            margin-top: 20px;
        }

        .edit-button a {
            text-decoration: none;
            background: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .edit-button a:hover {
            background: #45A049;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-info div {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .profile-info div label,
            .profile-info div span {
                width: 100%;
                text-align: left;
            }

            .profile-header h1 {
                font-size: 1.5rem;
            }
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
        <a href="#">Available Rooms</a>
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


    <div class="profile-container">
        <div class="profile-header">
            <!-- Club Logo -->
            <img src="<?php echo htmlspecialchars($club_data['Club Logo']); ?>" alt="Club Logo">
            <h1><?php echo htmlspecialchars($club_data['Club Name']); ?></h1>
            <p>Welcome to your club profile. Manage your club details here.</p>
        </div>

        <div class="profile-info">
            <div>
                <label>Club Full Name:</label>
                <span><?php echo htmlspecialchars($club_data['fullname']); ?></span>
            </div>
            <div>
                <label>Advisor:</label>
                <span><?php echo htmlspecialchars($club_data['Advisor']); ?></span>
            </div>
            <div>
                <label>President:</label>
                <span><?php echo htmlspecialchars($club_data['President']); ?></span>
            </div>
            <div>
                <label>Club ID:</label>
                <span><?php echo htmlspecialchars($club_data['Club ID']); ?></span>
            </div>
            <div>
                <label>Email:</label>
                <span><?php echo htmlspecialchars($club_data['Email']); ?></span>
            </div>
            <div>
                <label>Mobile:</label>
                <span><?php echo htmlspecialchars($club_data['Mobile']); ?></span>
            </div>
            <div>
                <label>Password:</label>
                <span>********</span> <!-- Hiding the password -->
            </div>
            <div>
                <label>Description:</label>
                <span><?php echo htmlspecialchars($club_data['Club Description']); ?></span>
            </div>
            <div>
                <label>Founded Date:</label>
                <span><?php echo htmlspecialchars($club_data['Founded Date']); ?></span>
            </div>
            <div>
                <label>Number of Members:</label>
                <span><?php echo htmlspecialchars($club_data['Number of Members']); ?></span>
            </div>
            <div>
                <label>Achievements:</label>
                <span><?php echo htmlspecialchars($club_data['Achievements']); ?></span>
            </div>
            <div>
                <label>Social Media Links:</label>
                <span>
                    <a href="<?php echo htmlspecialchars($club_data['Social Media Links']); ?>" target="_blank">Visit</a>
                </span>
            </div>
        </div>

        <div class="edit-button">
            <a href="edit_profile.php">Edit Profile</a>
        </div>
    </div>
</body>
</html>
