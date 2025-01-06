<?php
session_start();
require_once('DBconnect.php');

if (isset($_POST['aid']) && isset($_POST['apassword'])) {
    
    $u = mysqli_real_escape_string($conn, $_POST['aid']);
    $p = mysqli_real_escape_string($conn, $_POST['apassword']);


    $sql = "SELECT * FROM admins WHERE `admin_id` = '$u' AND `admin_pass` = '$p'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result)>0) {

        $row = mysqli_fetch_assoc($result);
        
        $_SESSION['admin_id'] = $row['admin_id'];
        $_SESSION['admin_name'] = $row['admin_name'];

        header("Location: admin_home.php");
        exit();
    } else {
        // Display an error message if login fails
        echo "Invalid Club ID or Password.";
    }
}
?>