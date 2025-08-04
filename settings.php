<?php
include("db.php");
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar */
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
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #675590;
        }

        /* Main Form Section */
        .form-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-box {
            background-color: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
        }

        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        .success {
            color: green;
            margin-bottom: 10px;
        }
    </style>

    <script>
        function validateForm() {
            const newPass = document.getElementById("new_password").value;
            const confirmPass = document.getElementById("confirm_password").value;

            if (newPass.length < 6) {
                alert("New password must be at least 6 characters.");
                return false;
            }

            if (newPass !== confirmPass) {
                alert("New passwords do not match.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2><i class="fas fa-users"></i> Team Manager</h2>
            <a href="team.php"><i class="fas fa-people-group"></i> Teams</a>
            <a href="task.php"><i class="fas fa-tasks"></i> Tasks</a>
            <a href="report.php"><i class="fas fa-chart-line"></i> Reports</a>
            <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
        </div>

        <!-- Password Change Form -->
        <div class="form-container">
            <div class="form-box">
                <h2>Change Password</h2>
                <form method="POST" action="" onsubmit="return validateForm();">
                    <label>Old Password:</label>
                    <input type="password" name="old_password" required>

                    <label>New Password:</label>
                    <input type="password" id="new_password" name="new_password" required>

                    <label>Confirm New Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>

                    <input type="submit" name="change_password" value="Change Password">
                </form>

                <?php
                $host = "localhost";
                $user = "root";
                $pass = "";
                $dbname = "task_manager";

                $conn = new mysqli($host, $user, $pass, $dbname);
                if ($conn->connect_error) {
                    echo "<div class='error'>Connection failed</div>";
                }

                if (isset($_POST['change_password'])) {
                    $username = $_SESSION['admin_username'];
                    $old_password = $_POST['old_password'];
                    $new_password = $_POST['new_password'];
                    $confirm_password = $_POST['confirm_password'];

                    if ($new_password !== $confirm_password) {
                        echo "<div class='error'>❌ New passwords do not match!</div>";
                    } else {
                        $stmt = $conn->prepare("SELECT password FROM admin WHERE username = ?");
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $admin = $result->fetch_assoc();

                        if ($admin && password_verify($old_password, $admin['password'])) {
                            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                            $update_stmt = $conn->prepare("UPDATE admin SET password = ? WHERE username = ?");
                            $update_stmt->bind_param("ss", $hashed_new_password, $username);

                            if ($update_stmt->execute()) {
                                echo "<div class='success'>✅ Password changed successfully!</div>";
                            } else {
                                echo "<div class='error'>❌ Failed to update password.</div>";
                            }
                        } else {
                            echo "<div class='error'>❌ Incorrect old password.</div>";
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>