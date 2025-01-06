<?php
require_once('DBconnect.php');
session_start();
if (!isset($_SESSION['club_id']) || !isset($_SESSION['club_name'])) {
    header("Location: index.html");
    exit();
}

if (!isset($_GET['booking_id'])) {
    echo "Invalid Request!";
    exit();
}

$booking_id = intval($_GET['booking_id']);

// Fetch booking details
$query = "SELECT * FROM bookings WHERE booking_id = $booking_id AND (status = 'budget' OR status = 'approved')";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "Booking not found or not approved.";
    exit();
}
$booking = mysqli_fetch_assoc($result);
$event_name = $booking['event_name'];
$club_name = $booking['club_name'];

// current budget status
$budget_status_query = "SELECT status FROM budget WHERE booking_id = $booking_id";
$budget_status_result = mysqli_query($conn, $budget_status_query);

if (mysqli_num_rows($budget_status_result) > 0) {
    $budget_status = mysqli_fetch_assoc($budget_status_result)['status'];
} else {
    $budget_status = 'pending';
}

// Handle form submission for adding items
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item']) && $budget_status === 'pending') {
    $item_category = mysqli_real_escape_string($conn, $_POST['item_category']);
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $quantity = intval($_POST['quantity']);
    $unit_price = floatval($_POST['unit_price']);
    $total_price = $quantity * $unit_price;

    $insert_query = "INSERT INTO budget_items (booking_id, event_name, item_category, item_name, quantity, unit_price, total_price) 
                     VALUES ($booking_id, '$event_name', '$item_category', '$item_name', $quantity, $unit_price, $total_price)";

    if (!mysqli_query($conn, $insert_query)) {
        echo "Error: " . mysqli_error($conn);
    }
}

