<?php

include("lib/common.php");
include("lib/header.php");

?>
<title>Dashboard</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>
    <div class="center_content">
        <div class="title_name">Select a report</div>
        <a href="report1.php" class="btn btn-secondary btn-lg btn-block">Report 1 - Manufacturer's Product Report</a>
        <a href="report2.php" class="btn btn-secondary btn-lg btn-block">Report 2 - Category Report</a>
        <a href="report3.php" class="btn btn-secondary btn-lg btn-block">Report 3 - Actual vs Predicted Revenue (Couches & Sofas)</a>
        <a href="report4.php" class="btn btn-secondary btn-lg btn-block">Report 4 - Store Revenue by Year by State</a>
        <a href="report5.php" class="btn btn-secondary btn-lg btn-block">Report 5 - Outdoor Furniture on Groundhog Day</a>
        <a href="report6.php" class="btn btn-secondary btn-lg btn-block">Report 6 - State with Highest Volume per Category</a>
        <a href="report7.php" class="btn btn-secondary btn-lg btn-block">Report 7 - Revenue by Population</a>
        <a href="report8.php" class="btn btn-secondary btn-lg btn-block">Report 8 - Grand Showcase Revenue Comparison</a>
        <a href="report9.php" class="btn btn-secondary btn-lg btn-block">Report 9 - Grand Showcase Category Comparison</a>
    </div>
</div>
</body>
</html>

