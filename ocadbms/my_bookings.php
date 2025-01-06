<?php
require_once('DBconnect.php');
session_start();
if (!isset($_SESSION['club_id']) || !isset($_SESSION['club_name'])) {
    header("Location: index.html");
    exit();
}
$club_name= $_SESSION['club_name'];

// Delete er query
if (isset($_POST['delete']) && isset($_POST['booking_id'])) {
    $booking_id = intval($_POST['booking_id']);

    $delete_budget_items_query = "DELETE FROM budget_items WHERE booking_id = '$booking_id'";
    $delete_budget_query = "DELETE FROM budget WHERE booking_id = '$booking_id'";
    $delete_registered_std_query = "DELETE FROM registered_std WHERE booking_id = '$booking_id'";
    $delete_booking_query = "DELETE FROM bookings WHERE booking_id = '$booking_id'";
    mysqli_query($conn, $delete_budget_items_query);
    mysqli_query($conn, $delete_budget_query);
    mysqli_query($conn, $delete_registered_std_query);
    mysqli_query($conn, $delete_booking_query);
}

// club er all data fetch
$query = "SELECT * FROM bookings WHERE club_name = '$club_name'";
$result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookings</title>
    <link rel="stylesheet" href="css/styledemo.css"> 
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
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

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

        .container {
            margin-top: 80px;
            padding: 20px;
        }
       
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .container {
    width: 95%; 
    max-width: 1500px;
    background: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    margin: 20px auto;
}


        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 2rem;
            font-weight: 600;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 1rem;
        }

        table th {
            background-color: #4CAF50;
            color: white;
        }

        table td {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .no-bookings {
            font-size: 1.2rem;
            color: #555;
        }

        .status {
            font-weight: bold;
            color: #333;
        }

        .status.pending {
            color: #FF9F00;
        }

        .status.approved {
            color: #4CAF50;
        }

        .status.rejected {
            color: #FF4D4D;
        }

        .status.budget {
            color:rgb(0, 119, 255);
        }

       
        table {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
 .budget-link {
            color: #4CAF50;
            font-weight: bold;
            text-decoration: none;
        }
        .budget-link:hover {
            color: #2e7d32;
            text-decoration: underline;
        }
        .delete-button {
            background-color: #ff4d4d;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #ff3333;
        }
    </style>
    <script>
        function confirmDeletion(bookingId) {
            if (confirm("Are you sure you want to delete this booking?")) {
                // hidden form create korbe deletetion er jonno
                const form = document.createElement("form");
                form.method = "POST";
                form.action = "";
                const bookingIdInput = document.createElement("input");
                bookingIdInput.type = "hidden";
                bookingIdInput.name = "booking_id";
                bookingIdInput.value = bookingId;
                form.appendChild(bookingIdInput);
                const deleteInput = document.createElement("input");
                deleteInput.type = "hidden";
                deleteInput.name = "delete";
                deleteInput.value = "1";
                form.appendChild(deleteInput);
                document.body.appendChild(form);
                form.submit();
            }
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
        <h1>Your Bookings</h1>

        <?php
        // club bookings table
        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>
                    <th>Serial No</th>
                    <th>Event Name</th>
                    <th>Event Date</th>
                    <th>Time Slot</th>
                    <th>Room No</th>
                    <th>Student Reg</th>
                    <th>Event Details</th>
                    <th>Status</th>
                    <th>Comments</th>
                    <th>Budget</th>
                    <th>Action</th>
                  </tr>";
            
            $serial_no = 1;
            // database Loop 
            while ($row = mysqli_fetch_assoc($result)) {
                $status_class = 'status ' . strtolower($row['status']);
                echo "<tr>
                        <td>" . $serial_no . "</td>
                        <td>" . $row['event_name'] . "</td>
                        <td>" . $row['event_date'] . "</td>
                        <td>" . $row['time_slot'] . "</td>
                        <td>" . $row['room_number'] . "</td>
                        <td>" . $row['std_reg'] . "</td>
                        <td>" . $row['event_details'] . "</td>
                        <td class='$status_class'>" . $row['status'] . "</td>
                        <td>" . $row['comments'] . "</td>";

                // Budget Link show korbe
                if (in_array(strtolower($row['status']), ['budget', 'approved'])) {
                    echo "<td><a class='budget-link' href='budget_execute.php?booking_id=" . $row['booking_id'] . "'>Execute Budget</a></td>";
                }
                else {
                    echo "<td>N/A</td>";
                }

                // Add Delete Button
                echo "<td><button class='delete-button' onclick='confirmDeletion(" . $row['booking_id'] . ")'>Delete</button></td>";

                echo "</tr>";
                $serial_no++;
            }
            echo "</table>";
        } else {
            echo "<p class='no-bookings'>No bookings found for your club.</p>";
        }

        // Close the database connection
        mysqli_close($conn);
        ?>
    </div>
</body>
</html>
