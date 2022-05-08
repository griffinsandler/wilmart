<?php
include("lib/common.php");
include("lib/header.php");
if ((strcmp($_SESSION['type'], "Marketing")) && (strcmp($_SESSION['type'], "Corporate"))) {
	header('Location: menu.php');
	exit();
}


$query = "SELECT q.year, q.total, ROUND(q.average,2) as average, a.groundhogs FROM

(SELECT year(s.Date) as year, sum(s.Quantity) as total, sum(s.Quantity)/365 as average FROM Sold as s Left Join isincategory as c on s.PID = c.PID WHERE c.CatName = 'Outdoor Furniture' GROUP BY(year) ORDER BY Year ASC) as q

LEFT JOIN

(SELECT year(s.Date) as year, sum(s.Quantity) as groundhogs FROM Sold as s Left Join isincategory as c on s.PID = c.PID WHERE c.CatName = 'Outdoor Furniture' and s.Date LIKE '____-02-02' GROUP BY year ORDER BY Year ASC) as a

on a.year = q.year;";
$result = mysqli_query($db, $query);

?>

<title>Outdoor Furniture Sales on Groundhog Day</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>
    <div class="center_content">
        <div class="main_table">
            <div class="title_name">Outdoor Furniture on Sales on Groundhog Day</div>
            <form name="drilldownform" action="report5.php" method="POST">
                <table border="1">
                    <tr>
                        <td><b>Year</b></td>
                        <td><b>Total Unit Sales</b></td>
                        <td><b>Average Unit Sales Per Day</b></td>
                        <td><b>Unit Sales on Groundhog Day</b></td>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        echo "<tr><td>" . $row['year'] . "</td>";
                        echo "<td>" . $row['total'] . "</td>";
                        echo "<td>" . $row['average'] . "</td>";
                        echo "<td>" . $row['groundhogs'] . "</td>";
                    }
                    ?>
                </table>
            </form>
        </div>
        <div class="clear"></div>
</div>
</body>
</html>