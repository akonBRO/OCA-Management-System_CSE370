<?php
session_start();
require_once('DBconnect.php');
if (isset($_POST['uid']) && isset($_POST['upassword'])) {
    $u = $_POST['uid'];
    $p = $_POST['upassword'];
    $sql = "SELECT * FROM users WHERE uid = '$u' AND upassword = '$p' AND status = 1";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) != 0) {
        $user_data = mysqli_fetch_assoc($result);

        $_SESSION['uname'] = $user_data['uname'];
        $_SESSION['uid'] = $user_data['uid'];
        $_SESSION['umail'] = $user_data['umail'];
        $_SESSION['umobile'] = $user_data['umobile'];

        header("Location: home.php");
    } else {
        echo "<script>
                alert('Invalid Username, Password, or Account Restricted');
                window.location.href = 'login_user.html';
              </script>";
    }
}
?>
