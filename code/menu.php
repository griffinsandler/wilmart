<?php

include("lib/common.php");
include("lib/header.php");

if (!isset($_SESSION["username"])) {
    header( "refresh:0;url=login.php" );
}

// count of stores
$queryStore = "SELECT COUNT(*) AS cnt FROM store;";
// count of showcase stores
$queryShowcase = "SELECT COUNT(*) AS cnt FROM store WHERE WillsGrandShowcase = 1;";
// count of manufacturers
$queryManufacturer = "SELECT COUNT(*) AS cnt FROM manufacturer;";
// count of products
$queryProduct = "SELECT COUNT(DISTINCT PID) AS cnt FROM product;";
// count of special savings day
$querySavings = "SELECT COUNT(*) AS cnt FROM `business day` WHERE SavingsDay = 1;";

$resultStore = mysqli_query($db, $queryStore);
$resultShowcase = mysqli_query($db, $queryShowcase);
$resultManufacturer = mysqli_query($db, $queryManufacturer);
$resultProduct = mysqli_query($db, $queryProduct);
$resultSavings = mysqli_query($db, $querySavings);

$rowStore = mysqli_fetch_assoc($resultStore);
$rowShowcase = mysqli_fetch_assoc($resultShowcase);
$rowManufacturer = mysqli_fetch_assoc($resultManufacturer);
$rowProduct = mysqli_fetch_assoc($resultProduct);
$rowSavings = mysqli_fetch_assoc($resultSavings);

?>
<style>
    .detail {
        color: #ce8483;
    }
</style>
<title>Main Menu</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>
    <div class="center_content">
        <div class="title_name">Statistics</div>
        <div class="main_table">
            <form name="drilldownform" action="">
                <table border="1">
                    <?php
                    echo "<tr> <td> Number of stores </td> <td> " .$rowStore['cnt'] . "</td></tr>";
                    echo "<tr> <td> Number of showcase stores </td> <td> " .$rowShowcase['cnt'] . "</td></tr>";
                    echo "<tr> <td> Number of manufacturers </td> <td> " .$rowManufacturer['cnt'] . "</td></tr>";
                    echo "<tr> <td> Number of products </td> <td> " .$rowProduct['cnt'] . "</td></tr>";
                    echo "<tr> <td> Number of savings day </td> <td> " .$rowSavings['cnt'] . "</td></tr>";

                    // logic for store managers
                    if ($_SESSION["type"] == "StoreManager") {
                        $username = $_SESSION["username"];
                        $queryUserStore = "SELECT COUNT(*) AS cnt FROM manages WHERE Username = '$username';";
                        $resultUserStore = mysqli_query($db, $queryUserStore);
                        $rowUserStore = mysqli_fetch_assoc($resultUserStore);
                        echo "<tr> <td> Number of user stores </td> <td> " .$rowUserStore["cnt"] . "</td></tr>";
                    }
                    ?>
                </table>
            </form>
            <?php
                if($_SESSION["type"] == "StoreManager") {
                    echo "<br>";
                    echo "<div><a href='details.php' class='detail'><b>Click here to see store details</b></a></div>";
                }
            ?>
        </div>
        <div class="clear"></div>
        <div class="title_name">Menu</div>
        <?php
            if($_SESSION["type"] != "StoreManager") {
                echo "<a href='dashboard.php' class='btn btn-secondary btn-lg btn-block'>View Reports</a>";
            }
        ?>


        <?php
            if($_SESSION["type"] == "Marketing") {
                echo "<a href='update.php' class='btn btn-secondary btn-lg btn-block'>Update the population</a>";
            }
        ?>

    </div>
</div>
</body>
</html>

