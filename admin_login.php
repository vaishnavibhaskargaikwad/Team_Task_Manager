<?php
include ("db2.php");
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "task_manager"; // Your DB name

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Get user from DB
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        
        // Check hashed password
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_username'] = $admin['username'];
            echo "Login successful. Redirecting...";
            header("refresh:2; url=dash.php");
        } else {
            echo "❌ Incorrect password";
        }
    } else {
        echo "❌ Admin user not found";
    }
}
?>
