<?php
// First, connect to the database
require_once('DBconnect.php');
include('email.php');

if (isset($_POST['uname']) && isset($_POST['dob']) && isset($_POST['umail']) && isset($_POST['uid']) && isset($_POST['umobile']) && isset($_POST['ugender']) && isset($_POST['upassword'])) {
    // Retrieve input values from the form
    $n = $_POST['uname'];
    $d = $_POST['dob'];
    $m = $_POST['umail'];
    $i = $_POST['uid'];
    $p = $_POST['umobile'];
    $g = $_POST['ugender'];
    $s = $_POST['upassword'];

    // Check if the uid or email already exists in the database
    $check_query = "SELECT * FROM users WHERE uid = '$i' OR umail='$m'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // If the uid exists, show a message and redirect
        echo "<script>
                alert('User already exists with this Student ID or E-mail!');
                window.location.href = 'registration_user.html';
              </script>";
    } else {
        // If the uid does not exist, insert the new user
        $otp = rand(11111, 99999); // Generate OTP
        send_otp($m, 'OTP FOR STUDENT REGISTRATION', $otp); // Send OTP via email

        // Correctly quote string values in the SQL query
        $sql = "INSERT INTO users VALUES ('$n', '$d', '$m', '$i', '$p', '$g', '$s', Null, Null, Null, Null, NOW(), '$otp', 0)";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('OTP Sent Successfully! Please check your E-mail.');
                    window.location.href = 'verify.php';
                  </script>";
        } else {
            echo "Error: " . mysqli_error($conn); // Output error for debugging
        }
    }
} else {
    echo "Required fields are missing!";
}
?>
