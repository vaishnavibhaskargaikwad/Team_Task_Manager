<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Task Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
        }
        .sidebar {
            background-color: #6c5ce7;
            color: white;
            padding: 20px;
            width: 200px;
            height: 100vh;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar li {
            margin-bottom: 10px;
        }
        .dashboard {
            flex-grow: 1;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .cards {
            display: flex;
            gap: 20px;
        }
        .card {
            padding: 20px;
            color: white;
            border-radius: 10px;
            width: 25%;
        }
        .card.team { background-color: #e74c3c; }
        .card.task { background-color: #9b59b6; }
        .card.report { background-color: #3498db; }
        .card.settings { background-color: #f1c40f; }
        .progress-cards {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }
        .progress-card {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            width: 50%;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Team Task Manager</h2>
        <ul>
            <li>Team</li>
            <li>Task</li>
            <li>Report</li>
            <li>Settings</li>
        </ul>
    </div>
    <div class="dashboard">
        <h2>Dashboard</h2>
        <div class="cards">
            <div class="card team">
                <h3>Team</h3>
                <p>Manage your team</p>
            </div>
            <div class="card task">
                <h3>Task</h3>
                <p>Track tasks</p>
            </div>
            <div class="card report">
                <h3>Report</h3>
                <p>View reports</p>
            </div>
            <div class="card settings">
                <h3>Settings</h3>
                <p>Manage settings</p>
            </div>
        </div>
        <div class="progress-cards">
            <div class="progress-card">
                <h3>Task Progress</h3>
            </div>
            <div class="progress-card">
                <h3>Team Performance</h3>
            </div>
        </div>
    </div>
</body>
</html>
