<?php
require_once('DBconnect.php');
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        
        .header {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 1.5rem;
        }

        .header a {
            text-decoration: none;
            color: #fff;
            background: #FF5733;
            padding: 8px 12px;
            border-radius: 5px;
        }

        .header a:hover {
            background: #C70039;
        }

        /* Main Content */
        .container {
            padding: 20px;
        }

        .card {
            background: #fff;
            padding: 20px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            margin-bottom: 10px;
            font-size: 1.25rem;
        }

        @media (max-width: 600px) {
            .header h1 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</h1>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Shob table data er jonno -->
    <div class="container">
        <div class="card">
            <h2>Manage Bookings</h2>
            <p><a href="manage_bookings.php">View and Update Bookings</a></p>
        </div>
        <div class="card">
            <h2>Registered Students</h2>
            <p><a href="view_reg_std.php">View Registered Students</a></p>
        </div>
        <div class="card">
            <h2>Registered Clubs</h2>
            <p><a href="view_reg_clubs.php">View Registered Clubs</a></p>
        </div>
    </div>
</body>
</html>
