<?php
session_start();
$username = $_SESSION['username'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/nav_bar/nav_bar.css">
</head>
<body>
    <div class="nav_bar">
        <a href="/home/home.php">
            <img class="logo" src="/images/Media.png" alt="Lingo Swap Logo">
        </a>
        <div class="buttons">
            <?php if ($username): ?>
                <a href="/dashboard/dashboard.php"><button class="btn-dashboard">Dashboard</button></a>
                <a href="/profile_settings/profile_settings.php"><button class="btn-profile-settings">Profile Settings</button></a>
                <form action="/logout.php" method="POST" style="display:inline;">
                    <button class="btn-logout" type="submit">Logout</button>
                </form>
            <?php else: ?>
                <a href="/create_user/create_user.php"><button class="btn-create">Create Account</button></a>
                <a href="/login/login.php"><button class="login_button">Login</button></a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
