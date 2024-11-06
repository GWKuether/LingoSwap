<?php
require('/home/infost490f2406/public_html/mysqli_connect.php');
session_start();

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($dbc, $_POST['username']);
    $password = mysqli_real_escape_string($dbc, $_POST['password']);

    $query = "SELECT * FROM User WHERE Username = '$username'";
    $result = mysqli_query($dbc, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $hashed_password = $user['Password'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $user['Username'];
            $_SESSION['user_id'] = $user['User_ID'];
            $successMessage = "Login successful! Welcome, " . htmlspecialchars($username) . ".";

            $user_id = $user['User_ID'];
            $profile_query = "SELECT * FROM `User-Sorter` WHERE User_ID = '$user_id' AND Native_Language_ID IS NOT NULL AND Learn_Language_ID IS NOT NULL AND Hobby_ID IS NOT NULL";
            $profile_result = mysqli_query($dbc, $profile_query);

            if (mysqli_num_rows($profile_result) > 0) {
                header("Location: /dashboard/dashboard.php");
            } else {
                header("Location: /profile_settings/profile_settings.php");
            }
            exit();
        } else {
            $errorMessage = "Invalid password. Please try again.";
        }
    } else {
        $errorMessage = "Username not found. Please check your username.";
    }

    mysqli_close($dbc);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Poppins:wght@200..900&display=swap" rel="stylesheet">
</head>
<body>
    
<div class="nav_bar">
    <a href ="/home/home.php">
        <div class="crop_image">
            <img class="logo" src="/images/Media.png" alt="Lingo Swap Logo">
        </div>
    </a>
    <div class="buttons">
        <a href="/create_user/create_user.php"><button class="btn-create">Create Account</button></a>
        <a href="/login/login.php"><button class="login_button">Login</button></a>
    </div>
</div>
<div class="container">
    <h2>Login</h2>

    <?php
    if (!empty($successMessage)) {
        echo '<p id="success-message">'.$successMessage.'</p>';
    } elseif (!empty($errorMessage)) {
        echo '<p id="error-message">'.$errorMessage.'</p>';
    }
    ?>

    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button class="login" type="submit">Login</button>
    </form>
</div>

</body>
</html>
