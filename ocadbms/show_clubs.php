<?php
require_once('DBconnect.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['uname']) || !isset($_SESSION['uid'])) {
    header("Location: index.html");
    exit();
}

// Fetch all clubs
$clubs_query = "SELECT `Club ID`, `Club Name`, `Advisor`, `Club Logo` FROM clubs";
$clubs_result = mysqli_query($conn, $clubs_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Clubs</title>
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
        /* General styling */
        body {
            font-family: 'Roboto', sans-serif;
            background: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 120px;
            font-size: 2rem;
            color: #2c3e50;
            font-weight: 600;
        }

        .club-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            padding: 40px;
        }

        /* Club Card Styling */
        .club-card {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .club-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .club-logo {
            width: 100%;
            height: 250px;
            object-fit: cover;
            filter: brightness(0.8);
            transition: filter 0.3s ease;
        }

        .club-card:hover .club-logo {
            filter: brightness(1);
        }

        .club-info {
            padding: 20px;
            background: #fff;
            color: #2c3e50;
        }

        .club-info h3 {
            font-size: 1.8rem;
            font-weight: 600;
            margin: 10px 0;
        }

        .club-info p {
            font-size: 1rem;
            color: #7f8c8d;
        }

        .see-more-btn {
            margin-top: 15px;
            padding: 12px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .see-more-btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: #ecf0f1;
            color: #2c3e50;
            border-radius: 20px;
            padding: 30px;
            width: 80%;
            max-width: 800px;
            max-height: 80%;
            overflow-y: auto;
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
        }

        .close-btn:hover {
            background: #c0392b;
        }
    </style>

    <script>
        function openModal(clubId) {
            const modal = document.getElementById('club-modal');
            modal.style.display = 'flex';

            // Fetch detailed club info via AJAX
            fetch(`fetch_club_details.php?club_id=${clubId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('modal-content').innerHTML = data;
                });
        }

        function closeModal() {
            const modal = document.getElementById('club-modal');
            modal.style.display = 'none';
        }
    </script>
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
        <a href="show_clubs.php">Clubs</a>
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
    <h1>Registered Clubs</h1>
    <div class="club-container">
        <?php while ($club = mysqli_fetch_assoc($clubs_result)): ?>
            <div class="club-card">
                <img src="<?php echo htmlspecialchars($club['Club Logo']); ?>" alt="Club Logo" class="club-logo">
                <div class="club-info">
                    <h3><?php echo htmlspecialchars($club['Club Name']); ?></h3>
                    <p><strong>Advisor:</strong> <?php echo htmlspecialchars($club['Advisor']); ?></p>
                    <button class="see-more-btn" onclick="openModal(<?php echo $club['Club ID']; ?>)">See More</button>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Modal for club details -->
    <div id="club-modal" class="modal" onclick="closeModal()">
        <div class="modal-content" id="modal-content" onclick="event.stopPropagation()">
            <button class="close-btn" onclick="closeModal()">×</button>
            <p>Loading...</p>
        </div>
    </div>
</body>
</html>
