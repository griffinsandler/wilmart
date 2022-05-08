<?php
    include("lib/common.php");

    if(isset($_SESSION["username"])) {
        $_SESSION["username"] = null;
        $_SESSION["type"] = null;
        echo "You have successfully logged out" . "<br>";
        echo "Redirecting you now ... ";
        header( "refresh:3;url=login.php" );
    } else {
        echo "You were not logged in" . "<br>";
        echo "Redirecting you now ... ";
        header( "refresh:3;url=login.php" );
    }
?>
