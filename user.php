<?php
// Connect to database
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "task_manager"; // Change if your DB name is different

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle task deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM tasks WHERE id = $id");
    header("Location: user.php"); // Refresh page after deletion
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
 body
 {
     background: linear-gradient(135deg, #ebadbe, #1E1E2F);
 }
        </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
        <div class="container">
            <a class="navbar-brand">TeamPlaner</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link " href="about.html">about us</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_logout.php">Logout</a></li>
                    
                </ul>
            </div>
        </div>
    </nav> 
<div class="container py-5">
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
                            <button><a href='chatapp.php'>Chat</a></button>

                        </td>
                    </tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>



