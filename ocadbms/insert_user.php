<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connect to the database
require_once('DBconnect.php');

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the OTP field is set
if (isset($_POST['otp'])) {
    // Retrieve the combined OTP
    $otp = $_POST['otp'];

    // Query to check if the OTP exists in the database
    $check_query = "SELECT * FROM users WHERE otp='$otp'";
    $check_result = mysqli_query($conn, $check_query);

    if (!$check_result) {
        die("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($check_result) > 0) {
        // Update the user's status and reset the OTP
        $sql = "UPDATE users 
                SET updated_at=NOW(), otp=NULL, status=1 
                WHERE otp='$otp'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_affected_rows($conn) > 0) {
            echo "<script>
                    alert('Student Registration Successful!');
                    window.location.href = 'login_user.html';
                  </script>";
        } else {
            echo "<script>
                    alert('Error updating the user.');
                    window.location.href = 'registration_user.html';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Invalid OTP!');
                window.location.href = 'verify.php';
              </script>";
    }
} else {
    echo "<script>
            alert('OTP is required!');
            window.location.href = 'verify.php';
          </script>";
}
?>
