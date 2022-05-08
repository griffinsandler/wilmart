<?php

include("lib/common.php");
include("lib/header.php");
if ((strcmp($_SESSION['type'], "Marketing")) && (strcmp($_SESSION['type'], "Corporate"))) {
	header('Location: menu.php');
	exit();
}

#if(isset($_POST['select'])) {


    $table_query = "WITH product_sales_by_showcase AS (
						SELECT p.PID, 
						SUM(CASE WHEN st.willsgrandshowcase = 1 THEN sld.quantity ELSE 0 END) gs_sold,
						SUM(CASE WHEN st.willsgrandshowcase = 0 THEN sld.quantity ELSE 0 END) ngs_sold
						FROM Product p
						LEFT JOIN Sold sld
							ON p.PID = sld.PID
						LEFT JOIN Store st
						ON sld.StoreID = st.StoreID
						GROUP BY p.PID
                    )
                    SELECT c.Name AS Category,
                    	SUM(COALESCE(ngs_sold,0)) AS Non_Showcase_Qty,
                        SUM(COALESCE(gs_sold,0)) AS Grand_Showcase_Qty,
                        (SUM(COALESCE(ngs_sold,0)) - SUM(COALESCE(gs_sold,0))) AS Difference
                    FROM Category c
                    LEFT JOIN IsInCategory iic
                        ON c.Name = iic.CatName
                    LEFT JOIN product_sales_by_showcase pss
                        ON iic.PID = pss.PID
                    GROUP BY c.Name
                    ORDER BY (SUM(COALESCE(gs_sold,0)) - SUM(COALESCE(ngs_sold,0))) DESC,
                        c.Name ASC;";

    $table_result = mysqli_query($db, $table_query);


     if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Figure out which button is pressed (which category)
    foreach ($_POST['submit'] as &$cat_name)
    {
        $drilldown = "true";
    }}

    $drill_query = "WITH product_sales_by_showcase AS (
                        SELECT p.PID, 
                        p.Name,
                        SUM(CASE WHEN st.willsgrandshowcase = 1 THEN sld.quantity ELSE 0 END) gs_sold,
                        SUM(CASE WHEN st.willsgrandshowcase = 0 THEN sld.quantity ELSE 0 END) ngs_sold
                        FROM Product p
                        LEFT JOIN Sold sld
                            ON p.PID = sld.PID
                        LEFT JOIN Store st
                        ON sld.StoreID = st.StoreID
                        GROUP BY p.PID, p.Name
                    ),
					top_5 as (
						SELECT  pss.PID,
							pss.Name,
							SUM(COALESCE(ngs_sold,0)) AS Non_Showcase_Qty,
							SUM(COALESCE(gs_sold,0)) AS Grand_Showcase_Qty,
							(SUM(COALESCE(ngs_sold,0)) - SUM(COALESCE(gs_sold,0))) AS Difference
						FROM Category c
						LEFT JOIN IsInCategory iic
							ON c.Name = iic.CatName
						LEFT JOIN product_sales_by_showcase pss
							ON iic.PID = pss.PID
						WHERE c.Name = 'Bamboo furniture'
						GROUP BY pss.PID, pss.Name
						ORDER BY Difference DESC, pss.PID ASC
						limit 5
					),
					bottom_5 as (
						SELECT pss.PID,
							pss.Name,
							SUM(COALESCE(ngs_sold,0)) AS Non_Showcase_Qty,
							SUM(COALESCE(gs_sold,0)) AS Grand_Showcase_Qty,
							(SUM(COALESCE(ngs_sold,0)) - SUM(COALESCE(gs_sold,0))) AS Difference
						FROM Category c
						LEFT JOIN IsInCategory iic
							ON c.Name = iic.CatName
						LEFT JOIN product_sales_by_showcase pss
							ON iic.PID = pss.PID
						WHERE c.Name = 'Bamboo furniture'
						GROUP BY pss.PID, pss.Name
						ORDER BY Difference ASC, PID ASC
						limit 5
					),
                    pre_sort_table as (
                        SELECT *
                        FROM top_5

                        UNION ALL

                        SELECT *
                        FROM bottom_5)
                    select * from pre_sort_table
                    order by Difference desc;
                    ";

    $drill_result = mysqli_query($db, $drill_query);


?>

<title>Grand Showcase Store Category Comparison</title>

</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>
    <div class="center_content">

        <div class="main_table">
			<div class="title_name">Grand Showcase Store Category Comparison</div>
            <form name="drilldownform" action="report9.php" method="POST">
                <table border="1">
                    <tr>
                        <td><b>Category</b></td>
                        <td><b>Grand Showcase Quantity</b></td>
                        <td><b>Non Grand Showcase Quantity</b></td>
                        <td><b>Difference</b></td>
                        <td><b>Details</b></td>
                    </tr>
                    <?php
                    $index = 0;
                    while ($row = mysqli_fetch_assoc($table_result))
                    {
                        echo "<tr><td>" . $row['Category'] . "</td>";
                        echo "<td>" . $row['Grand_Showcase_Qty'] . "</td>";
                        echo "<td>" . $row['Non_Showcase_Qty'] . "</td>";
                        echo "<td>" . $row['Difference'] . "</td>";
                        echo "<td><input type=\"submit\" name=\"submit[" . $index . "]\" value=\"" . $row['Category'] . "\" /></td></tr>";
                        $index++;
                    }
                    ?>

                </table>
            </form>
        </div>

        <div class="center_lower">
                <div class="title_name">Category Details</div>
                    <?php
                    if(isset($drilldown)){
                        echo "<div class=\"subtitle\">" . $cat_name . "</div>";
                    }
                    ?>
                <table border="1">
                        <tr>
                          <td><b>Product ID</b></td>
                          <td><b>Product Name</b></td>
                          <td><b>Grand Showcase Quantity</b></td>
                          <td><b>Non Grand Showcase Quantity/b></td>
                          <td><b>Difference</b></td>
                        </tr>
                    <?php               
                    if (isset($drilldown)) {
                        while ($row = mysqli_fetch_assoc($drill_result))
                        {
                            echo "<tr><td>" . $row['PID'] . "</td>";
                            echo "<td>" . $row['Name'] . "</td>";
                            echo "<td>" . $row['Grand_Showcase_Qty'] . "</td>";
                            echo "<td>" . $row['Non_Showcase_Qty'] . "</td>";
                            echo "<td>" . $row['Difference'] . "</td></tr>";
                        }
                    }
                  ?>
				</table>
		</div> 

        <div class="clear"></div>
</div>
</div>
</body>
</html>

