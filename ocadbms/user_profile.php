<?php
require_once('DBconnect.php');
session_start(); // Start the session

// Get the User ID from session
$user_id = (int)$_SESSION['uid'];

// Query to fetch the user details using User ID
$query = "SELECT * FROM users WHERE `uid` = $user_id";

// Execute the query
$result = mysqli_query($conn, $query);

// Check if any data is returned
if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result); // Fetch the user details
} else {
    echo "No user found with the given User ID.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <style>
        /* General Reset */
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
            max-width: 120px;
            height: auto;
            border-radius: 30%;
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
        <a href="home.php">Home</a>
        <a href="#">null</a>
        <a href="#">null</a>
        <a href="#">Contact</a>
        <a href="logout.php">Logout</a>
    </nav>

    <!-- Right Bar for Club Logo -->
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
    <div class="profile-container">
        <div class="profile-header">
            <!-- User Photo -->
            <img src="<?php echo htmlspecialchars($user_data['profile_pic']); ?>" alt="User Photo">
            <h1><?php echo htmlspecialchars($user_data['uname']); ?></h1>
            <p>Welcome to your profile. Manage your personal details here.</p>
        </div>

        <div class="profile-info">
            <div>
                <label>Email:</label>
                <span><?php echo htmlspecialchars($user_data['umail']); ?></span>
            </div>
            <div>
                <label>ID:</label>
                <span><?php echo htmlspecialchars($user_data['uid']); ?></span>
            </div>
            <div>
                <label>Date of Birth:</label>
                <span><?php echo htmlspecialchars($user_data['dob']); ?></span>
            </div>
            <div>
                <label>Gender:</label>
                <span><?php echo htmlspecialchars($user_data['ugender']); ?></span>
            </div>
            <div>
                <label>Mobile:</label>
                <span><?php echo htmlspecialchars($user_data['umobile']); ?></span>
            </div>
            <div>
                <label>Password:</label>
                <span>********</span> <!-- Hiding the password -->
            </div>
            <div>
                <label>Address:</label>
                <span><?php echo htmlspecialchars($user_data['address']); ?></span>
            </div>
            <div>
                <label>Bio:</label>
                <span><?php echo htmlspecialchars($user_data['bio']); ?></span>
            </div>
        </div>

        <div class="edit-button">
            <a href="user_edit_profile.php">Edit Profile</a>
        </div>
    </div>
</body>
</html>
