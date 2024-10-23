<?php
require('/home/infost490f2406/public_html/mysqli_connect.php');

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($dbc, $_POST['username']);
    $password = mysqli_real_escape_string($dbc, $_POST['password']);

    $query = "SELECT * FROM Login WHERE Username = '$username'";
    $result = mysqli_query($dbc, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $hashed_password = $user['Password'];

        if (password_verify($password, $hashed_password)) {
            $successMessage = "Login successful! Welcome, " . htmlspecialchars($username) . ".";
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
</head>
<body>

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
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
