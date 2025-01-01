<?php
require_once('DBconnect.php');
session_start(); // Start session

// Get the User ID from session
$user_id = (int)$_SESSION['uid'];

// Fetch existing user details
$query = "SELECT * FROM users WHERE uid = $user_id";
$result = mysqli_query($conn, $query);

// Check if user data exists
if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
} else {
    echo "No user found with the given User ID.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uname = mysqli_real_escape_string($conn, $_POST['uname']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $umail = mysqli_real_escape_string($conn, $_POST['umail']);
    $umobile = mysqli_real_escape_string($conn, $_POST['umobile']);
    $ugender = mysqli_real_escape_string($conn, $_POST['ugender']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $social_links = mysqli_real_escape_string($conn, $_POST['social_links']);
    
    // Optional: Update Profile Picture
    if (isset($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['name'] !== '') {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES['profile_pic']['name']);
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file);
        $profile_pic = $target_file;
    } else {
        $profile_pic = $user_data['profile_pic']; // Use existing picture if not updated
    }

    // Update query
    $update_query = "
        UPDATE users 
        SET 
            uname = '$uname',
            dob = '$dob',
            umail = '$umail',
            umobile = '$umobile',
            ugender = '$ugender',
            bio = '$bio',
            address = '$address',
            social_links = '$social_links',
            profile_pic = '$profile_pic',
            otp= NULL,
            status=1
        WHERE uid = $user_id
    ";

    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Profile updated successfully.'); window.location.href='user_profile.php';</script>";
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .edit-container {
            background: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            max-width: 700px;
            width: 100%;
            padding: 20px;
        }

        .edit-container h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.8rem;
            color: #4CAF50;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-group textarea {
            resize: none;
            height: 100px;
        }

        .form-group input[type="file"] {
            border: none;
        }

        .form-group button {
            width: 100%;
            background: #4CAF50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .form-group button:hover {
            background: #45A049;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <h1>Edit Profile</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="uname">Name:</label>
                <input type="text" id="uname" name="uname" value="<?php echo htmlspecialchars($user_data['uname']); ?>" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user_data['dob']); ?>" required>
            </div>
            <div class="form-group">
                <label for="umail">Email:</label>
                <input type="email" id="umail" name="umail" value="<?php echo htmlspecialchars($user_data['umail']); ?>" required>
            </div>
            <div class="form-group">
                <label for="umobile">Mobile:</label>
                <input type="text" id="umobile" name="umobile" value="<?php echo htmlspecialchars($user_data['umobile']); ?>" required>
            </div>
            <div class="form-group">
                <label for="ugender">Gender:</label>
                <input type="text" id="ugender" name="ugender" value="<?php echo htmlspecialchars($user_data['ugender']); ?>" required>
            </div>
            <div class="form-group">
                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio"><?php echo htmlspecialchars($user_data['bio'] ?? 'N/A'); ?></textarea>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user_data['address'] ?? 'N/A'); ?>">
            </div>
            <div class="form-group">
                <label for="social_links">Social Links:</label>
                <textarea id="social_links" name="social_links"><?php echo htmlspecialchars($user_data['social_links'] ?? 'N/A'); ?></textarea>
            </div>
            <div class="form-group">
                <label for="profile_pic">Profile Picture:</label>
                <input type="file" id="profile_pic" name="profile_pic">
            </div>
            <div class="form-group">
                <button type="submit">Update Profile</button>
            </div>
            
        </form>
        <a href="user_profile.php"><button type="submit">Back</button></a>
    </div>
</body>
</html>
