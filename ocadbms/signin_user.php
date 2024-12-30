<?php
// Start the session
session_start();

// Connect to the database
@include 'DBconnect.php';

// Check if the input fields are set
if (isset($_POST['uid']) && isset($_POST['upassword'])) {
    // Get the user input
    $u = $_POST['uid'];
    $p = $_POST['upassword'];

    // Query to check if the user exists
    $sql = "SELECT * FROM users WHERE uid = '$u' AND upassword = '$p'";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Check if the user exists
    if (mysqli_num_rows($result) != 0) {
        // Fetch the user data
        $user_data = mysqli_fetch_assoc($result);

        // Store the user data in the session, including uid
        $_SESSION['uname'] = $user_data['uname'];
        $_SESSION['uid'] = $user_data['uid'];
        $_SESSION['umail'] = $user_data['umail'];
        $_SESSION['umobile'] = $user_data['umobile']; // Explicitly store uid

        // Redirect to the home page or dashboard
        header("Location: home.php");
    } else {
        // Show an error message for invalid credentials
        echo "<script>
                alert('Username or Password is wrong');
                window.location.href = 'login_user.html';
              </script>";
    }
}
?>
