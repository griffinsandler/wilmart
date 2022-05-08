<?php

include('lib/common.php');
// written by bdunham3

if ((strcmp($_SESSION['type'], "Marketing")) && (strcmp($_SESSION['type'], "Corporate"))) {
	header('Location: menu.php');
	exit();
}

	//Start by getting a list of manufacturers
    $query = "SELECT Name FROM Manufacturer";
    $result = mysqli_query($db, $query);

if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
	//Create list of manufacturers
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
	{
		$man_name_array[] = $row['Name'];
	}

	//Use our list of manufacturers to find all prices for all products offered
	foreach($man_name_array as &$man_name)
	{
		$pid_array = array();
		$query = "SELECT COUNT(PID) AS pidcount, MAX(Price) AS maxprice, " .
		"MIN(Price) AS minprice, AVG(Price) AS avgprice FROM Product WHERE Mname = \"$man_name\" ";
			
		$result = mysqli_query($db, $query);
		if(mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				$count_array[$man_name] = $row['pidcount'];
				$avg_array[$man_name] = $row['avgprice'];
				$min_array[$man_name] = $row['minprice'];
				$max_array[$man_name] = $row['maxprice'];
			}
		}
		
		$query = "SELECT PID, Name, Price FROM Product WHERE Mname = \"$man_name\" ORDER BY Price DESC";
		$result = mysqli_query($db, $query);
		if(mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				$pid_array[] = $row['PID'];
				$all_pids[] = $row['PID'];
				$pid_name_ary[$row['PID']] = $row['Name'];
				$pid_price_ary[$row['PID']] = $row['Price'];
			}
		}

		$man_pid_array[$man_name] = $pid_array;
		
		//Create our sorted list of manufacturers (sorted by average price)
		if (count($man_name_array_sorted) > 0)
		{
			for($i = 0; $i < count($man_name_array_sorted); $i++)
			{
				if($avg_array[$man_name] > $avg_array[$man_name_array_sorted[$i]])
				{
					array_splice($man_name_array_sorted, $i, null, $man_name);
					break;
				}
				if ($i == (count($man_name_array_sorted)-1))
				{
					$man_name_array_sorted[] = $man_name;
					break;
				}
			}		
		}
		else
		{
			$man_name_array_sorted[] = $man_name;
		}
	}
	
	//Limit to only the top 100 manufacturers
	$man_name_array_sorted = array_slice($man_name_array_sorted, 0, 100);
	
	foreach($all_pids as &$my_pid)
	{
		$query = "SELECT GROUP_CONCAT(CatName SEPARATOR ', ') AS categories " .
				"FROM IsInCategory WHERE PID = \"$my_pid\"";
		
		$result = mysqli_query($db, $query);
		if(mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				$pid_cat_ary[$my_pid] = $row['categories'];
			}
		}
	}

	
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	//Figure out which button is pressed (which manufacturer)
	foreach ($_POST['Submit'] as &$man_name_post)
	{
		$drilldown = "true";
	}

}

?>

<?php include("lib/header.php"); ?>
		<title>Manfacturer Product Report</title>
	</head>

	<body>
		<div id="main_container">
      <?php include("lib/menu.php"); ?>
			<div class="center_content">
					<div class="title_name">Manufacturer's Product Report</div>
            <div class="main_table"> 
                <form name="drilldownform" action="report1.php" method="POST">
                  <table border="1">
                  <tr>
                    <td><b>Manufacturer Name</b></td>
                    <td><b>Number of Products</b></td>
                    <td><b>Average Price</b></td>
                    <td><b>Minimum Price</b></td>
                    <td><b>Maximum Price</b></td>
					<td><b>Details</b></td>
                  </tr>
                  <?php
					$index = 0;
					foreach($man_name_array_sorted as &$man_name)
					{
						echo "<tr><td>" . $man_name . "</td>";
						echo "<td>" . $count_array[$man_name] . "</td>";
						echo "<td>";
						printf("$%01.2f", $avg_array[$man_name]);
						echo "</td><td>";
						printf("$%01.2f", $min_array[$man_name]);
						echo "</td><td>";
						printf("$%01.2f", $max_array[$man_name]);
						echo "</td><td><input type=\"submit\" name=\"Submit[" . $index . "]\" value=\"" . $man_name . "\" /></td></tr>";
						$index++;
					}
                  ?>
                  </table>
                </form>
            </div>
          <div class="center_lower">
            <div class="title_name">Manufacturer's Details</div>
			<?php
			if(isset($drilldown)){
				echo "<div class=\"subtitle\">" . $man_name_post . "</div>";
			}
			?>
				<table border="1">
				<tr>
				  <td><b>Product ID</b></td>
				  <td><b>Product Name</b></td>
				  <td><b>Categories</b></td>
				  <td><b>Price</b></td>
				</tr>
			<?php				
			if (isset($drilldown)) {
				foreach($man_pid_array[$man_name_post] as $pid_display)
				{
					echo "<td>" . $pid_display . "</td>";
					echo "<td>" . $pid_name_ary[$pid_display] . "</td>";
					echo "<td>" . $pid_cat_ary[$pid_display] . "</td>";
					echo "<td>";
					printf("$%01.2f", $pid_price_ary[$pid_display]);
					echo "</td></tr>";
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
