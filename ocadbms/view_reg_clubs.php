<?php
session_start();
require_once('DBconnect.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch clubs based on search or all clubs
$search_club_id = isset($_GET['search_club_id']) ? $_GET['search_club_id'] : '';
$search_club_name = isset($_GET['search_club_name']) ? $_GET['search_club_name'] : '';

if (isset($_GET['search'])) {
    $query = "SELECT * FROM clubs WHERE `Club ID` = '$search_club_id' OR `Club Name` = '$search_club_name'";
} else {
    $query = "SELECT * FROM clubs";
}
$clubs = mysqli_query($conn, $query);


// Update club information
if (isset($_POST['update_club'])) {
    $club_id = $_POST['club_id'];
    $club_name = $_POST['club_name'];
    $advisor = $_POST['advisor'];
    $president = $_POST['president'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $description = $_POST['description'];
    $founded_date = $_POST['founded_date'];
    $logo = $_POST['logo'];
    $social_links = $_POST['social_links'];
    $num_members = $_POST['num_members'];
    $achievements = $_POST['achievements'];

    $update_query = "UPDATE clubs SET 
                        `Club Name` = '$club_name',
                        Advisor = '$advisor',
                        President = '$president',
                        Email = '$email',
                        Mobile = '$mobile',
                        Password = '$password',
                        `Club Description` = '$description',
                        `Founded Date` = '$founded_date',
                        `Club Logo` = '$logo',
                        `Social Media Links` = '$social_links',
                        `Number of Members` = '$num_members',
                        Achievements = '$achievements'
                    WHERE `Club ID` = '$club_id'";
    mysqli_query($conn, $update_query);

    header("Location: view_reg_clubs.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Clubs</title>
    <style>
        /* Add your existing styles here */
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
    <h1>Registered Clubs</h1>
    <a href="admin_home.php" class="home-button">Home</a>

    <!-- Search bar -->
    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="search_club_id" placeholder="Search by Club ID or Club Name" value="<?php echo htmlspecialchars($search_club_id); ?>">
            <button type="submit" name="search">Search</button>
        </form>
    </div>

    <table>
        <tr>
            <th>Club ID</th>
            <th>Club Name</th>
            <th>Advisor</th>
            <th>President</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Password</th>
            <th>Description</th>
            <th>Founded Date</th>
            <th>Logo</th>
            <th>Social Links</th>
            <th>Number of Members</th>
            <th>Achievements</th>
            <th>Action</th>
        </tr>
        <?php while ($club = mysqli_fetch_assoc($clubs)) : ?>
            <tr>
                <form action="" method="POST">
                    <td><input type="hidden" name="club_id" value="<?php echo $club['Club ID']; ?>"><?php echo $club['Club ID']; ?></td>
                    <td><input type="text" name="club_name" value="<?php echo $club['Club Name']; ?>"></td>
                    <td><input type="text" name="advisor" value="<?php echo $club['Advisor']; ?>"></td>
                    <td><input type="text" name="president" value="<?php echo $club['President']; ?>"></td>
                    <td><input type="email" name="email" value="<?php echo $club['Email']; ?>"></td>
                    <td><input type="number" name="mobile" value="<?php echo $club['Mobile']; ?>"></td>
                    <td><input type="text" name="password" value="<?php echo $club['Password']; ?>"></td>
                    <td><textarea name="description"><?php echo $club['Club Description']; ?></textarea></td>
                    <td><input type="date" name="founded_date" value="<?php echo $club['Founded Date']; ?>"></td>
                    <td><input type="text" name="logo" value="<?php echo $club['Club Logo']; ?>"></td>
                    <td><textarea name="social_links"><?php echo $club['Social Media Links']; ?></textarea></td>
                    <td><input type="number" name="num_members" value="<?php echo $club['Number of Members']; ?>"></td>
                    <td><textarea name="achievements"><?php echo $club['Achievements']; ?></textarea></td>
                    <td>
                        <button type="submit" name="update_club" class="update-btn">Update</button>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
