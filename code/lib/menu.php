
			<div id="header">
                <div class="logo"><img src="img/gtonline_logo.png" style="opacity:0.6;background-color:E9E5E2;" border="0" alt="" title="GT Online Logo"/></div>
			</div>
			
			<div class="nav_bar">
				<ul>    
                    <li><a href="login.php" <?php if($current_filename=='view_profile.php') echo "class='active'"; ?>><b>Log In</b></a></li>
					<li><a href="menu.php" <?php if(strpos($current_filename, 'edit_profile.php') !== false) echo "class='active'"; ?>><b>Main Menu</b></a></li>
                    <li><a href="logout.php" <span class='glyphicon glyphicon-log-out'></span> <b>Log Out</b></a></li>
				</ul>
			</div>