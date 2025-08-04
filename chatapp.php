<?php
session_start();

// Database setup
$db = new mysqli("localhost", "root", "", "task_manager");
if ($db->connect_error) die("DB Connection Failed");

// Handle login
if (isset($_POST['login'])) {
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['team'] = $_POST['team'];

    $username = $db->real_escape_string($_POST['username']);
    $team = $db->real_escape_string($_POST['team']);
    $db->query("INSERT INTO chat (username, team) VALUES ('$username', '$team')");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Handle new message
if (isset($_POST['send'])) {
    $username = $_SESSION['username'];
    $team = $_SESSION['team'];
    $message = $db->real_escape_string($_POST['message']);
    $db->query("INSERT INTO msg (team, username, message) VALUES ('$team', '$username', '$message')");
}

// Fetch messages
$messages = [];
if (isset($_SESSION['team'])) {
    $team = $_SESSION['team'];
    $result = $db->query("SELECT * FROM msg WHERE team='$team' ORDER BY created_at DESC LIMIT 50");
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Team Chat</title>
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #ebadbe, #1E1E2F);
    margin: 0;
    padding: 0;
}
.container {
    max-width: 600px;
    margin: 40px auto;
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 8px 20px rgba(169, 167, 212, 0.1);
}
h2 {
    text-align: center;
    color: #444;
    margin-bottom: 20px;
}
.chat-box {
    height: 400px;
    overflow-y: scroll;
    background: #566f94ff ;
    padding: 15px;
    border-radius: 12px;
    border: 1px solid #e0e0e0;
    margin-bottom: 15px;
}
.msg {
    max-width: 50%;
    padding: 10px 15px;
    margin: 8px 0;
    border-radius: 18px;
    background-color: #b3d0fbff;
    color: white;
    position: relative;
    clear: both;
}
.msg.self {
    background-color:#f9a8d4;
    margin-left: auto;
}
.msg strong {
    display: block;
    font-weight: 600;
    font-size: 14px;
}
.msg .timestamp {
    font-size: 10px;
    color: #eee;
    text-align: right;
    margin-top: 5px;
}
form {
    display: flex;
    gap: 10px;
}
input[type=text] {
    flex: 1;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 12px;
    font-size: 16px;
}
button {
    background-color: #f472b6;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 12px;
    cursor: pointer;
    font-size: 16px;
    transition: 0.3s ease;
}
button:hover {
    background-color: #ec4899;
}
button.logout {
    background-color: #aaa;
    margin-top: 10px;
}
button.logout:hover {
    background-color: #ec4899;
}
</style>
</head>
<body>
<div class="container">
<?php if (!isset($_SESSION['username'])): ?>
    <button onclick="openpage('index.html')" style="margin-bottom:10px;">Back</button>
    <h2>Login to Team Chat</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Your name" required>
        <input type="text" name="team" placeholder="Team name" required>
        <button type="submit" name="login">Join</button>
    </form>
<?php else: ?>
    <h2>Team: <?= htmlspecialchars($_SESSION['team']) ?> | User: <?= htmlspecialchars($_SESSION['username']) ?></h2>
    <div class="chat-box" id="chatBox">
        <?php foreach (array_reverse($messages) as $msg): ?>
            <div class="msg <?= $msg['username'] === $_SESSION['username'] ? 'self' : '' ?>">
                <strong><?= htmlspecialchars($msg['username']) ?></strong>
                <?= htmlspecialchars($msg['message']) ?>
                <div class="timestamp"><?= date('M d, h:i A', strtotime($msg['created_at'])) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
    <form method="post">
        <input type="text" name="message" placeholder="Type message..." required>
        <button type="submit" name="send">Send</button>
    </form>
    <form method="post" action="chatlogout.php">
        <button type="submit" class="logout">Logout</button>
    </form>
<?php endif; ?>
</div>
<script>
function openpage(url){
    window.location.href = url;
}
const chatBox = document.getElementById("chatBox");
if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
</script>
</body>
</html>