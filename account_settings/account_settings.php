<?php
require('/home/infost490f2406/public_html/mysqli_connect.php');
session_start();

$username = $_SESSION['username'] ?? null;
$successMessage = "";
$errorMessage = "";

if ($username) {
    $query = "SELECT User_ID, Password FROM User WHERE Username = '$username'";
    $result = mysqli_query($dbc, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['User_ID'];
        $current_password = $row['Password'];
    } else {
        $errorMessage = "User not found. Please make sure you are logged in.";
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
        if (!empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                $update_password_query = "UPDATE User SET Password = '$hashed_password' WHERE User_ID = '$user_id'";

                if (mysqli_query($dbc, $update_password_query)) {
                    $successMessage = "Password updated successfully!";
                } else {
                    $errorMessage = "Error updating password: " . mysqli_error($dbc);
                }
            } else {
                $errorMessage = "Passwords do not match.";
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_profile'])) {
        echo "<script>
            if (confirm('Are you sure you want to delete your profile?')) {
                window.location.href = 'account_settings.php?confirm_delete=true';
            }
        </script>";
    }

    if (isset($_GET['confirm_delete']) && $_GET['confirm_delete'] == 'true') {
        $delete_user_sorter_query = "DELETE FROM `User-Sorter` WHERE User_ID = '$user_id'";
        $delete_user_query = "DELETE FROM `User` WHERE User_ID = '$user_id'";

        if (mysqli_query($dbc, $delete_user_sorter_query) && mysqli_query($dbc, $delete_user_query)) {
            session_destroy();
            header("Location: ../home/home.php");
            exit();
        } else {
            $errorMessage = "Error deleting profile: " . mysqli_error($dbc);
        }
    }
} else {
    $errorMessage = "No user session found. Please log in.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link rel="stylesheet" href="account_settings.css">
</head>
<body>
<div class="container">
    <h3>Update Password</h3>

    <?php 
    if (!empty($successMessage)) echo '<p id="success-message">'.$successMessage.'</p>';
    if (!empty($errorMessage)) echo '<p id="error-message">'.$errorMessage.'</p>';
    ?>

    <form action="account_settings.php" method="POST">
        <label for="new_password">New Password:</label>
        <input placeholder="optional" type="password" name="new_password" id="new_password" required>

        <label for="confirm_password">Confirm New Password:</label>
        <input placeholder="optional" type="password" name="confirm_password" id="confirm_password" required>

        <button type="submit" name="update_password">Update Password</button>
    </form>

    <br>
    <br>
    <br>
    <br>
    <h3>Delete Profile</h3>
    <form action="account_settings.php" method="POST">
        <button type="submit" name="delete_profile" class="delete-btn">Delete Profile</button>
    </form>
</div>
</body>
</html>
