<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "task_manager";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Delete team
if (isset($_GET['delete_team'])) {
    $group_name = $_GET['delete_team'];
    $stmt = $conn->prepare("DELETE FROM team_members WHERE group_name = ?");
    $stmt->bind_param("s", $group_name);
    $stmt->execute();
    echo "<div class='success'>🗑️ Team '$group_name' deleted successfully!</div>";
}

// Create team
if (isset($_POST['create_team'])) {
    $group_name = $_POST['group_name'];
    $member_count = intval($_POST['member_count']);

    for ($i = 1; $i <= $member_count; $i++) {
        $name = $_POST["name_$i"];
        $email = $_POST["email_$i"];
        $task = $_POST["task_$i"];

        $stmt = $conn->prepare("INSERT INTO team_members (group_name, member_name, email, task) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $group_name, $name, $email, $task);
        $stmt->execute();
    }

    echo "<div class='success'>✅ Team '$group_name' created successfully!</div>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Team Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { display: flex; min-height: 100vh; background-color: #f3f4f6; color: #333; }
        .sidebar {
            width: 220px; background-color: #2c3e50; color: white;
            padding: 20px; display: flex; flex-direction: column; gap: 15px;
        }
        .sidebar h2 {
            font-size: 22px; margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 10px;
            color:white;
        }
        .sidebar a {
            color: white; text-decoration: none; padding: 10px; border-radius: 5px;
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar a:hover { background-color: #675590; }
        .container {
            padding: 30px; width: calc(100% - 220px); background-color: #f3f4f6;
        }
        h2, h3 { text-align: center; color: #2c3e50; margin-bottom: 20px; }
        .field { margin-bottom: 15px; }
        label { font-weight: 600; display: block; margin-bottom: 6px; }
        input[type="text"], input[type="email"], input[type="number"] {
            width: 100%; padding: 12px; border-radius: 6px; border: 1px solid #ccc;
        }
        button {
            background-color: #3498db; color: white; padding: 12px;
            border: none; border-radius: 6px; width: 10%; font-size: 16px; cursor: pointer; margin-top: 10px;
        }
        button:hover { background-color: #2980b9; }
        .success {
            background: #d4edda; padding: 15px; border-radius: 6px; margin-bottom: 15px;
            color: #155724; border: 1px solid #c3e6cb; text-align: center;
        }
        .team-table {
            width: 100%; margin-top: 25px; border-collapse: collapse; background: white;
            border-radius: 10px; overflow: hidden;
        }
        .team-table th, .team-table td {
            border: 1px solid #ddd; padding: 12px; text-align: center;
        }
        .team-table th { background-color: #f8f9fa; }
        .action-btns a {
            margin: 0 5px; padding: 8px 10px; text-decoration: none; border-radius: 4px;
            color: white; font-size: 14px; display: inline-block;
        }
        .delete-btn { background: #e74c3c; }
        .edit-btn { background: #2ecc71; }
        hr { margin: 20px 0; border: 0; height: 1px; background: #ccc; }
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; }
            .container { width: 100%; padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2><i class="fas fa-users"></i> Team Manager</h2>
        <a href="team.php"><i class="fas fa-people-group"></i> Teams</a>
        <a href="task.php"><i class="fas fa-tasks"></i> Tasks</a>
        <a href="report.php"><i class="fas fa-chart-line"></i> Reports</a>
        <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
    </div>

    <div class="container">
        <h2>👥 Create a New Team</h2>

        <?php if (!isset($_POST['set_member_count'])): ?>
            <form method="post">
                <div class="field">
                    <label>Group Name</label>
                    <input type="text" name="group_name" required>
                </div>
                <div class="field">
                    <label>Number of Members</label>
                    <input type="number" name="member_count" min="1" required>
                </div>
                <button type="submit" name="set_member_count"><i class="fas fa-arrow-right"></i> Next</button>
            </form>

        <?php else:
            $group_name = htmlspecialchars($_POST['group_name']);
            $member_count = intval($_POST['member_count']);
        ?>
            <form method="post">
                <input type="hidden" name="group_name" value="<?php echo $group_name; ?>">
                <input type="hidden" name="member_count" value="<?php echo $member_count; ?>">
                <h3>Group: <?php echo $group_name; ?></h3>
                <hr>
                <?php for ($i = 1; $i <= $member_count; $i++): ?>
                    <div class="field">
                        <label>Member <?php echo $i; ?> Name</label>
                        <input type="text" name="name_<?php echo $i; ?>" required>
                    </div>
                    <div class="field">
                        <label>Email</label>
                        <input type="email" name="email_<?php echo $i; ?>" required>
                    </div>
                    <div class="field">
                        <label>Task</label>
                        <input type="text" name="task_<?php echo $i; ?>" required>
                    </div>
                    <hr>
                <?php endfor; ?>
                <button type="submit" name="create_team"><i class="fas fa-check"></i> Create Team</button>
            </form>
        <?php endif; ?>

        <hr>
        <h3>📋 Existing Teams</h3>
        <div style="text-align: right;">
            <button onclick="openpage('chatapp.php')"><i class="fas fa-comments"></i> Chat</button>
        </div>

        <table class="team-table">
            <tr>
                <th>Group Name</th>
                <th>Members</th>
                <th>Actions</th>
            </tr>
            <?php
            $teams = $conn->query("SELECT group_name, COUNT(*) as total FROM team_members GROUP BY group_name");
            while ($row = $teams->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['group_name']) . "</td>";
                echo "<td>" . $row['total'] . "</td>";
                echo "<td class='action-btns'>
                        <a href='?delete_team={$row['group_name']}' class='delete-btn'><i class='fas fa-trash'></i></a>
                        <a href='update_team.php?group={$row['group_name']}' class='edit-btn'><i class='fas fa-edit'></i></a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <script>
        function openpage(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>