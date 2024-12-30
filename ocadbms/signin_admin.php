<?php
session_start();
require_once('DBconnect.php');

if (isset($_POST['aid']) && isset($_POST['apassword'])) {
    // Get the values from the form
    $u = mysqli_real_escape_string($conn, $_POST['aid']);
    $p = mysqli_real_escape_string($conn, $_POST['apassword']);

    // Write the query to check if the user ID and password exist in the database
    $sql = "SELECT * FROM admins WHERE `admin_id` = '$u' AND `admin_pass` = '$p'";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Check if a matching row exists
    if (mysqli_num_rows($result)>0) {
        // Fetch the result row
        $row = mysqli_fetch_assoc($result);
        
        // Store user data in session
        $_SESSION['admin_id'] = $row['admin_id'];
        $_SESSION['admin_name'] = $row['admin_name'];

        // Redirect to the booking page or any other page after login
        header("Location: admin_home.php");
        exit();
    } else {
        // Display an error message if login fails
        echo "Invalid Club ID or Password.";
    }
}
?>