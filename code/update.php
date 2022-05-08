<?php
include('lib/common.php');
include("lib/header.php");
?>
<style>
    textarea {
        resize: none;
        overflow: auto;
    }
    center_content {
        margin-left: 20px;
    }

</style>
<title>Update city population</title>
</head>


<?php
$stateQuery = "SELECT DISTINCT State FROM city";
$cityQuery = "SELECT DISTINCT CityName FROM city";
?>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>
    <div class="center_content">
        <div class="title_name">Select State and City</div>
        <form name="update" action="update.php" method="POST">
            <?php
            // populate dropdowns
            if ($stateResult = mysqli_query($db, $stateQuery)) {
                if(mysqli_num_rows($stateResult) > 0) {
                    echo "<select class='btn btn-secondary btn-lg' name='stateDropDown'>";
                    while ($row = mysqli_fetch_array($stateResult)) {
                        $state = $row['State'];
                        echo "<option selected='selected' value='$state'>$state</option>";
                    }
                    echo "</select>";
                }
            }
            if ($cityResult = mysqli_query($db, $cityQuery)) {
                if(mysqli_num_rows($cityResult) > 0) {
                    echo "<select class='btn btn-secondary btn-lg' name='cityDropDown'>";
                    while ($row = mysqli_fetch_array($cityResult)) {
                        $city = $row['CityName'];
                        echo "<option selected='selected' value='$city'>$city</option>";
                    }
                    echo "</select>";
                }
            }
            ?>
            <input type="submit" class="btn btn-secondary btn-lg" name="select" value="Check"/>
        </form>

    <?php
        if (isset($_POST['select'])) {
            $chosenState = $_POST["stateDropDown"];
            $chosenCity = $_POST["cityDropDown"];
            $checkPairQuery = "SELECT Population 
                                        FROM city 
                                        WHERE State = '$chosenState' AND 
                                        CityName = '$chosenCity';";

            if ($checkPairResult = mysqli_query($db, $checkPairQuery)) {
                if (mysqli_num_rows($checkPairResult) > 0) {
                    while ($row = mysqli_fetch_array($checkPairResult)) {
                        $population = $row["Population"];
                        echo "<div class='title_name'>Current Population</div>
                              <textarea rows='1' cols='50' class='form-control' selected='selected' value=$population>$population</textarea>";
                        echo "<div class='title_name'>New Population</div>
                           <form name='updatePopulation' action='update.php' method='POST'>           
                              <input type='text' class='form-control' name='population'> <br>
                              <input type='submit' class='btn btn-secondary btn-lg' name='update' value='update'/>
                              <input type='hidden' name='chosenState' value='$chosenState'>
                               <input type='hidden' name='chosenCity' value='$chosenCity'>
                           </form>";
                    }
                }
                else {
                    echo "The City / State pair does not exist";
                }
            }
        }
        if(isset($_POST['update'])) {
            $newPopulation = $_POST["population"];
            $chosenState = $_POST["chosenState"];
            $chosenCity = $_POST["chosenCity"];

            // check if the value is a integer
            $updatePopulationQuery = "UPDATE city
                                      SET
                                      Population = '$newPopulation'
                                      WHERE CityName = '$chosenCity'
                                      AND State = '$chosenState'";
            if (is_numeric($newPopulation) && intval($newPopulation) >= 0) {
                mysqli_query($db, $updatePopulationQuery);
                echo "Record updated successfully";
            } else {
                die("Error : Enter a positive integer");
            }
        }
    ?>
    </div>
</div>
</body>
</html>
