<?php
// First of all, we need to connect to the database
require_once('DBconnect.php');

// We need to check if the input in the form textfields are not empty
if (isset($_POST['uid']) && isset($_POST['upassword'])) {
    // Write the query to check if this user ID and password exist in the database
    $u = $_POST['uid'];
    $p = $_POST['upassword'];
    $sql = "SELECT uid, upassword FROM users WHERE uid = '$u' AND upassword = '$p'";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Check if it returns a result
    if (mysqli_num_rows($result) != 0) {
        echo "LET HIM ENTER";
        //header("Location: home.php");
    } else {
        echo "Username or Password is wrong";
        //header("Location: index.php");
    }
}
?>
