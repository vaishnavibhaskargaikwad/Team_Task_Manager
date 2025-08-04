<?php
session_start();
session_destroy();
header("Location: chatapp.php");
exit;
?>
