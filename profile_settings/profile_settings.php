<?php
require('/home/infost490f2406/public_html/mysqli_connect.php');
session_start();

$username = $_SESSION['username'] ?? null;
$first_name = "";
$last_name = "";
$successMessage = "";
$errorMessage = "";

if ($username) {
    $query = "SELECT First, Last, User_ID, Password FROM User WHERE Username = '$username'";
    $result = mysqli_query($dbc, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $first_name = $row['First'];
        $last_name = $row['Last'];
        $user_id = $row['User_ID'];
        $current_password = $row['Password'];
    } else {
        $errorMessage = "User not found. Please make sure you are logged in.";
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($user_id)) {
        $native_language = mysqli_real_escape_string($dbc, $_POST['native_language']);
        $learn_language = mysqli_real_escape_string($dbc, $_POST['learn_language']);
        $hobbies_interests = mysqli_real_escape_string($dbc, $_POST['hobbies_interests']);

        $query = "INSERT INTO `User-Sorter` (User_ID, Native_Language_ID, Learn_Language_ID, Hobby_ID)
                  VALUES ('$user_id', '$native_language', '$learn_language', '$hobbies_interests')
                  ON DUPLICATE KEY UPDATE 
                      Native_Language_ID = '$native_language', 
                      Learn_Language_ID = '$learn_language', 
                      Hobby_ID = '$hobbies_interests'";

        if (mysqli_query($dbc, $query)) {
            $successMessage = "Profile updated successfully!";
        } else {
            $errorMessage = "Error updating profile information: " . mysqli_error($dbc);
        }

        if (!empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                $update_password_query = "UPDATE User SET Password = '$hashed_password' WHERE User_ID = '$user_id'";

                if (mysqli_query($dbc, $update_password_query)) {
                    $successMessage .= " Password updated successfully!";
                } else {
                    $errorMessage .= " Error updating password: " . mysqli_error($dbc);
                }
            } else {
                $errorMessage = "Passwords do not match.";
            }
        }

        mysqli_close($dbc);
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
    <title>Profile Settings</title>
    <link rel="stylesheet" href="profile_settings.css">
</head>
<body>

<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($first_name . ' ' . $last_name); ?>!</h2>
    <h3>Profile Settings</h3>

    <?php 
    if (!empty($successMessage)) echo '<p id="success-message">'.$successMessage.'</p>';
    if (!empty($errorMessage)) echo '<p id="error-message">'.$errorMessage.'</p>';
    ?>

    <form action="profile_settings.php" method="POST">
        <label for="native_language">Natively Spoken Language:</label>
        <select name="native_language" id="native_language" required>
            <option value="1">English</option>
            <option value="2">Spanish</option>
            <option value="3">French</option>
            <option value="4">German</option>
            <option value="5">Japanese</option>
        </select>

        <label for="learn_language">Language You Want to Learn:</label>
        <select name="learn_language" id="learn_language" required>
            <option value="1">English</option>
            <option value="2">Spanish</option>
            <option value="3">French</option>
            <option value="4">German</option>
            <option value="5">Japanese</option>
        </select>

        <label for="hobbies_interests">Hobbies and Interests:</label>
        <select name="hobbies_interests" id="hobbies_interests" required>
            <option value="1">Sports</option>
            <option value="2">Music</option>
            <option value="3">Art</option>
            <option value="4">Technology</option>
            <option value="5">Travel</option>
        </select>

        <br><br>
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password">

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" name="confirm_password" id="confirm_password">

        <button type="submit">Save Profile</button>
    </form>
</div>

</body>
</html>
