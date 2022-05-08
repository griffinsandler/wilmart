<?php

include("lib/common.php");
include("lib/header.php");
if ((strcmp($_SESSION['type'], "Marketing")) && (strcmp($_SESSION['type'], "Corporate"))) {
	header('Location: menu.php');
	exit();
}

#if(isset($_POST['select'])) {

    $query_t = "WITH StoreRevenue AS (
               SELECT YEAR(sld.date) Year, 
					st.StoreID,
					st.willsgrandshowcase,
					CASE WHEN bd.savingsday = 1  
							THEN p.price * ((100-bd.percentdiscount)/100) * sld.quantity
						WHEN bd.percentdiscount IS NULL AND do.discountprice IS NOT NULL
							THEN do.discountprice  * sld.quantity
						ELSE p.price * sld.quantity
						END AS Revenue
                FROM store st
                LEFT JOIN sold sld
					ON st.StoreID = sld.StoreID
                LEFT JOIN  product p
					ON sld.PID = p.PID
                LEFT JOIN `discounted on` do
                    ON sld.date = do.date
                    AND p.PID = do.PID
                LEFT JOIN `business day` bd
                    ON sld.date = bd.date
                #WHERE st.willsgrandshowcase = 1
                ),
                GrandShowcaseStats AS (
                    SELECT year, 
                MIN(revenue) gs_min_rev, 
                AVG(revenue) gs_avg_rev, 
                MAX(revenue) gs_max_rev,
				SUM(revenue) gs_tot_rev
                    FROM StoreRevenue
                    WHERE willsgrandshowcase = 1
                    GROUP BY year
                ),
                NonShowcaseStats AS (
                    SELECT year, 
                    MIN(revenue) ngs_min_rev, 
                    AVG(revenue) ngs_avg_rev, 
                    MAX(revenue) ngs_max_rev,
					SUM(revenue) ngs_tot_rev
                    FROM StoreRevenue
                    WHERE willsgrandshowcase = 0
                    GROUP BY year
                )
                SELECT gss.year,
                    round(gss.gs_min_rev,2) as Grand_Showcase_Minimum_Revenue,
                    round(gss.gs_avg_rev,2) as Grand_Showcase_Average_Revenue,
                    round(gss.gs_max_rev,2) as Grand_Showcase_Maximum_Revenue,
					round(gss.gs_tot_rev,2) as Grand_Showcase_Total_Revenue,
                    round(nss.ngs_min_rev,2) as Non_Grand_Showcase_Minimum_Revenue,
                    round(nss.ngs_avg_rev,2) as Non_Grand_Showcase_Average_Revenue,
                    round(nss.ngs_max_rev,2) as Non_Grand_Showcase_Maximum_Revenue,
					round(nss.ngs_tot_rev,2) as Non_Grand_Showcase_Total_Revenue
                FROM GrandShowcaseStats gss
                LEFT JOIN NonShowcaseStats nss
                    ON gss.year = nss.year
                where gss.year is not null
                ORDER BY gss.year;";

    $result_t = mysqli_query($db, $query_t);


    $query_h = "SELECT  CASE WHEN willsgrandshowcase = 1 THEN 'Number of Wills Grand Showcase Stores'
                        WHEN willsgrandshowcase = 0 THEN 'Number of Non-Showcase Stores'
                        END AS Store_Type,
                COUNT(DISTINCT StoreID) AS Store_Count
                FROM store
                GROUP BY 1;";

    $result_h = mysqli_query($db, $query_h) ;
#}
?>

<header>
<h3>
	<div class="main_table">
            <form name="drilldownform" action="report8.php" method="POST">
                <table border="1" style="background-color:#FFFFE0;">

					<?php
					while ($row = mysqli_fetch_assoc($result_h))
							{
								echo "<tr><td>" . $row['Store_Type'] . "</td>";
								echo "<td>" . $row['Store_Count'] . "</td>";
							}
					?>
			</table>
		</form>
	</div>
</h3>
</header>


<title>Grand Showcase Store Revenue Comparison</title>
</head>


<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>
	<div class="center_content">
        <div class="main_table">
			<div class="title_name">Grand Showcase Store Revenue Comparison</div>
            <form name="drilldownform" action="report8.php" method="POST">
                <table border="1">
                    <tr>
                        <td><b>Year</b></td>
                        <td><b>Grand Showcase Minimum Revenue</b></td>
                        <td><b>Grand Showcase Average Revenue</b></td>
                        <td><b>Grand Showcase Maximum Revenue</b></td>
						<td><b>Grand Showcase Total Revenue</b></td>
                        <td><b>Non Grand Showcase Minimum Revenue</b></td>
                        <td><b>Non Grand Showcase Average Revenue</b></td>
                        <td><b>Non Grand Showcase Maximum Revenue</b></td>
						<td><b>Non Grand Showcase Total Revenue</b></td>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_assoc($result_t))
                    {
                        echo "<tr><td>" . $row['year'] . "</td>";
						
                        echo "<td>"; 
						printf("$%01.2f", $row['Grand_Showcase_Minimum_Revenue']);
						echo "</td>";
						
                        echo "<td>";
							printf("$%01.2f", $row['Grand_Showcase_Average_Revenue']);
						echo "</td>";
						
                        echo "<td>";
							printf("$%01.2f", $row['Grand_Showcase_Maximum_Revenue']);
						echo "</td>";
						
						echo "<td>";
							printf("$%01.2f", $row['Grand_Showcase_Total_Revenue']);
						echo "</td>";
						
                        echo "<td>";
							printf("$%01.2f", $row['Non_Grand_Showcase_Minimum_Revenue']);
						echo "</td>";
						
                        echo "<td>";
							printf("$%01.2f", $row['Non_Grand_Showcase_Average_Revenue']);
						echo "</td>";
						
                        echo "<td>";
							printf("$%01.2f", $row['Non_Grand_Showcase_Maximum_Revenue']);
						echo "</td>";
						
						echo "<td>";
							printf("$%01.2f", $row['Non_Grand_Showcase_Total_Revenue']);
						echo "</td></tr>";
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

