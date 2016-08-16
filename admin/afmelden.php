<?php
session_start();


//session_destroy();
if(isset($_SESSION['login'])) unset($_SESSION['login']);
if(isset($_SESSION['user'])) unset($_SESSION['user']);
header("Location: index.php");
?>
