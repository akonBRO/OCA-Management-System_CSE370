<?php
// first of all, we need to connect to the database
require_once('DBconnect.php');

// we need to check if the input in the form textfields are not empty
if(isset($_POST['uname']) && isset($_POST['dob']) && isset($_POST['umail']) && isset($_POST['uid']) && isset($_POST['umobile']) && isset($_POST['ugender']) && isset($_POST['upassword'])){
	// write the query to check if this username and password exists in our database
	$n = $_POST['uname'];
	$d = $_POST['dob'];
	$m = $_POST['umail'];
	$i = $_POST['uid'];
	$p = $_POST['umobile'];
	$g = $_POST['ugender'];
	$s = $_POST['upassword'];
	
	$sql = " INSERT INTO users VALUES( '$n', '$d', '$m', '$i', '$p', '$g', '$s' ) ";
	
	//Execute the query 
	$result = mysqli_query($conn, $sql);
	
	//check if this insertion is happening in the database
	if(mysqli_affected_rows($conn)){
	
		//echo "Inseted Successfully";
		header("Location: login_user.php");
	}
	else{
		//echo "Insertion Failed";
		header("Location: registration_user.php");
	}
	
}


?>