// Handle budget confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_budget']) && $budget_status === 'pending') {
    // sum total budget
    $total_budget_query = "SELECT SUM(total_price) AS total FROM budget_items WHERE booking_id = $booking_id";
    $total_budget_result = mysqli_query($conn, $total_budget_query);
    $total_budget = mysqli_fetch_assoc($total_budget_result)['total'];

    // already exist thakle
    $check_budget_query = "SELECT * FROM budget WHERE booking_id = $booking_id";
    $check_budget_result = mysqli_query($conn, $check_budget_query);

    if (mysqli_num_rows($check_budget_result) > 0) {
        // Update the existing row in the budget table
        $update_budget_query = "UPDATE budget 
                                SET total_budget = $total_budget, status = 'hold' 
                                WHERE booking_id = $booking_id";
        if (mysqli_query($conn, $update_budget_query)) {
            // error er jonno
            $update_status_query = "UPDATE bookings SET status = 'budget' WHERE booking_id = $booking_id";
            mysqli_query($conn, $update_status_query);
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // Insert a new row in the budget table if it doesn't exist
        $insert_budget_query = "INSERT INTO budget (booking_id, club_name, total_budget, status) 
                                VALUES ($booking_id, '$club_name', $total_budget, 'hold')";
        if (mysqli_query($conn, $insert_budget_query)) {
            // error er jonno
            $update_status_query = "UPDATE bookings SET status = 'budget' WHERE booking_id = $booking_id";
            mysqli_query($conn, $update_status_query);
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Handle item deletion
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_item']) && $budget_status === 'pending') {
    $item_id = intval($_GET['delete_item']);
    
    // Delete the item from the budget_items table
    $delete_query = "DELETE FROM budget_items WHERE id = $item_id AND booking_id = $booking_id";
    
    if (mysqli_query($conn, $delete_query)) {
        echo "Item deleted successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// item update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_item']) && $budget_status === 'pending') {
    $item_id = intval($_POST['item_id']);
    $quantity = intval($_POST['quantity']);
    $unit_price = floatval($_POST['unit_price']);
    $total_price = $quantity * $unit_price;

    $update_query = "UPDATE budget_items 
                     SET quantity = $quantity, unit_price = $unit_price, total_price = $total_price 
                     WHERE id = $item_id AND booking_id = $booking_id";
    
    if (mysqli_query($conn, $update_query)) {
        echo "Item updated successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch budget items for this booking
$items_query = "SELECT * FROM budget_items WHERE booking_id = $booking_id";
$items_result = mysqli_query($conn, $items_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Execute Budget</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f7fc;
    color: #333;
    margin: 0;
    padding: 0;
}

h1, h3 {
    color: #2c3e50;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

h1 {
    text-align: center;
    font-size: 2.5rem;
    margin-bottom: 20px;
}

h3 {
    font-size: 1.2rem;
    margin-bottom: 15px;
}

.add-item-form {
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 30px;
}

.add-item-form input,
.add-item-form select,
.add-item-form button {
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 100%;
    font-size: 1rem;
}

.add-item-form button {
    background-color: #3498db;
    color: white;
    cursor: pointer;
    border: none;
}

.add-item-form button:hover {
    background-color: #2980b9;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
    background-color: #ffffff;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

table th, table td {
    padding: 12px;
    text-align: center;
}

table th {
    background-color: #2c3e50;
    color: white;
}

table tr:nth-child(even) {
    background-color: #f9f9f9;
}

table tr:hover {
    background-color: #f1f1f1;
}


.back-button {
    display: block;
    width: 200px;
    margin: 30px auto;
    padding: 12px 20px;
    background-color: #2ecc71;
    color: white;
    text-align: center;
    font-size: 1.1rem;
    border-radius: 5px;
    text-decoration: none;
    border: none;
}

.back-button:hover {
    background-color: #27ae60;
}

form button[type="submit"] {
    background-color: #27ae60;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    width: 100%;
    margin-top: 20px;
}

form button[type="submit"]:hover {
    background-color: #2ecc71; 
}


.delete-btn {
    color: #e74c3c;
    font-size: 1.1rem;
    cursor: pointer;
    background: none;
    border: none;
}

.delete-btn:hover {
    text-decoration: underline;
}

.edit-btn {
    color: #3498db;
    font-size: 1.1rem;
    cursor: pointer;
    background: none;
    border: none;
}

.edit-btn:hover {
    text-decoration: underline;
}


.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 600px;
    border-radius: 8px;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
    </style>
</head>
<body>
    <div class="container">
        <h1>Execute Budget for Event: <?php echo htmlspecialchars($event_name); ?></h1>
        <h3>Booking ID: <?php echo $booking_id; ?></h3>
        <h3>Budget Status: <?php echo ucfirst($budget_status); ?></h3> 

        <?php if ($budget_status === 'pending') : ?>
            <form class="add-item-form" action="" method="POST">
                <h3>Add Budget Item</h3>
                <label for="item_category">Category:</label>
                <select name="item_category" id="item_category" required>
                    <option value="Logistics">Logistics</option>
                    <option value="Guest Food">Guest Food</option>
                    <option value="Visitor Food">Visitor Food</option>
                    <option value="Transport Cost">Transport Cost</option>
                    <option value="Miscellaneous">Miscellaneous</option>
                </select>
                
                <label for="item_name">Item Name:</label>
                <input type="text" name="item_name" id="item_name" required>

                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" required>

                <label for="unit_price">Unit Price:</label>
                <input type="number" step="0.01" name="unit_price" id="unit_price" required>

                <button type="submit" name="add_item">Add Item</button>
            </form>
        <?php endif; ?>

        <!-- Budget Items Table -->
        <h3>Budget Items</h3>
        <table>
            <tr>
                <th>Category</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
                <?php if ($budget_status === 'pending') : ?>
                    <th>Action</th>
                <?php endif; ?>
            </tr>

            <?php while ($item = mysqli_fetch_assoc($items_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['item_category']); ?></td>
                    <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo $item['unit_price']; ?></td>
                    <td><?php echo $item['total_price']; ?></td>
                    <?php if ($budget_status === 'pending') : ?>
                        <td>
                            <button class="edit-btn" onclick="openEditModal(<?php echo $item['id']; ?>, '<?php echo $item['item_name']; ?>', <?php echo $item['quantity']; ?>, <?php echo $item['unit_price']; ?>)">Edit</button>
                            <a href="?delete_item=<?php echo $item['id']; ?>&booking_id=<?php echo $booking_id; ?>" class="delete-btn"><button class="delete-btn">Delete</button></a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </table>

        <h3>Total Budget: 
            <?php 
            $total_budget_query = "SELECT SUM(total_price) AS total FROM budget_items WHERE booking_id = $booking_id";
            $total_budget_result = mysqli_query($conn, $total_budget_query);
            $total_budget = mysqli_fetch_assoc($total_budget_result)['total'];
            echo $total_budget ?: 0; 
            ?>
        </h3>

        <!-- Confirm Budget Button -->
        <?php if ($budget_status === 'pending') : ?>
            <form action="" method="POST">
                <button type="submit" name="confirm_budget">
                    Confirm Budget
                </button>
            </form>
        <?php endif; ?>

        <a href="my_bookings.php" class="back-button">Back to My Booking</a>
    </div>

    <!-- Modal for editing item -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h3>Edit Item</h3>
            <form method="POST" id="editForm">
                
                <input type="hidden" name="item_id" id="item_id">
                <label for="item_name">Item Name:</label>
                <input type="text" name="item_name" id="edit_item_name" readonly><br><br>
                <label for="edit_quantity">Quantity:</label>
                <input type="number" name="quantity" id="edit_quantity" required><br><br>
                <label for="edit_unit_price">Unit Price:</label>
                <input type="number" step="0.01" name="unit_price" id="edit_unit_price" required>
                <button type="submit" name="update_item">Update Item</button>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(itemId, itemName, quantity, unitPrice) {
            document.getElementById('item_id').value = itemId;
            document.getElementById('edit_item_name').value = itemName;
            document.getElementById('edit_quantity').value = quantity;
            document.getElementById('edit_unit_price').value = unitPrice;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function deleteItem(itemId) {
            if (confirm("Are you sure you want to delete this item?")) {
                window.location.href = "?delete_item=" + itemId;
            }
        }
    </script>

</body>

</html>
