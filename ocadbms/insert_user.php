<?php
// First, connect to the database
require_once('DBconnect.php');

// Check if all the input fields in the form are set
if (isset($_POST['uname']) && isset($_POST['dob']) && isset($_POST['umail']) && isset($_POST['uid']) && isset($_POST['umobile']) && isset($_POST['ugender']) && isset($_POST['upassword'])) {
    // Retrieve input values from the form
    $n = $_POST['uname'];
    $d = $_POST['dob'];
    $m = $_POST['umail'];
    $i = $_POST['uid'];
    $p = $_POST['umobile'];
    $g = $_POST['ugender'];
    $s = $_POST['upassword'];

    // Check if the uid already exists in the database
    $check_query = "SELECT * FROM users WHERE uid = '$i'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // If the uid exists, show a message and redirect
        echo "<script>
                alert('User already exists with this UID!');
                window.location.href = 'registration_user.php';
              </script>";
    } else {
        // If the uid does not exist, insert the new user
        $sql = "INSERT INTO users VALUES ('$n', '$d', '$m', '$i', '$p', '$g', '$s')";

        // Execute the query
        $result = mysqli_query($conn, $sql);

        // Check if the insertion was successful
        if (mysqli_affected_rows($conn)) {
            header("Location: login_user.html");
        } else {
            header("Location: registration_user.html");
        }
    }
}
?>
