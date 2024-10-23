<?php
require('/home/infost490f2406/public_html/mysqli_connect.php');

$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = mysqli_real_escape_string($dbc, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($dbc, $_POST['last_name']);
    $username = mysqli_real_escape_string($dbc, $_POST['username']);
    $password = mysqli_real_escape_string($dbc, $_POST['password']);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO Login (First, Last, Username, Password) VALUES ('$first_name', '$last_name', '$username', '$hashed_password')";

    if (mysqli_query($dbc, $query)) {
        session_start();
        $_SESSION['username'] = $username;

        header("Location: /profile_settings/profile_settings.php");
        exit();
    } else {
        echo "Error: Could not execute $query. " . mysqli_error($dbc);
    }

    mysqli_close($dbc);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link rel="stylesheet" href="create_user.css">
</head>
<body>

<div class="container">
    <h2>Create User</h2>

    <?php if (!empty($successMessage)) echo '<p id="success-message">'.$successMessage.'</p>'; ?>

    <form action="create_user.php" method="POST">
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Create User</button>
    </form>
</div>

</body>
</html>
