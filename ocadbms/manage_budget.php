<?php
session_start();
require_once('DBconnect.php');
if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.html");
    exit();
}

if (!isset($_GET['booking_id'])) {
    echo "Booking ID not provided!";
    exit();
}

$booking_id = mysqli_real_escape_string($conn, $_GET['booking_id']);

// budget items show korbe specific booking id er jonno
$sqlItems = "SELECT * FROM budget_items WHERE booking_id = '$booking_id'";
$resultItems = mysqli_query($conn, $sqlItems);

// budget show korbe specific booking id er jonno
$sqlBudget = "SELECT * FROM budget WHERE booking_id = '$booking_id'";
$resultBudget = mysqli_query($conn, $sqlBudget);
$budgetDetails = mysqli_fetch_assoc($resultBudget);

// Fetch comments 
$sqlComments = "SELECT comments FROM bookings WHERE booking_id = '$booking_id'";
$resultComments = mysqli_query($conn, $sqlComments);
$bookingComments = mysqli_fetch_assoc($resultComments)['comments'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStatus = mysqli_real_escape_string($conn, $_POST['status']);
    $newComments = mysqli_real_escape_string($conn, $_POST['comments']);

    // Update budget table
    $updateBudget = "UPDATE budget SET status = '$newStatus' WHERE booking_id = '$booking_id'";
    mysqli_query($conn, $updateBudget);

    // Update comments in bookings table
    $updateComments = "UPDATE bookings SET comments = '$newComments' WHERE booking_id = '$booking_id'";
    mysqli_query($conn, $updateComments);

    // budget status is 'accepted' hoile update bookings status to 'approved'
    if ($newStatus === 'accepted') {
        $updateBookingStatus = "UPDATE bookings SET status = 'approved' WHERE booking_id = '$booking_id'";
        mysqli_query($conn, $updateBookingStatus);
    }

    echo "<script>alert('Budget details updated successfully!'); window.location.href='manage_bookings.php';</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Budget</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007BFF;
            color: #fff;
        }

        .btn {
            padding: 6px 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .submit-btn {
            background-color: #28a745;
            padding: 10px 15px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #218838;
        }
        .home-button {
        display: inline-block;
        margin: 10px;
        padding: 10px 20px;
        background-color: #007BFF;
        color: white;
        text-decoration: none;
        font-size: 14px;
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
        <h1>Manage Budget</h1>
        

        <!-- Budget Details -->
        <h3>Budget Details</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Event Name</th>
                    <th>Item Category</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($resultItems) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($resultItems)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['event_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['item_category']); ?></td>
                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo $row['unit_price']; ?></td>
                            <td><?php echo $row['total_price']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No budget items found for this booking.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Editable Fields -->
        <form method="POST">
            <h3>Edit Budget</h3>
            <table>
                <tr>
                    <th>Total Budget</th>
                    <td><?php echo $budgetDetails['total_budget']; ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <select name="status">
                            <option value="pending" <?php echo $budgetDetails['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="hold" <?php echo $budgetDetails['status'] === 'hold' ? 'selected' : ''; ?>>Hold</option>
                            <option value="accepted" <?php echo $budgetDetails['status'] === 'accepted' ? 'selected' : ''; ?>>Accepted</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Comments</th>
                    <td><textarea name="comments"><?php echo htmlspecialchars($bookingComments); ?></textarea></td>
                </tr>
            </table>

            <button type="submit" class="submit-btn">Submit</button><a href="manage_bookings.php" class="home-button">Back</a>
        </form>
    </div>
</body>
</html>
