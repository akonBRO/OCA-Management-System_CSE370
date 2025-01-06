<?php
require_once('DBconnect.php');
session_start();

if (!isset($_SESSION['club_id']) || !isset($_SESSION['club_name'])) {
    header("Location: index.html");
    exit();
}

// audience allow korle query run korbe
$query = "SELECT * 
          FROM bookings 
          WHERE status = 'approved' 
            AND std_reg = 'Yes' 
            AND club_name = '{$_SESSION['club_name']}' ";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audience Page</title>
    <link rel="stylesheet" href="styles.css"> 
    <style>
      
body {
    font-family: 'Poppins', sans-serif;
    background: #f8f9fd;
    margin: 0;
    padding: 0;
    color: #333;
}

.container {
    margin: 120px auto;
    max-width: 1100px;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    padding: 20px;
}

h1 {
    background: white;
    color: #333;
    padding: 25px;
    text-align: center;
    font-size: 2rem;
    font-weight: 600;
    letter-spacing: 2px;
    border-radius: 20px 20px 0 0;
    text-transform: uppercase;
}


table {
    width: 100%;
    border-spacing: 0;
    margin: 20px 0;
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
}

thead {
    background: #4CAF50;
    color: white;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.8px;
}

thead th {
    padding: 15px;
    font-weight: 600;
    text-align: center;
}

tbody tr {
    background: #ffffff;
    border-bottom: 2px solid #f0f0f5;
    transition: background-color 0.3s ease;
}

tbody tr:hover {
    background: #f9f9ff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

td {
    padding: 15px;
    text-align: center;
    font-size: 0.95rem;
    color: #555;
}

td:first-child {
    border-top-left-radius: 12px;
    border-bottom-left-radius: 12px;
}

td:last-child {
    border-top-right-radius: 12px;
    border-bottom-right-radius: 12px;
}

th:first-child,
td:first-child {
    text-align: left;
    padding-left: 20px;
}

th:last-child,
td:last-child {
    text-align: right;
    padding-right: 20px;
}


.btn {
    background: #6a11cb;
    color: white;
    padding: 10px 20px;
    font-size: 0.9rem;
    font-weight: 500;
    text-transform: uppercase;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.btn:hover {
    background: #2575fc;
    transform: translateY(-2px);
}


.floating-page {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 70%;
    max-height: 80%;
    overflow-y: auto;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
    z-index: 1000;
    display: none;
    animation: fadeIn 0.5s ease;
    padding: 25px;
}

.floating-page.active {
    display: block;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translate(-50%, -60%);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%);
    }
}

.floating-page h2 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
    text-align: center;
    text-transform: uppercase;
}

.close-btn {
    background: #ff5e5e;
    color: white;
    padding: 10px 15px;
    border: none;
    font-size: 0.85rem;
    font-weight: bold;
    border-radius: 10px;
    cursor: pointer;
    position: absolute;
    top: 15px;
    right: 15px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.close-btn:hover {
    background: #d94c4c;
    transform: scale(1.1);
}

.floating-page table {
    width: 100%;
    border-collapse: collapse;
    background: #f7f7fb;
    border-radius: 10px;
}

.floating-page th, .floating-page td {
    padding: 12px 15px;
    text-align: center;
    font-size: 0.95rem;
}

.floating-page th {
    background: #2575fc;
    color: white;
    font-weight: 600;
    text-transform: uppercase;
}

.floating-page tr:nth-child(odd) {
    background: #ffffff;
}

.floating-page tr:nth-child(even) {
    background: #f0f0f5;
}


p {
    font-size: 1.2rem;
    font-weight: 500;
    color: #555;
    margin-top: 15px;
    text-align: center;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #2c3e50; 
    color: white;
    padding: 5px 30px;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
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
    color: #f1f1f1;
    text-decoration: underline;
}
.header .user-info a:hover {
    color: #76c7c0;
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

    .header .right-bar {
        width: 80px;
        margin-left: 0;
    }
}

    </style>
    <script>
        //floating page er jonno
        function showAudience(bookingId) {
            const floatingPage = document.getElementById('floating-page');
            const audienceTable = document.getElementById('audience-table-body');
            const totalAudience = document.getElementById('total-audience');

            fetch(`fetch_audience.php?booking_id=${bookingId}`)
                .then(response => response.json())
                .then(data => {
                    audienceTable.innerHTML = data.audienceList.map((audience, index) => `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${audience.uname}</td>
                            <td>${audience.uid}</td>
                            <td>${audience.number}</td>
                            <td>${audience.email}</td>
                        </tr>
                    `).join('');
                    totalAudience.innerText = data.totalAudience;

                    floatingPage.classList.add('active');
                });
        }

        function closeFloatingPage() {
            document.getElementById('floating-page').classList.remove('active');
        }
    </script>
</head>
<body>

<div class="header">

    <div class="website-logo">
        <img src="images/oca.jpg" alt="Website Logo">
    </div>

    <nav>
        <a href="room_booking.php">Home</a>
        <a href="my_bookings.php">My Bookings</a>
        <a href="audience.php">Audience</a>
        <a href="#">Contact</a>
        <a href="logout.php">Logout</a>
    </nav>

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
<div class="container">
    <h1>Audience</h1>
    <table>
        <thead>
        <tr>
            <th>Serial No</th>
            <th>Event Name</th>
            <th>Date</th>
            <th>Time</th>
            <th>Room</th>
            <th>Audience</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            $serial = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$serial}</td>
                        <td>{$row['event_name']}</td>
                        <td>{$row['event_date']}</td>
                        <td>{$row['time_slot']}</td>
                        <td>{$row['room_number']}</td>
                        <td><button class='btn' onclick='showAudience({$row['booking_id']})'>Show</button></td>
                      </tr>";
                $serial++;
            }
        } else {
            echo "<tr><td colspan='6' style='text-align: center; font-weight: bold;'>No events found with audience registration.</td></tr>";

        }
        ?>
        </tbody>
    </table>
</div>

<div id="floating-page" class="floating-page">
    <button class="close-btn" onclick="closeFloatingPage()">Close</button>
    <h2>Audience Details</h2>
    <table>
        <thead>
        <tr>
            <th>Serial No</th>
            <th>Name</th>
            <th>ID</th>
            <th>Contact</th>
            <th>Email</th>
        </tr>
        </thead>
        <tbody id="audience-table-body"></tbody>
    </table>
    <p>Total Audience: <span id="total-audience">0</span></p>
</div>
</body>
</html>
