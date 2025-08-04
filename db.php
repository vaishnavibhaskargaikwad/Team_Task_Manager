<?php
$server="localhost";
$user="root";
$password="";
$database="task_manager";
$conn =new mysqli($server,$user,$password,$database);
if($conn)
{
    echo "";
}
else
{
    echo "disconnect";
}


?>