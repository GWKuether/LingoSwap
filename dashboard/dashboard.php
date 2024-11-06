<?php
require('/home/infost490f2406/public_html/mysqli_connect.php');
session_start();

$username = $_SESSION['username'] ?? null;

if (!$username) {
    header("Location: /home/home.php");
    exit();
}

$user_query = "SELECT User_ID, Native_Language_ID, Learn_Language_ID FROM `User-Sorter` 
               WHERE User_ID = (SELECT User_ID FROM `User` WHERE Username = '$username')";

$user_result = mysqli_query($dbc, $user_query);

if ($user_result && mysqli_num_rows($user_result) == 1) {
    $user_data = mysqli_fetch_assoc($user_result);
    $current_user_id = $user_data['User_ID'];
    $native_language_id = $user_data['Native_Language_ID'];
    $learn_language_id = $user_data['Learn_Language_ID'];

    $match_query = "
    SELECT User.User_ID, User.Username, User.First, User.Last, U1.Hobby_ID, Hobbies.Hobby AS Hobby
    FROM `User-Sorter` AS U1
    JOIN `User` ON U1.User_ID = User.User_ID
    JOIN `Hobbies` ON U1.Hobby_ID = Hobbies.Hobby_ID
    WHERE U1.Native_Language_ID = $learn_language_id
      AND U1.Learn_Language_ID = $native_language_id
      AND U1.User_ID != $current_user_id
";

    $match_result = mysqli_query($dbc, $match_query);
} else {
    echo "Error: Unable to retrieve user information.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
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
    <!-- Welcome message-->
    <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    <h3>Dashboard</h3>

    <?php if ($match_result && mysqli_num_rows($match_result) > 0): ?>
        <ul>
            <?php while ($row = mysqli_fetch_assoc($match_result)): ?>
                <li>
                    <form action="/chat/chat.php" method="GET">
                        <input type="hidden" name="current_user_id" value="<?php echo $current_user_id; ?>">
                        <input type="hidden" name="matched_user_id" value="<?php echo $row['User_ID']; ?>">
                        
                        <button class = "dashboard_button" type="submit">
                            <?php echo htmlspecialchars($row['Username']); ?> 
                            - Interest: <?php echo htmlspecialchars($row['Hobby']); ?>
                        </button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No matches found based on your language preferences.</p>
    <?php endif; ?>

    <form action="/logout.php" method="POST">
        <button type="submit">Logout</button>
    </form>
</div>

</body>
</html>

<?php
mysqli_close($dbc);
?>
