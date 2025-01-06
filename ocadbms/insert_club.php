<?php

require_once('DBconnect.php');

if (isset($_POST['cname']) && isset($_POST['caname']) && isset($_POST['cpname']) && isset($_POST['cmail']) && isset($_POST['cid']) && isset($_POST['cmobile']) && isset($_POST['cpassword'])) {
    $c = $_POST['cname'];         
    $a = $_POST['caname'];       
    $p = $_POST['cpname'];      
    $m = $_POST['cmail'];         
    $i = $_POST['cid'];          
    $x = $_POST['cmobile'];     
    $s = $_POST['cpassword'];     

    $desc = null;         
    $founded = null;     
    $logo = null;        
    $social = null;      
    $members = null;      
    $achievements = null; 
    $check_sql = "SELECT * FROM clubs WHERE `Club ID` = '$i'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>
                alert('User already exists with this Club ID.');
                window.location.href = 'registration_club.html';
              </script>";
    } else {
        $sql = "INSERT INTO clubs (`Club Name`, `Advisor`, `President`, `Email`, `Club ID`, `Mobile`, `Password`, `Club Description`, `Founded Date`, `Club Logo`, `Social Media Links`, `Number of Members`, `Achievements`) 
                VALUES ('$c', '$a', '$p', '$m', '$i', '$x', '$s', NULL, NULL, NULL, NULL, NULL, NULL, NULL)";
        $result = mysqli_query($conn, $sql);

        if (mysqli_affected_rows($conn)) {
            header("Location: login_club.html");
        } else {
            echo "<script>
                    alert('Insertion failed. Please try again.');
                    window.location.href = 'registration_club.html';
                  </script>";
        }
    }
} else {
    echo "<script>
            alert('All fields are required. Please fill out the form completely.');
            window.location.href = 'registration_club.html';
          </script>";
}
?>
