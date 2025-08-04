<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "task_manager";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$username = $_POST['username'];
$password = password_hash("", PASSWORD_DEFAULT); // Secure password

$sql = "INSERT INTO admin (username, password) VALUES ('$username', '$password')";
if ($conn->query($sql)) {
    header("location:dash.php");
} else {
    echo "Error: " . $conn->error;
}
?>
