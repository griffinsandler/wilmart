<?php

include("lib/common.php");
include("lib/header.php");
if ((strcmp($_SESSION['type'], "Marketing")) && (strcmp($_SESSION['type'], "Corporate"))) {
    header('Location: menu.php');
    exit();
}

$query = "WITH TotalRevenue AS (
    SELECT
    YEAR(sld.Date) AS year, s.CityName,
    CASE WHEN bd.savingsday = 1  THEN p.price * (1-bd.percentdiscount) * sld.quantity
		 WHEN bd.percentdiscount IS NULL AND dc.discountprice IS NOT NULL THEN dc.discountprice  * sld.quantity
		 ELSE p.price * sld.quantity END AS Revenue
    FROM store s
    JOIN city c ON c.CityName = s.CityName AND c.State = s.State
    JOIN sold sld ON sld.StoreID = s.StoreID 
    JOIN product p ON p.PID = sld.PID
    JOIN `business day` bd ON bd.Date = sld.Date 
    LEFT JOIN `discounted on` dc ON dc.PID = p.PID AND dc.Date = bd.Date
    )
    SELECT year,
    CONCAT(FORMAT(SUM(CASE WHEN c.Population < 3700000 THEN Revenue END), 2), '$') AS small,
    CONCAT(FORMAT(SUM(CASE WHEN c.Population  BETWEEN 3700000 AND 6700000 THEN Revenue END), 2), '$') AS medium,
    CONCAT(FORMAT(SUM(CASE WHEN c.Population  BETWEEN 6700000 AND 9000000 THEN Revenue END), 2), '$') AS large,
    CONCAT(FORMAT(SUM(CASE WHEN c.Population  > 9000000 THEN Revenue END), 2), '$') AS `extra large`
    FROM TotalRevenue
    JOIN city c ON c.CityName = TotalRevenue.CityName
    GROUP BY year
    ORDER BY year;";

$result = mysqli_query($db, $query);

?>

<title>Report of The Revenue by Population</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>
    <div class="center_content">
        <div class="title_name">Revenue by Population</div>
        <div class="main_table">
            <form name="drilldownform" action="">
                <table border="1">
                    <tr>
                        <td><b>Year</b></td>
                        <td><b>Small</b></td>
                        <td><b>Medium</b></td>
                        <td><b>Large</b></td>
                        <td><b>Extra Large</b></td>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        echo "<tr><td>" . $row['year'] . "</td>";
                        echo "<td>" . $row['small'] . "</td>";
                        echo "<td>" . $row['medium'] . "</td>";
                        echo "<td>" . $row['large'] . "</td>";
                        echo "<td>" . $row['extra large'] . "</td>";
                    }
                    ?>
                </table>
            </form>
        </div>
        <div class="clear"></div>
    </div>
</div>
</body>
</html>

