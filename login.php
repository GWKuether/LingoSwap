<?php
require('mysqli_connect.php');

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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        #success-message {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color: green;
        }

        #error-message {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color: red;
        }
    </style>
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
