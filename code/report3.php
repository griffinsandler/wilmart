<?php

include('lib/common.php');
// written by bdunham3

if ((strcmp($_SESSION['type'], "Marketing")) && (strcmp($_SESSION['type'], "Corporate"))) {
	header('Location: menu.php');
	exit();
}

	//Start by getting a list of manufacturers
    $query = "SELECT PID FROM IsInCategory WHERE CatName = \"Couches and Sofas\"";
    $result = mysqli_query($db, $query);

if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
	//Create list of manufacturers
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
	{
		$pid_array[] = $row['PID'];
	}

	//Use our list of PIDS to find all Salse for all products
	foreach($pid_array as &$my_pid)
	{
		//Start by pulling details for the product
		$query = "SELECT Price, Name FROM Product WHERE PID = $my_pid";
		$result = mysqli_query($db, $query);
		if(mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				$price_array[$my_pid] = $row['Price'];
				$name_array[$my_pid] = $row['Name'];
				$pid_price_ary[$row['PID']] = $row['Price'];
			}
		}
		
		//Now find how many products sold at retail price
		$quantity_array = array(); 
		$query = "SELECT Quantity FROM Sold WHERE PID = $my_pid AND " .
			"Sold.Date NOT IN (SELECT Date FROM `Discounted On` WHERE PID = $my_pid)";
		$result = mysqli_query($db, $query);
		if(mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				$quantity_array[] = $row['Quantity'];
			}
		}
		$retail_array[$my_pid] = array_sum($quantity_array);
		
		//Now find how many products sold at discount price (and how much revenue)
		$quantity_array = array();
		$query = "SELECT DISTINCT Quantity, DiscountPrice FROM Sold, `Discounted On` WHERE Sold.PID = $my_pid AND " .
			"Sold.Date IN (SELECT Date FROM `Discounted On` WHERE PID = $my_pid) AND " .
			"Sold.Date = `Discounted On`.Date AND `Discounted On`.PID = $my_pid";

		$result = mysqli_query($db, $query);
		if(mysqli_num_rows($result) > 0)
		{
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				$quantity_array[] = $row['Quantity'];
				$drev_array[$my_pid] += ($row['Quantity'] * $row['DiscountPrice']);
			}
		}
		$discounted_array[$my_pid] = array_sum($quantity_array);
		$rrev_array[$my_pid] = ($retail_array[$my_pid] * $price_array[$my_pid]);
		
		//Actual and predicted Revenue (and the difference)
		$arev_array[$my_pid] = $drev_array[$my_pid] + $rrev_array[$my_pid];
		$prev_array[$my_pid] = $rrev_array[$my_pid] + (.75 * $discounted_array[$my_pid] * $price_array[$my_pid]);
		$rev_diff_array[$my_pid] = $arev_array[$my_pid] - $prev_array[$my_pid];
		
		
		//Create our sorted list of pids (sorted by difference); $5000ABV and above
		if(abs($rev_diff_array[$my_pid]) > 5000)
		{
			if (count($pid_array_sorted) > 0)
			{
				for($i = 0; $i < count($pid_array_sorted); $i++)
				{
					if($rev_diff_array[$my_pid] > $rev_diff_array[$pid_array_sorted[$i]])
					{
						array_splice($pid_array_sorted, $i, 0, $my_pid);
						break;
					}
					if ($i == (count($pid_array_sorted)-1))
					{
						$pid_array_sorted[] = $my_pid;
						break;
					}
				}
			}
			else
			{
				$pid_array_sorted[] = $my_pid;
			}
		}
		
	}
	
}


?>

<?php include("lib/header.php"); ?>
		<title>Actual and Predicted Revenue</title>
	</head>

	<body>
		<div id="main_container">
      <?php include("lib/menu.php"); ?>
			<div class="center_content">
					<div class="title_name">Actual vs. Predicted Revenue (Couches and Sofas)</div>
            <div class="main_table"> 
                <form name="drilldownform" action="report1.php" method="POST">
                  <table border="1">
                  <tr>
                    <td><b>Product ID</b></td>
                    <td><b>Product Name</b></td>
                    <td><b>Retail Price</b></td>
                    <td><b>Total Sold</b></td>
                    <td><b>Sold (Discount)</b></td>
                    <td><b>Sold (Retail)</b></td>
                    <td><b>Actual Revenue</b></td>
					<td><b>Predicted Revenue</b></td>
					<td><b>Difference</b></td>
                  </tr>
                  <?php
				  setlocale(LC_MONETARY, 'en_US');
					foreach($pid_array_sorted as &$pid)
					{
						echo "<tr><td>" . $pid . "</td>";
						echo "<td>" . $name_array[$pid] . "</td><td>";
						printf("$%01.2f", $price_array[$pid]);
						echo "</td><td>" . ($discounted_array[$pid] + $retail_array[$pid]) . "</td>";
						echo "<td>" . $discounted_array[$pid] . "</td>";
						echo "<td>" . $retail_array[$pid] . "</td><td>";
						printf("$%01.2f", $arev_array[$pid]);
						echo "</td><td>";
						printf("$%01.2f", $prev_array[$pid]);
						echo "</td><td>";
						printf("$%01.2f", $rev_diff_array[$pid]);
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
