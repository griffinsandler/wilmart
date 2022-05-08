<?php
include("lib/header.php");
?>

<title>Login page</title>
</head>

<style>
    .main_table {
        margin-left: 10px;
    }
</style>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>
    <div class="center_content">
        <div class="main_table">
            <form name="loginForm" method="POST" action="processlogin.php">
                <div>
                    <label class="col-form-label">Username</label>
                    <input type="text" name="username" class="form-control" aria-describedby="passwordHelpBlock" placeholder="Enter username">
                </div>
                <div>
                    <label class="col-form-label">Password</label>
                    <input type="password" class="form-control" aria-describedby="passwordHelpInline" name="password" placeholder="Enter password">
                </div>
                <div>
                    <br>
                    <button id="submit" class="btn btn-secondary btn-lg" name="submit">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
