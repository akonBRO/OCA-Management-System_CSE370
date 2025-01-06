<?php
session_start();
require_once('DBconnect.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.html");
    exit();
}

// searching er jonno
$search_uid = '';
if (isset($_GET['search'])) {
    $search_uid = $_GET['search_uid'];
    $query = "SELECT * FROM users WHERE uid = '$search_uid'";
} else {
    $query = "SELECT * FROM users";
}
$students = mysqli_query($conn, $query);

// Update student information
if (isset($_POST['update_student'])) {
    $uid = $_POST['uid'];
    $uname = $_POST['uname'];
    $dob = $_POST['dob'];
    $umail = $_POST['umail'];
    $umobile = $_POST['umobile'];
    $ugender = $_POST['ugender'];
    $upassword = $_POST['upassword'];
    $profile_pic = $_POST['profile_pic'];
    $bio = $_POST['bio'];
    $address = $_POST['address'];
    $social_links = $_POST['social_links'];
    $otp=$_POST['otp'];
    $status= $_POST['status'];

    $update_query = "UPDATE users SET 
                        uname = '$uname', 
                        dob = '$dob', 
                        umail = '$umail', 
                        umobile = '$umobile', 
                        ugender = '$ugender', 
                        upassword = '$upassword', 
                        profile_pic = '$profile_pic', 
                        bio = '$bio', 
                        address = '$address', 
                        social_links = '$social_links',
                        otp='$otp',
                        status= '$status'
                    WHERE uid = '$uid'";
    mysqli_query($conn, $update_query);

    header("Location: view_reg_std.php"); 
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Students</title>
    <style>
        body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 20px;
        color: #333;
    }

    h1 {
        text-align: center;
        font-size: 2rem;
        color: #444;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    th {
        background-color: #4CAF50;
        color: white;
        padding: 12px 10px;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.1rem;
    }

    td {
        border: 1px solid #ddd;
        padding: 10px 12px;
        font-size: 0.95rem;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    input[type="text"],
    input[type="email"],
    input[type="number"],
    input[type="date"],
    textarea,
    select {
        width: 100%;
        padding: 8px 10px;
        margin: 5px 0;
        font-size: 0.9rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        transition: 0.2s;
    }

    input:focus,
    textarea:focus,
    select:focus {
        outline: none;
        border-color: #4CAF50;
        box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
    }

    .update-btn {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        font-size: 0.9rem;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .update-btn:hover {
        background-color: #45a049;
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    }
    .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-bar input[type="text"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .search-bar button {
            padding: 10px 16px;
            margin-left: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }

    @media (max-width: 768px) {
        .search-bar input[type="text"] {
                width: 70%;
            }

            .search-bar button {
                padding: 8px 10px;
            }

        table {
            font-size: 0.8rem;
        }

        th, td {
            padding: 8px;
        }

        .update-btn {
            padding: 8px 10px;
            font-size: 0.8rem;
        }
    }
    .home-button {
        display: inline-block;
        margin: 10px;
        padding: 10px 20px;
        background-color: #007BFF;
        color: white;
        text-decoration: none;
        font-size: 16px;
        border-radius: 5px;
        font-weight: bold;
        text-align: center;
        transition: background-color 0.3s;
    }

    .home-button:hover {
        background-color: #0056b3;
    }

    </style>
</head>
<body>
    <h1>Registered Students</h1>
    <a href="admin_home.php" class="home-button">Home</a>

    <!-- Search bar -->
    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="search_uid" placeholder="Search by UID" value="<?php echo htmlspecialchars($search_uid); ?>">
            <button type="submit" name="search">Search</button>
        </form>
    </div>

    <table>
        <tr>
            <th>UID</th>
            <th>Username</th>
            <th>Date of Birth</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Gender</th>
            <th>Password</th>
            <th>Profile Pic</th>
            <th>Bio</th>
            <th>Address</th>
            <th>Social Links</th>
            <th>OTP</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($student = mysqli_fetch_assoc($students)) : ?>
            <tr>
                <form action="" method="POST">
                    <td><input type="hidden" name="uid" value="<?php echo $student['uid']; ?>"><?php echo $student['uid']; ?></td>
                    <td><input type="text" name="uname" value="<?php echo $student['uname']; ?>"></td>
                    <td><input type="date" name="dob" value="<?php echo $student['dob']; ?>"></td>
                    <td><input type="email" name="umail" value="<?php echo $student['umail']; ?>"></td>
                    <td><input type="number" name="umobile" value="<?php echo $student['umobile']; ?>"></td>
                    <td>
                        <select name="ugender">
                            <option value="Male" <?php if ($student['ugender'] === 'Male') echo 'selected'; ?>>Male</option>
                            <option value="Female" <?php if ($student['ugender'] === 'Female') echo 'selected'; ?>>Female</option>
                            <option value="Other" <?php if ($student['ugender'] === 'Other') echo 'selected'; ?>>Other</option>
                        </select>
                    </td>
                    <td><input type="text" name="upassword" value="<?php echo $student['upassword']; ?>"></td>
                    <td><input type="text" name="profile_pic" value="<?php echo $student['profile_pic']; ?>"></td>
                    <td><textarea name="bio"><?php echo $student['bio']; ?></textarea></td>
                    <td><input type="text" name="address" value="<?php echo $student['address']; ?>"></td>
                    <td><textarea name="social_links"><?php echo $student['social_links']; ?></textarea></td>
                    <td><textarea name="otp"><?php echo $student['otp']; ?></textarea></td>
                    <td><textarea name="status"><?php echo $student['status']; ?></textarea></td>
                    <td>
                        <button type="submit" name="update_student" class="update-btn">Update</button>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
