<?php
include('db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Team Task Manager</title>
    <link rel="stylesheet" href="new.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<?php
    include "db.php";
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $name =$conn->real_escape_string($_POST["name"]);
        $email=$conn->real_escape_string($_POST["email"]);
        $message=$conn->real_escape_string($_POST["message"]);
        $check=$conn->query("SELECT * FROM message WHERE email='email'");
        if($check->num_rows>0){
            echo "email already exsists";

        }else{
            $sql="INSERT INTO message (name,email,message)VALUES('$name','$email','$message')";
            if($conn ->query($sql)){
                echo "successful";

            }else{
                echo "error:".$conn->error;
            }
        }
    }
?>
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
                    <li class="nav-item"><a class="nav-link " href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link " href="about.html">about us</a></li>
                    <li class="nav-item"><a class="nav-link active" href="contact.php">contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <h5><i>Enter your details</i></h5>
    <form action="contact.php" method="post">
    <div class="neumorphic neumorphic-card ">
        <h1>contact us</h1>
        <input type="text" class="neumorphic neumorphic-input" name="name" placeholder="username" required>
        <input type="email" class="neumorphic neumorphic-input" name="email" placeholder="email" required>
        <input type="text" class="neumorphic neumorphic-input"  name="message" placeholder="message"required>
        <button class="neumorphic neumorphic-button" value="Send" name="submit">Send</button>
    </div>
    </form>
    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        &copy; 2025 TeamTask Manager. All rights reserved.
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>