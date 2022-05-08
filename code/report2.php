<?php

include('lib/common.php');
// written by bdunham3

if ((strcmp($_SESSION['type'], "Marketing")) && (strcmp($_SESSION['type'], "Corporate"))) {
	header('Location: menu.php');
	exit();
}

	//Start by getting a list of manufacturers
    $query = "SELECT Name FROM Category ORDER BY Name ASC";
    $result = mysqli_query($db, $query);

if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
	//Create list of manufacturers
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
	{
		$cat_name_array[] = $row['Name'];
	}

	//Use our list of categories to find all prices for all products offered
	foreach($cat_name_array as &$cat_name)
	{
		$price_array = array();
		$query = "SELECT COUNT(Product.PID) AS pidcount, MIN(Price) AS minprice, AVG(Price) AS avgprice, MAX(Price) AS maxprice FROM " .
		"Product, IsInCategory WHERE Product.PID = IsInCategory.PID AND IsInCategory.CatName = \"$cat_name\"";
		
		$result = mysqli_query($db, $query);
		if(mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				$max_array[$cat_name] = $row['maxprice'];
				$min_array[$cat_name] = $row['minprice'];
				$avg_array[$cat_name] = $row['avgprice'];
				$count_array[$cat_name] = $row['pidcount'];
			}
		}


		
	}
	
}


?>

<?php include("lib/header.php"); ?>
		<title>Category Report</title>
	</head>

	<body>
		<div id="main_container">
      <?php include("lib/menu.php"); ?>
			<div class="center_content">
					<div class="title_name">Category Report</div>
            <div class="main_table"> 
                <form name="drilldownform" action="report1.php" method="POST">
                  <table border="1">
                  <tr>
                    <td><b>Category</b></td>
                    <td><b>Number of Products</b></td>
                    <td><b>Minimum Price</b></td>
                    <td><b>Average Price</b></td>
                    <td><b>Maximum Price</b></td>
                  </tr>
                  <?php
					foreach($cat_name_array as &$cat_name)
					{
						echo "<tr><td>" . $cat_name . "</td>";
						echo "<td>" . $count_array[$cat_name] . "</td><td>";
						printf("$%01.2f", $min_array[$cat_name]);
						echo "</td><td>";
						printf("$%01.2f", $avg_array[$cat_name]);
						echo "</td><td>";
						printf("$%01.2f", $max_array[$cat_name]);
						echo "</td>";
					}
                  ?>
                  </table>
                </form>
            </div>
          <div class="center_lower">
					</table>
				</div> 
				<div class="clear"></div> 
			</div>    
		</div>
	</body>
</html>
