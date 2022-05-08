<?php

include("lib/common.php");
include("lib/header.php");
if ((strcmp($_SESSION['type'], "Marketing")) && (strcmp($_SESSION['type'], "Corporate"))) {
	header('Location: menu.php');
	exit();
}



$stateQuery = "SELECT DISTINCT state as state FROM City";

if(isset($_POST['select'])) {

    $state = $_POST['stateDropDown'];

    $query = "Select t.storeID, a.CityName, a.Address, year, ROUND(t.TotalRevenue, 2) as TotalRevenue FROM (Select year(t.date) as year, t.storeID, sum(t.TotalRevenue) as TotalRevenue FROM ((Select t.date, t.storeId, t.Revenue*(1-b.PercentDiscount) as TotalRevenue FROM (Select g.Date, g.StoreID, sum(g.Revenue) as Revenue FROM ((Select t.PID, t.Date,t.StoreID,t.Quantity*t.Price as Revenue FROM (SELECT p.PID, s.Date, st.StoreID, s.Quantity, p.Price FROM Product as p JOIN SOLD as s JOIN Store as st on p.PID = s.PID and s.StoreID = st.StoreID WHERE st.State = '$state') as t LEFT JOIN
    (SELECT d.PID, d.Date, d.Discountprice FROM `Discounted On` AS d) as g on t.PID = g.PID and t.Date = g.Date where g.Discountprice is NULL) Union 
    (Select t.PID, t.Date, t.StoreID, t.Quantity*g.Discountprice as Revenue FROM (SELECT p.PID, s.Date, st.StoreID, s.Quantity, p.Price FROM Product as p JOIN SOLD as s JOIN Store as st on p.PID = s.PID and s.StoreID = st.StoreID WHERE st.State = '$state') as t LEFT JOIN
    (SELECT d.PID, d.Date, d.Discountprice FROM `Discounted On` AS d) as g on t.PID = g.PID and t.Date = g.Date where g.Discountprice is  not NULL)) as g group by g.DAte, g.StoreID) as t LEFT JOIN `business day`as b on t.date = b.date)) as t group by year, t.storeid order by year asc, TotalRevenue desc) as t LEFT JOIN `Store` as a on t.storeID = a.storeID;";

    $result = mysqli_query($db, $query);
}
?>

<title>Report of Store Revenue by Year by State</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>
    <div class="center_content">
            <div class="title_name">Select State</div>
            <form name="dropdown" action="report4.php" method="POST">

                <?php if ($stateResult = mysqli_query($db, $stateQuery)) {
                    if(mysqli_num_rows($stateResult) > 0) {
                        echo "<select class='btn btn-secondary btn-lg' name='stateDropDown'>";
                        while ($row = mysqli_fetch_array($stateResult)) {
                            echo "<option value='".$row['state']."'>" .$row['state']. "</option>";
                        }
                        echo "</select>";
                    }
                } ?>
                <input type="submit" class="btn btn-secondary btn-lg" name="select" value="Submit"/>

            </form>
        <div class="main_table">
            <div class="title_name">Report of Store Revenue by Year by State</div>
            <form name="drilldownform" action="report4.php" method="POST">
                <table border="1">
                    <tr>
                        <td><b>Store ID</b></td>
                        <td><b>Address</b></td>
                        <td><b>City Name</b></td>
                        <td><b>Year</b></td>
                        <td><b>Total Revenue</b></td>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        echo "<tr><td>" . $row['storeID'] . "</td>";
                        echo "<td>" . $row['Address'] . "</td>";
                        echo "<td>" . $row['CityName'] . "</td>";
                        echo "<td>" . $row['year'] . "</td>";
                        echo "<td>" . "$" . $row['TotalRevenue'] . "</td>";
                    }
                    ?>
                </table>
            </form>
        </div>
        <div class="clear"></div>
</div>
</body>
</html>
