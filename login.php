<html>
	<head>
		<title>Blog Demo - Post</title>
		<link rel="stylesheet" href="css/style.css">
		<meta charset="utf-8">
	</head>
	<body>
		<div id="header"> 
			<h1 class="obullo">Blog Demo</h1>
			<div id="menu">
				<ul>
					<a href="index.php"><li>Home</li></a>
					<a href="about.php"><li>About</li></a>
					<a href="contact.php"><li>Contact</li></a>
					<a href="login.php"><li id="active">Login</li></a>
				</ul>
			</div>
		</div>
		<div id="clear"> </div>
		<div id="containerbox">
			<div id="content x" class="x mt">
				<div id="navigation">
					<a href="index.php" >Home</a> » <b> Login </b>
				</div>
				<h1>Login</h1>
				<div id="contacttext">
					<p>Please fill out the following form with your login credentials: </p>
					<i>Fields with * are required.</i>
					<form action="index.php" method="post" id="contactform">
						<label for="name"><b id="username_text">Username <span class="color_red">*</span></b><br></label>
						<input type="text" id="username"><br>
						<div id="null_username">Username Cannot be Blank !</div>
						<label for="name"><b id="password_text">Password <span class="color_red">*</span></b><br></label>
						<input type="text" id="password"><br>
						<div id="null_password">Password Cannot be Blank !</div>
						 <p class="cp"> Hint: You may login with demo/demo</p>
						 <input type="checkbox"> <b>Remember me next time</b><br><br>
						 <input type="submit" value="Login">
					</form>
				</div>
				
				

			<div id="clear"></div><div id="blockbottom"> </div>
				

			</div>
			<div id="footer">
				Copyright © 2013 by My Company.
				All Rights Reserved.
				Powered by <a href="#">Obullo Framework</a>. 
			</div>
		</div>
	</body>
</html>