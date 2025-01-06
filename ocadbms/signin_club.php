<?php

require_once('DBconnect.php');
session_start();

if (isset($_POST['cid']) && isset($_POST['cpassword'])) {
    $u = mysqli_real_escape_string($conn, $_POST['cid']);
    $p = mysqli_real_escape_string($conn, $_POST['cpassword']);

    $sql = "SELECT `Club ID`, `Password`, `Club Name` FROM clubs WHERE `Club ID` = '$u' AND `Password` = '$p'";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        

        $_SESSION['club_id'] = $row['Club ID'];
        $_SESSION['club_name'] = $row['Club Name'];

        header("Location: room_booking.php");
        exit();
    } else {
        echo "Invalid Club ID or Password.";
    }
}
?>