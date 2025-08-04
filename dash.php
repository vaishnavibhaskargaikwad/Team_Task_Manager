
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Team Task Manager Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        
        {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            background-color: #d1cfcf;
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
        }

        .sidebar a:hover {
            background-color: #675590;
        }

        /* Main content */
        .main {
            flex: 1;
            padding: 30px;
        }

        .main h1 {
            margin-bottom: 30px;
        }

        .top-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: #ccc;
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            font-size: 18px;
        }

        .card.red { background-color: #a94442; }
        .card.purple { background-color: #8e44ad; }
        .card.blue { background-color: #2c3e50; }
        .card.gold { background-color: #b7950b; }

        .card span {
            display: block;
            font-size: 14px;
            margin-top: 8px;
            color: #f0f0f0;
        }

        .bottom-panels {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .panel {
            background-color: #f2eeee;
            padding: 20px;
            border-radius: 10px;
            min-height: 200px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .panel h3 {
            margin-bottom: 10px;
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

    <!-- Main content -->
    <div class="main">
       
        <h1>Dashboard</h1>

        <!-- Top Cards -->
        <div class="top-cards">
            <div class="card red" onclick="openpage('team.php')">
                <strong>Team</strong>
                <span>Manage your team</span>
            </div>
            <div class="card purple" onclick="openpage('task.php')">
                <strong>Task</strong>
                <span>Track tasks</span>
            </div>
            <div class="card blue" onclick="openpage('report.php')">
                <strong>Report</strong>
                <span>View reports</span>
            </div>
            <div class="card gold" onclick="openpage('settings.php')">
                <strong>Settings</strong>
                <span>Manage settings</span>
            </div>
        </div>

        <!-- Bottom Panels -->
        <div class="bottom-panels">
            <div class="panel">
                <h3>Task Progress</h3>
                <!-- Add charts or progress indicators here -->
            </div>
            <div class="panel">
                <h3>Team Performance</h3>
                <!-- Add analytics or visual performance data -->
            </div>
        </div>
    </div>
    <script>function openpage(url){
        window.location.href=url;
    }
    </script>
</body>
</html>