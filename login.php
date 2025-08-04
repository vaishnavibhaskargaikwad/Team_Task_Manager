<?php
include("db.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Team Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="new.css">
</head>
<?php
if(isset($_POST['submit']))
{
    $name=$_POST['name'];
    $password=$_POST['password'];
    $query="insert into login(name,password) values('$name','$password')";
    $data=mysqli_query($conn,$query);
    if($data)
    {
        echo "";
    }
    else
    {
        echo "not inserted".mysqli_error($conn);
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
                    <li class="nav-item"><a class="nav-link active" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="signup.php">signup</a></li>
                    <li class="nav-item"><a class="nav-link " href="contact.php">contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <form action="login.php" method="post">
    <div class="neumorphic neumorphic-card ">
        <h1>login</h1>
        <input type="text" class="neumorphic neumorphic-input"  name="name" placeholder="username" required>
        <input type="password" class="neumorphic neumorphic-input" name="password"placeholder="password" id="pwd" required>
        <a href='index.html'>
        <button class="neumorphic neumorphic-button" type="submit" name="submit">login</button>
        </a>
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