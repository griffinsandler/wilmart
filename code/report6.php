<?php

include("lib/common.php");
include("lib/header.php");
if ((strcmp($_SESSION['type'], "Marketing")) && (strcmp($_SESSION['type'], "Corporate"))) {
    header('Location: menu.php');
    exit();
}

$monthQuery = "SELECT DISTINCT month(Date) as month FROM `business day`";
$yearQuery = "SELECT DISTINCT year(Date) as year FROM `business day`";

if(isset($_POST['select'])) {

    $year = $_POST['yearDropDown'];
    $month = $_POST['monthDropDown'];
    // we add a 0 to the month if it's only one character
    if (strlen($month) == 1) {
        $month = "0" . $month;
    }

    $query = "WITH VolumePerCategory (category, state, volume) AS (
        SELECT cat.Name AS category, st.State AS state, SUM(s.Quantity)
        FROM sold s
        LEFT JOIN product p ON p.PID = s.PID
        LEFT JOIN store st ON s.StoreID = st.StoreID
        LEFT JOIN city c ON st.State = c.State AND st.CityName = c.CityName
        LEFT JOIN isincategory IIC on p.PID = iic.PID
        LEFT JOIN category cat ON cat.Name = iic.CatName
        WHERE s.Date IN (
            SELECT Date FROM `business day`
            WHERE Date LIKE CONCAT('$year','-','$month', '%')
        )
        GROUP BY cat.Name, c.State
        )
        SELECT v1.category, v1.state, v1.volume
        FROM VolumePerCategory v1
        JOIN (
            SELECT category, state, MAX(volume) AS maxvol
            FROM VolumePerCategory 
            GROUP BY category) v2 ON v1.category = v2.category AND v1.volume = v2.maxvol
        GROUP BY v1.category
        ORDER BY v1.category ASC;";

    $result = mysqli_query($db, $query);
}
?>

<title>Report of The States with Highest Volume for each Category</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>
    <div class="center_content">
            <div class="title_name">Select Year And Month</div>
            <form name="dropdown" action="report6.php" method="POST">

                <?php if ($yearResult = mysqli_query($db, $yearQuery)) {
                    if(mysqli_num_rows($yearResult) > 0) {
                        echo "<select class='btn btn-secondary btn-lg' name='yearDropDown' >";
                        while ($row = mysqli_fetch_array($yearResult)) {
                            $yearSelected = $row['year'];
                            echo "<option value=$yearSelected>$yearSelected</option>";
                        }
                        echo "</select>";
                    }
                }

                // populate dropdowns
                if ($monthResult = mysqli_query($db, $monthQuery)) {
                    if(mysqli_num_rows($yearResult) > 0) {
                        echo "<select class='btn btn-secondary btn-lg' name='monthDropDown'>";
                        while ($row = mysqli_fetch_array($monthResult)) {
                            $monthSelected = $row['month'];
                            echo "<option selected='selected' value=$monthSelected>$monthSelected</option>";
                        }
                        echo "</select>";
                    }
                } ?>
                <input type="submit" class="btn btn-secondary btn-lg" name="select" value="Submit"/>

            </form>
        <div class="main_table">
            <div class="title_name">State with Highest Volume for each Category</div>
            <form name="drilldownform" action="report6.php" method="POST">
                <table border="1">
                    <tr>
                        <td><b>Category</b></td>
                        <td><b>State</b></td>
                        <td><b>Quantity</b></td>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        echo "<tr><td>" . $row['category'] . "</td>";
                        echo "<td>" . $row['state'] . "</td>";
                        echo "<td>" . $row['volume'] . "</td>";
                    }
                    ?>
                </table>
            </form>
        </div>
        <div class="clear"></div>
</div>
</body>
</html>

