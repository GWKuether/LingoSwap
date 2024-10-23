<?php
require('/home/infost490f2406/public_html/mysqli_connect.php');
session_start();

$username = $_SESSION['username'] ?? null;
$first_name = "";
$last_name = "";
$successMessage = "";

if ($username) {
    $query = "SELECT First, Last FROM Login WHERE Username = '$username'";
    $result = mysqli_query($dbc, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $first_name = $row['First'];
        $last_name = $row['Last'];
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $native_language = mysqli_real_escape_string($dbc, $_POST['native_language']);
        $learn_language = mysqli_real_escape_string($dbc, $_POST['learn_language']);
        $hobbies_interests = mysqli_real_escape_string($dbc, $_POST['hobbies_interests']);

        $update_query = "UPDATE Login 
                         SET Native_Language = '$native_language', 
                             Learn_Language = '$learn_language', 
                             Hobbies_Interests = '$hobbies_interests'
                         WHERE Username = '$username'";

        if (mysqli_query($dbc, $update_query)) {
            $successMessage = "Profile updated successfully!";
        } else {
            echo "Error: Could not execute $update_query. " . mysqli_error($dbc);
        }

        if (!empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                $password_update_query = "UPDATE Login SET Password = '$hashed_password' WHERE Username = '$username'";
                if (mysqli_query($dbc, $password_update_query)) {
                    $successMessage .= " Password updated successfully!";
                } else {
                    echo "Error: Could not execute $password_update_query. " . mysqli_error($dbc);
                }
            } else {
                echo "Passwords do not match!";
            }
        }

        mysqli_close($dbc);
    }
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

    <?php if (!empty($successMessage)) echo '<p id="success-message">'.$successMessage.'</p>'; ?>

    <form action="profile_settings.php" method="POST">
        <label for="native_language">Natively Spoken Language:</label>
        <select name="native_language" id="native_language" required>
            <option value="English">English</option>
            <option value="Spanish">Spanish</option>
            <option value="French">French</option>
            <option value="German">German</option>
            <option value="Japanese">Japanese</option>
        </select>

        <label for="learn_language">Language You Want to Learn:</label>
        <select name="learn_language" id="learn_language" required>
            <option value="English">English</option>
            <option value="Spanish">Spanish</option>
            <option value="French">French</option>
            <option value="German">German</option>
            <option value="Japanese">Japanese</option>
        </select>

        <label for="hobbies_interests">Hobbies and Interests:</label>
        <select name="hobbies_interests" id="hobbies_interests" required>
            <option value="Sports">Sports</option>
            <option value="Music">Music</option>
            <option value="Art">Art</option>
            <option value="Technology">Technology</option>
            <option value="Travel">Travel</option>
        </select>

        <br>
        <br>
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password">

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" name="confirm_password" id="confirm_password">

        <button type="submit">Save Profile</button>
    </form>
</div>

</body>
</html>
