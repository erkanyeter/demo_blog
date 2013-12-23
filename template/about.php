<html>
	<head>
		<title>Blog Demo - Post</title>
		<link rel="stylesheet" href="css/style.css">
		<meta charset="utf-8">
	</head>
	<body>
		<div id="header"> 
			<h1 class="logo">Blog Demo</h1>
			<div id="menu">
				<ul>
					<a href="index.php"><li >Home</li></a>
					<a href="about.php"><li id="active">About</li></a>
					<a href="contact.php"><li>Contact</li></a>
					<a href="login.php"><li>Login</li></a>
				</ul>
			</div>
		</div>
		<div id="clear"> </div>
		<div id="containerbox">
			<div id="content x" class="x mt">
				<div id="navigation">
					<a href="index.php" >Home</a> » <b> About </b>
				</div>
				<h1>About</h1>
				<div id="abouttext">
					<p>This is the "about" page for my blog site.</p>
				</div>
				

				<div id="clear"></div><div id="blockbottom"> </div>
				

			</div>
			
			<?php 
			 	
			 	include 'footer.php';
			 ?>
		</div>
	</body>
</html>