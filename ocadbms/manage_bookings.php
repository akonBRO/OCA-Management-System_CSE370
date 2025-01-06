<?php
session_start();
require_once('DBconnect.php');
if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.html");
    exit();
}

$searchQuery = "";
if (isset($_POST['search'])) {
    $searchQuery = mysqli_real_escape_string($conn, $_POST['search_query']);
    $searchSQL = "WHERE club_name LIKE '%$searchQuery%' OR booking_id LIKE '%$searchQuery%'";
} else {
    $searchSQL = "";
}

//query chalabe search er jonno
$sql = "SELECT * FROM bookings $searchSQL";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 90%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-bar input[type="text"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .search-bar button {
            padding: 10px 16px;
            margin-left: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            text-align: left;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
            word-wrap: break-word;
        }

        th {
            background-color: #007BFF;
            color: white;
            position: sticky;
            top: 0;
        }

        td {
            background-color: #f9f9f9;
        }

        td input[type="text"], td input[type="date"], td select, td textarea {
            width: 95%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        td textarea {
            resize: vertical;
            height: 50px;
        }

        td a.btn, td button {
            padding: 8px 12px;
            font-size: 14px;
            text-decoration: none;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: inline-block;
        }

        td a.btn {
            background-color: #007BFF;
        }

        td a.btn:hover {
            background-color: #0056b3;
        }

        td button.submit-btn {
            background-color: #28a745;
        }

        td button.submit-btn:hover {
            background-color: #218838;
        }

        @media (max-width: 768px) {
            th, td {
                font-size: 12px;
                padding: 8px 10px;
            }

            .search-bar input[type="text"] {
                width: 70%;
            }

            .search-bar button {
                padding: 8px 10px;
            }

            td input[type="text"], td input[type="date"], td select, td textarea {
                width: 90%;
            }
        }
        
    .home-button {
        display: inline-block;
        margin: 10px;
        padding: 10px 20px;
        background-color: #007BFF;
        color: white;
        text-decoration: none;
        font-size: 16px;
        border-radius: 5px;
        font-weight: bold;
        text-align: center;
        transition: background-color 0.3s;
    }

    .home-button:hover {
        background-color: #0056b3;
    }


    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Bookings</h1>
        
    <a href="admin_home.php" class="home-button">Home</a>
        <!-- Searching-->
        <form method="POST" class="search-bar">
            <input type="text" name="search_query" placeholder="Search by Club Name or Booking ID" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit" name="search">Search</button>
        </form>

        <!--Table shuru-->
        <form method="POST" action="update_bookings.php">
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Event Name</th>
                        <th>Event Date</th>
                        <th>Time Slot</th>
                        <th>Room Number</th>
                        <th>Event Details</th>
                        <th>Club Name</th>
                        <th>Status</th>
                        <th>Comments</th>
                        <th>Show Budget</th>
                        <th>Submit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['booking_id']; ?></td>
                                <td><input type="text" name="event_name[<?php echo $row['booking_id']; ?>]" value="<?php echo htmlspecialchars($row['event_name']); ?>" readonly></td>
                                <td><input type="date" name="event_date[<?php echo $row['booking_id']; ?>]" value="<?php echo $row['event_date']; ?>" readonly></td>
                                <td><input type="text" name="time_slot[<?php echo $row['booking_id']; ?>]" value="<?php echo htmlspecialchars($row['time_slot']); ?>" readonly></td>
                                <td><input type="text" name="room_number[<?php echo $row['booking_id']; ?>]" value="<?php echo htmlspecialchars($row['room_number']); ?>" readonly></td>
                                <td><textarea name="event_details[<?php echo $row['booking_id']; ?>]"><?php echo htmlspecialchars($row['event_details']); ?></textarea></td>
                                <td><?php echo htmlspecialchars($row['club_name']); ?></td>
                                <td>
                                    <select name="status[<?php echo $row['booking_id']; ?>]">
                                        <option value="pending" <?php echo $row['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="approved" <?php echo $row['status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                                        <option value="rejected" <?php echo $row['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                        <option value="budget" <?php echo $row['status'] === 'budget' ? 'selected' : ''; ?>>Budget</option>
                                    </select>
                                </td>
                                <td><input type="text" name="comments[<?php echo $row['booking_id']; ?>]" value="<?php echo htmlspecialchars($row['comments']); ?>"></td>
                                <td>
                                    <?php
                                    // budget or budget_item table e booking_id match korle show korbe
                                    $bookingId = $row['booking_id'];
                                    $checkBudget = "SELECT 1 FROM budget WHERE booking_id = '$bookingId' LIMIT 1";
                                    $checkBudgetItems = "SELECT 1 FROM budget_items WHERE booking_id = '$bookingId' LIMIT 1";
                                    $budgetExists = mysqli_query($conn, $checkBudget);
                                    $budgetItemsExists = mysqli_query($conn, $checkBudgetItems);

                                    if (mysqli_num_rows($budgetExists) > 0 || mysqli_num_rows($budgetItemsExists) > 0): ?>
                                        <a href="manage_budget.php?booking_id=<?php echo $bookingId; ?>" class="btn">Budget</a>
                                    <?php else: ?>
                                        <span style="color: gray;">No Budget</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="submit" name="submit[<?php echo $row['booking_id']; ?>]" class="btn submit-btn">Submit</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="11">No bookings found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </form>
    </div>
</body>
</html>
