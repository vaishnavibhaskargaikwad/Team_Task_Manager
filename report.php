<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "task_manager";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Add report
if (isset($_POST['add'])) {
    $stmt = $conn->prepare("INSERT INTO reports (title, description, progress) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $_POST['title'], $_POST['description'], $_POST['progress']);
    $stmt->execute(); $stmt->close();
    header("Location: report.php"); exit();
}

// Update report
if (isset($_POST['update']) && !empty($_POST['id'])) {
    $stmt = $conn->prepare("UPDATE reports SET title=?, description=?, progress=? WHERE id=?");
    $stmt->bind_param("ssii", $_POST['title'], $_POST['description'], $_POST['progress'], $_POST['id']);
    $stmt->execute(); $stmt->close();
    header("Location: report.php"); exit();
}

// Delete report
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM reports WHERE id=?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute(); $stmt->close();
    header("Location: report.php"); exit();
}

$result = $conn->query("SELECT * FROM reports ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Team Task Manager - Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Global Styling */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #f5f7fa;
        }

        /* Sidebar Styling */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100vh;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .sidebar h2 {
            font-size: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 5px;
            display: block;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #675590;
        }

        /* Main Content Area */
        .wrapper {
            margin-left: 240px;
            padding: 30px;
        }

        /* Form Styling */
        .form-box {
            background-color: #ffffff;
            padding: 25px 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .form-box h3 {
            margin-bottom: 20px;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
            margin-right: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        #cancelBtn {
            background-color: #888;
        }

        /* Table Section */
        .table-box {
            margin-top: 30px;
            background-color: #fff;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .table-box h3 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }

        th {
            background-color: #eaeaea;
        }

        /* Progress Bar */
        .progress-container {
            background-color: #eee;
            border-radius: 20px;
            overflow: hidden;
            height: 20px;
        }

        .progress-bar {
            height: 100%;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            line-height: 20px;
        }

        /* Actions */
        .actions {
            display: flex;
            gap: 8px;
        }

        .actions button {
            background-color: #2196F3;
        }

        .actions .delete-btn {
            background-color: #f44336;
        }

        .actions form {
            display: inline;
        }

        .actions button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2><i class="fas fa-users"></i> Team Manager</h2>
        <a href="team.php"><i class="fas fa-people-group"></i> Teams</a>
        <a href="task.php"><i class="fas fa-tasks"></i> Tasks</a>
        <a href="report.php"><i class="fas fa-chart-line"></i> Reports</a>
        <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
    </div>

    <!-- Main Content -->
    <div class="wrapper">
        <!-- Form Box -->
        <div class="form-box">
            <h3>Manage Report</h3>
            <form method="POST" id="reportForm">
                <input type="hidden" name="id" id="reportId">
                <input type="text" name="title" id="title" placeholder="Enter Title" required>
                <textarea name="description" id="description" placeholder="Enter Description" required></textarea>
                <input type="number" name="progress" id="progress" placeholder="Progress %" min="0" max="100" required>

                <button type="submit" name="add" id="addBtn">Add</button>
                <button type="submit" name="update" id="updateBtn" style="display:none;">Update</button>
                <button type="button" onclick="resetForm()" id="cancelBtn" style="display:none;">Cancel</button>
            </form>
        </div>

        <!-- Report Table -->
        <div class="table-box">
            <h3>Report List</h3>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Progress</th>
                    <th>Actions</th>
                </tr>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td>
                        <div class="progress-container">
                            <div class="progress-bar" style="width:<?= $row['progress'] ?>%;">
                                <?= $row['progress'] ?>%
                            </div>
                        </div>
                    </td>
                    <td class="actions">
                        <button type="button"
                            onclick="editReport(
                                '<?= $row['id'] ?>',
                                '<?= htmlspecialchars(addslashes($row['title'])) ?>',
                                '<?= htmlspecialchars(addslashes($row['description'])) ?>',
                                '<?= $row['progress'] ?>')">Edit</button>

                        <form method="GET" onsubmit="return confirm('Delete this report?');">
                            <input type="hidden" name="delete" value="<?= $row['id'] ?>">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

    <!-- JavaScript for Edit Form -->
    <script>
        function editReport(id, title, desc, prog) {
            document.getElementById('reportId').value = id;
            document.getElementById('title').value = title;
            document.getElementById('description').value = desc;
            document.getElementById('progress').value = prog;

            document.getElementById('addBtn').style.display = 'none';
            document.getElementById('updateBtn').style.display = 'inline-block';
            document.getElementById('cancelBtn').style.display = 'inline-block';
        }

        function resetForm() {
            document.getElementById('reportForm').reset();
            document.getElementById('reportId').value = '';
            document.getElementById('addBtn').style.display = 'inline-block';
            document.getElementById('updateBtn').style.display = 'none';
            document.getElementById('cancelBtn').style.display = 'none';
        }
    </script>
</body>
</html>