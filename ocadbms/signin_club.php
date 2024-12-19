<?php
// Start the session to store user data
session_start();

// First of all, we need to connect to the database
require_once('DBconnect.php');

// Check if the form has been submitted
if (isset($_POST['cid']) && isset($_POST['cpassword'])) {
    // Get the values from the form
    $u = mysqli_real_escape_string($conn, $_POST['cid']);
    $p = mysqli_real_escape_string($conn, $_POST['cpassword']);

    // Write the query to check if the user ID and password exist in the database
    $sql = "SELECT `Club ID`, `Password`, `Club Name` FROM clubs WHERE `Club ID` = '$u' AND `Password` = '$p'";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Check if a matching row exists
    if (mysqli_num_rows($result) > 0) {
        // Fetch the result row
        $row = mysqli_fetch_assoc($result);
        
        // Store user data in session
        $_SESSION['club_id'] = $row['Club ID'];
        $_SESSION['club_name'] = $row['Club Name'];

        // Redirect to the booking page or any other page after login
        header("Location: room_booking.php");
        exit();
    } else {
        // Display an error message if login fails
        echo "Invalid Club ID or Password.";
    }
}
?>