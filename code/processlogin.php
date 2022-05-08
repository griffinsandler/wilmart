<?php

include("lib/common.php");

if (isset($_POST["submit"])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM user WHERE Username = '$username'";

    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);

        if ($password == $row["Password"]) {
            $_SESSION["username"] = $username;
            $_SESSION["type"] = $row["Type"];
            echo "You have successfully logged in" . "<br>";
            echo "Redirecting you now ... ";
            header( "refresh:3;url=menu.php" );

        } else {
            echo "Wrong password, try again" . "<br>";
            sleep(1);
            echo "Redirecting you now ... ";
            header( "refresh:3;url=login.php" );
        }

    } else {
        echo "This username does not exist" . "<br>";
        sleep(1);
        echo "Redirecting you now ... ";
        header( "refresh:3;url=login.php" );
    }

}
?>