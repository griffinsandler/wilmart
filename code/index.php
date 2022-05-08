<?php

// written by bdunham3

session_start();
if (empty($_SESSION['username']) ){
    header("Location: login.php");
    die();
}else{
    header("Location: menu.php");
    die();
}
?>