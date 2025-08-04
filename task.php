<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "task_manager";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Alert Message
$msg = "";

// Add Task
if (isset($_POST['add_task'])) {
    $title = $_POST['title'];
    $team = $_POST['group_name'];
    $member = $_POST['member_name'];
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO tasks (title, group_name, member_name, priority, deadline, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $team, $member, $priority, $deadline, $status);
    $stmt->execute();
    $msg = "<div class='alert alert-success text-center'>✅ Task added successfully!</div>";
}

// Update Task
if (isset($_POST['update_task'])) {
    $id = $_POST['task_id'];
    $title = $_POST['title'];
    $team = $_POST['group_name'];
    $member = $_POST['member_name'];
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE tasks SET title=?, group_name=?, member_name=?, priority=?, deadline=?, status=? WHERE id=?");
    $stmt->bind_param("ssssssi", $title, $team, $member, $priority, $deadline, $status, $id);
    $stmt->execute();
    $msg = "<div class='alert alert-info text-center'>🔄 Task updated successfully!</div>";
}

// Delete Task
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM tasks WHERE id=$id");
    $msg = "<div class='alert alert-danger text-center'>🗑️ Task deleted successfully!</div>";
}

// Get task for editing
$editTask = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM tasks WHERE id=$id");
    if ($result->num_rows > 0) {
        $editTask = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Team Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
     <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f2f6fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .main-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 220px;
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
        }

        .sidebar a:hover {
            background-color: #675590;
        }

        .content {
            flex-grow: 1;
            padding: 40px;
        }

        .form-label {
            font-weight: 500;
        }

        table th, table td {
            vertical-align: middle !important;
        }

        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                flex-direction: row;
                overflow-x: auto;
                justify-content: space-around;
            }

            .sidebar h2 {
                display: none;
            }

            .sidebar a {
                font-size: 14px;
                padding: 10px;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
<div class="main-container">
    <!-- Sidebar -->
      <div class="sidebar">
        <h2><i class="fas fa-users"></i> Team Manager</h2>
        <a href="team.php"><i class="fas fa-people-group"></i> Teams</a>
        <a href="task.php"><i class="fas fa-tasks"></i> Tasks</a>
        <a href="report.php"><i class="fas fa-chart-line"></i> Reports</a>
        <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
    </div>

  
    <!-- Main Content -->
    <div class="content">
        <h2 class="mb-4 text-center">📌 Team Task Management</h2>

        <?= $msg ?>

        <!-- Task Form -->
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white fw-bold">
                <?= $editTask ? '✏️ Edit Task' : '➕ Add New Task' ?>
            </div>
            <div class="card-body">
                <form method="post">
                    <?php if ($editTask): ?>
                        <input type="hidden" name="task_id" value="<?= $editTask['id'] ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Task Title</label>
                        <input type="text" name="title" class="form-control" required value="<?= $editTask['title'] ?? '' ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Team</label>
                            <select name="group_name" class="form-select" required>
                                <option value="">Select Team</option>
                                <?php
                                $teams = $conn->query("SELECT DISTINCT group_name FROM team_members");
                                while ($t = $teams->fetch_assoc()) {
                                    $selected = ($editTask && $editTask['group_name'] == $t['group_name']) ? "selected" : "";
                                    echo "<option value='{$t['group_name']}' $selected>{$t['group_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Member</label>
                            <select name="member_name" class="form-select" required>
                                <option value="">Select Member</option>
                                <?php
                                $members = $conn->query("SELECT DISTINCT member_name FROM team_members");
                                while ($m = $members->fetch_assoc()) {
                                    $selected = ($editTask && $editTask['member_name'] == $m['member_name']) ? "selected" : "";
                                    echo "<option value='{$m['member_name']}' $selected>{$m['member_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Priority</label>
                            <select name="priority" class="form-select" required>
                                <?php
                                $priorities = ['Low', 'Medium', 'High'];
                                foreach ($priorities as $p) {
                                    $selected = ($editTask && $editTask['priority'] == $p) ? "selected" : "";
                                    echo "<option value='$p' $selected>$p</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Deadline</label>
                            <input type="date" name="deadline" class="form-control" required value="<?= $editTask['deadline'] ?? '' ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <?php
                                $statuses = ['Pending', 'In Progress', 'Completed'];
                                foreach ($statuses as $s) {
                                    $selected = ($editTask && $editTask['status'] == $s) ? "selected" : "";
                                    echo "<option value='$s' $selected>$s</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <button type="submit" name="<?= $editTask ? 'update_task' : 'add_task' ?>" class="btn <?= $editTask ? 'btn-info' : 'btn-success' ?> w-100">
                        <i class="fas <?= $editTask ? 'fa-sync' : 'fa-plus-circle' ?>"></i> <?= $editTask ? 'Update Task' : 'Add Task' ?>
                    </button>
                </form>
            </div>
        </div>

        <!-- Task Table -->
        <div class="card shadow">
            <div class="card-header bg-dark text-white fw-bold">📋 All Tasks</div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped text-center align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Team</th>
                        <th>Member</th>
                        <th>Priority</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $tasks = $conn->query("SELECT * FROM tasks ORDER BY id DESC");
                    while ($task = $tasks->fetch_assoc()) {
                        echo "<tr>
                            <td>{$task['id']}</td>
                            <td>{$task['title']}</td>
                            <td>{$task['group_name']}</td>
                            <td>{$task['member_name']}</td>
                            <td><span class='badge bg-".($task['priority']=='High'?'danger':($task['priority']=='Medium'?'warning text-dark':'secondary'))."'>
                                {$task['priority']}</span></td>
                            <td>{$task['deadline']}</td>
                            <td><span class='badge bg-".($task['status']=='Completed'?'success':($task['status']=='In Progress'?'info text-dark':'secondary'))."'>
                                {$task['status']}</span></td>
                            <td>
                                <a href='?edit={$task['id']}' class='btn btn-sm btn-outline-primary me-1'>✏️</a>
                                <a href='?delete={$task['id']}' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Delete this task?\")'>🗑️</a>
                            </td>
                        </tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>