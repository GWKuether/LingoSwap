<?php
require('/home/infost490f2406/public_html/mysqli_connect.php');
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: /home/home.php");
    exit();
}

$current_user_id = $_GET['current_user_id'] ?? null;
$matched_user_id = $_GET['matched_user_id'] ?? null;

if (!$current_user_id || !$matched_user_id) {
    echo "Error: User IDs are missing.";
    exit();
}

$matched_user_query = "SELECT Username FROM `User` WHERE User_ID = $matched_user_id";
$matched_user_result = mysqli_query($dbc, $matched_user_query);
if ($matched_user_result && mysqli_num_rows($matched_user_result) > 0) {
    $matched_user_data = mysqli_fetch_assoc($matched_user_result);
    $matched_username = $matched_user_data['Username'];
} else {
    $matched_username = "Unknown User";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = mysqli_real_escape_string($dbc, trim($_POST['message']));
    
    $insert_query = "
        INSERT INTO `Messages` (Sender_ID, Receiver_ID, Message_Content, Timestamp)
        VALUES ($current_user_id, $matched_user_id, '$message', NOW())
    ";
    mysqli_query($dbc, $insert_query);
}

$chat_query = "
    SELECT Sender_ID, Message_Content, Timestamp 
    FROM `Messages`
    WHERE (Sender_ID = $current_user_id AND Receiver_ID = $matched_user_id)
       OR (Sender_ID = $matched_user_id AND Receiver_ID = $current_user_id)
    ORDER BY Timestamp ASC
";
$chat_result = mysqli_query($dbc, $chat_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="chat.css">
</head>
<body>

   <?php include '../nav_bar/nav_bar.php'; ?>

    <div class="chat-container">
        <h2>Chat with <?php echo htmlspecialchars($matched_username); ?></h2>

        <div class="chat-history">
            <?php if ($chat_result && mysqli_num_rows($chat_result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($chat_result)): ?>
                    <p><strong><?php echo ($row['Sender_ID'] == $current_user_id) ? 'You' : 'Them'; ?>:</strong> 
                    <?php echo htmlspecialchars($row['Message_Content']); ?> 
                    <span class="timestamp"><?php echo $row['Timestamp']; ?></span></p>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No messages yet.</p>
            <?php endif; ?>
        </div>

        <form action="chat.php?current_user_id=<?php echo $current_user_id; ?>&matched_user_id=<?php echo $matched_user_id; ?>" method="POST">
            <div class="send_message">
                <textarea name="message" placeholder="Type your message here..." required></textarea>
                <button type="submit" class="send_button">Send</button>
            </div>
        </form>
    </div>

</body>
</html>

<?php
mysqli_close($dbc);
?>
