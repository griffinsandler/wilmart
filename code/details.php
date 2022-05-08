<?php

include("lib/common.php");
include("lib/header.php");

$username = $_SESSION["username"];
$query = "SELECT s.StoreID,s.Address,s.PhoneNumber,s.WillsGrandShowcase 
          FROM store s JOIN manages m ON m.StoreID = s.StoreID 
          WHERE m.Username = '$username';";
$result = mysqli_query($db, $query);
?>

<title>Report of The Revenue by Population</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>
    <div class="center_content">
        <div class="title_name">Store details</div>
        <div class="main_table">
            <form name="drilldownform" action="">
                <table border="1">
                    <tr>
                        <td><b>Store Number</b></td>
                        <td><b>Phone Number</b></td>
                        <td><b>Address</b></td>
                        <td><b>WillsGrandShowcase</b></td>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        echo "<tr><td>" . $row['StoreID'] . "</td>";
                        echo "<td>" . $row['PhoneNumber'] . "</td>";
                        echo "<td>" . $row['Address'] . "</td>";
                        if ($row['WillsGrandShowcase'] == 0) {
                            echo "<td>Yes</td>";
                        } else {
                            echo "<td>No</td>";
                        }
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

