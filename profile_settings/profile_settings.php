<?php
require('/home/infost490f2406/public_html/mysqli_connect.php');
session_start();

$username = $_SESSION['username'] ?? null;
$first_name = "";
$last_name = "";
$successMessage = "";
$errorMessage = "";

$native_language_selected = "";
$learn_language_selected = "";
$hobbies_interests_selected = "";

if ($username) {
    $query = "SELECT First, Last, User_ID FROM User WHERE Username = '$username'";
    $result = mysqli_query($dbc, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $first_name = $row['First'];
        $last_name = $row['Last'];
        $user_id = $row['User_ID'];

        $sorter_query = "SELECT Native_Language_ID, Learn_Language_ID, Hobby_ID FROM `User-Sorter` WHERE User_ID = '$user_id'";
        $sorter_result = mysqli_query($dbc, $sorter_query);
        
        if ($sorter_result && mysqli_num_rows($sorter_result) == 1) {
            $sorter_row = mysqli_fetch_assoc($sorter_result);
            $native_language_selected = $sorter_row['Native_Language_ID'];
            $learn_language_selected = $sorter_row['Learn_Language_ID'];
            $hobbies_interests_selected = $sorter_row['Hobby_ID'];
        }
    } else {
        $errorMessage = "User not found. Please make sure you are logged in.";
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
        $native_language = mysqli_real_escape_string($dbc, $_POST['native_language']);
        $learn_language = mysqli_real_escape_string($dbc, $_POST['learn_language']);
        $hobbies_interests = mysqli_real_escape_string($dbc, $_POST['hobbies_interests']);

        if ($native_language === $learn_language) {
            $errorMessage = "Native language and language to learn cannot be the same.";
        } else {
            $query = "INSERT INTO `User-Sorter` (User_ID, Native_Language_ID, Learn_Language_ID, Hobby_ID)
                      VALUES ('$user_id', '$native_language', '$learn_language', '$hobbies_interests')
                      ON DUPLICATE KEY UPDATE 
                          Native_Language_ID = '$native_language', 
                          Learn_Language_ID = '$learn_language', 
                          Hobby_ID = '$hobbies_interests'";

            if (mysqli_query($dbc, $query)) {
                $successMessage = "Profile updated successfully!";
                header("Location: /dashboard/dashboard.php");
                exit();
            } else {
                $errorMessage = "Error updating profile information: " . mysqli_error($dbc);
            }
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
            <option value="1" <?php if ($native_language_selected == 1) echo 'selected'; ?>>English</option>
            <option value="2" <?php if ($native_language_selected == 2) echo 'selected'; ?>>Spanish</option>
            <option value="3" <?php if ($native_language_selected == 3) echo 'selected'; ?>>French</option>
            <option value="4" <?php if ($native_language_selected == 4) echo 'selected'; ?>>German</option>
            <option value="5" <?php if ($native_language_selected == 5) echo 'selected'; ?>>Japanese</option>
        </select>

        <label for="learn_language">Language You Want to Learn:</label>
        <select name="learn_language" id="learn_language" required>
            <option value="1" <?php if ($learn_language_selected == 1) echo 'selected'; ?>>English</option>
            <option value="2" <?php if ($learn_language_selected == 2) echo 'selected'; ?>>Spanish</option>
            <option value="3" <?php if ($learn_language_selected == 3) echo 'selected'; ?>>French</option>
            <option value="4" <?php if ($learn_language_selected == 4) echo 'selected'; ?>>German</option>
            <option value="5" <?php if ($learn_language_selected == 5) echo 'selected'; ?>>Japanese</option>
        </select>

        <label for="hobbies_interests">Hobbies and Interests:</label>
        <select name="hobbies_interests" id="hobbies_interests" required>
            <option value="1" <?php if ($hobbies_interests_selected == 1) echo 'selected'; ?>>Sports</option>
            <option value="2" <?php if ($hobbies_interests_selected == 2) echo 'selected'; ?>>Music</option>
            <option value="3" <?php if ($hobbies_interests_selected == 3) echo 'selected'; ?>>Art</option>
            <option value="4" <?php if ($hobbies_interests_selected == 4) echo 'selected'; ?>>Technology</option>
            <option value="5" <?php if ($hobbies_interests_selected == 5) echo 'selected'; ?>>Travel</option>
        </select>
        <button type="submit" name="update_profile">Save Profile</button>
    </form>
</div>
</body>
</html>
