<?php
require_once('DBconnect.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['club_id'])) {
    header("Location: index.html");
    exit();
}

// Fetch current club data
$club_id = $_SESSION['club_id'];
$query = "SELECT * FROM clubs WHERE `Club ID` = '$club_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    $club_data = mysqli_fetch_assoc($result);
} else {
    echo "Error fetching club data.";
    exit();
}

// Handle form submission for updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $club_name = $_POST['club_name'];
    $advisor = $_POST['advisor'];
    $president = $_POST['president'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $description = $_POST['description'];
    $facebook = $_POST['facebook'];
    $founded_date = $_POST['founded_date'];
    $members = $_POST['members'];
    $achievements = $_POST['achievements'];

    // Handle logo upload
    if (isset($_FILES['club_logo']) && $_FILES['club_logo']['error'] == 0) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES['club_logo']['name']);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type and size
        if (in_array($file_type, ['jpg', 'jpeg', 'png', 'gif']) && $_FILES['club_logo']['size'] <= 2000000) {
            if (move_uploaded_file($_FILES['club_logo']['tmp_name'], $target_file)) {
                $logo_path = $target_file;
            } else {
                echo "Error uploading the logo.";
                exit();
            }
        } else {
            echo "Invalid file type or size for the logo.";
            exit();
        }
    } else {
        $logo_path = $club_data['Logo']; // Keep the existing logo if not updated
    }

    $update_query = "UPDATE clubs SET 
        `Club Name` = '$club_name', 
        `Advisor` = '$advisor', 
        `President` = '$president', 
        `Email` = '$email', 
        `Mobile` = '$mobile', 
        `Club Description` = '$description', 
        `Social Media Links` = '$facebook',  
        `Founded Date` = '$founded_date',
        `Number of Members` = '$members',
        `Achievements` = '$achievements',
        `Club Logo` = '$logo_path'
        WHERE `Club ID` = '$club_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: club_profile.php"); // Redirect to profile page
        exit();
    } else {
        echo "Error updating club data.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Club Profile</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        .container {
            background: #fff;
            max-width: 600px;
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px 30px;
        }

        h1 {
            text-align: center;
            color: #4CAF50;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: 500;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="url"],
        input[type="file"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s ease;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        input:focus,
        textarea:focus {
            border-color: #4CAF50;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45A049;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            h1 {
                font-size: 1.5rem;
            }

            button {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Edit Club Profile</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="club_name">Club Name:</label>
            <input type="text" id="club_name" name="club_name" value="<?php echo htmlspecialchars($club_data['Club Name']); ?>" required>

            <label for="advisor">Advisor:</label>
            <input type="text" id="advisor" name="advisor" value="<?php echo htmlspecialchars($club_data['Advisor']); ?>" required>

            <label for="president">President:</label>
            <input type="text" id="president" name="president" value="<?php echo htmlspecialchars($club_data['President']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($club_data['Email']); ?>" required>

            <label for="mobile">Mobile:</label>
            <input type="text" id="mobile" name="mobile" value="<?php echo htmlspecialchars($club_data['Mobile']); ?>" required>

            <label for="founded_date">Founded Date:</label>
            <input type="date" id="founded_date" name="founded_date" value="<?php echo htmlspecialchars($club_data['Founded Date']); ?>">

            <label for="members">Number of Members:</label>
            <input type="text" id="members" name="members" value="<?php echo htmlspecialchars($club_data['Number of Members']); ?>">

            <label for="achievements">Achievements:</label>
            <textarea id="achievements" name="achievements"><?php echo htmlspecialchars($club_data['Achievements'] ?? ''); ?></textarea>

            <label for="description">Description:</label>
            <textarea id="description" name="description"><?php echo htmlspecialchars($club_data['Description'] ?? ''); ?></textarea>

            <label for="facebook">Facebook:</label>
            <input type="url" id="facebook" name="facebook" value="<?php echo htmlspecialchars($club_data['Facebook'] ?? ''); ?>">


            <label for="club_logo">Club Logo:</label>
            <input type="file" id="club_logo" name="club_logo">

            <button type="submit">Save Changes</button>
        </form>
    </div>

</body>
</html>
