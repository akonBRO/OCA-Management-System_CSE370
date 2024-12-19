<?php
// First, connect to the database
require_once('DBconnect.php');

// Check if the input in the form text fields are not empty
if (isset($_POST['cname']) && isset($_POST['caname']) && isset($_POST['cpname']) && isset($_POST['cmail']) && isset($_POST['cid']) && isset($_POST['cmobile']) && isset($_POST['cpassword'])) {
    // Retrieve form data
    $c = $_POST['cname'];         // Club Name
    $a = $_POST['caname'];        // Advisor
    $p = $_POST['cpname'];        // President
    $m = $_POST['cmail'];         // Email
    $i = $_POST['cid'];           // Club ID
    $x = $_POST['cmobile'];       // Mobile
    $s = $_POST['cpassword'];     // Password

    // Set optional fields as NULL during registration
    $desc = null;         // Club Description
    $founded = null;      // Founded Date
    $logo = null;         // Club Logo
    $social = null;       // Social Media Links
    $members = null;      // Number of Members
    $achievements = null; // Achievements

    // Check if the Club ID (cid) already exists in the database
    $check_sql = "SELECT * FROM clubs WHERE `Club ID` = '$i'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // If Club ID already exists, redirect back with a message
        echo "<script>
                alert('User already exists with this Club ID.');
                window.location.href = 'registration_club.html';
              </script>";
    } else {
        // If Club ID does not exist, insert the new record
        $sql = "INSERT INTO clubs (`Club Name`, `Advisor`, `President`, `Email`, `Club ID`, `Mobile`, `Password`, `Club Description`, `Founded Date`, `Club Logo`, `Social Media Links`, `Number of Members`, `Achievements`) 
                VALUES ('$c', '$a', '$p', '$m', '$i', '$x', '$s', NULL, NULL, NULL, NULL, NULL, NULL)";
        
        // Execute the query
        $result = mysqli_query($conn, $sql);

        // Check if the insertion was successful
        if (mysqli_affected_rows($conn)) {
            // Redirect to login page if successful
            header("Location: login_club.html");
        } else {
            // Redirect to registration page if insertion failed
            echo "<script>
                    alert('Insertion failed. Please try again.');
                    window.location.href = 'registration_club.html';
                  </script>";
        }
    }
} else {
    // If required fields are missing, redirect back with an error message
    echo "<script>
            alert('All fields are required. Please fill out the form completely.');
            window.location.href = 'registration_club.html';
          </script>";
}
?>
