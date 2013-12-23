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
					<a href="about.php"><li >About</li></a>
					<a href="contact.php"><li id="active">Contact</li></a>
					<a href="login.php"><li>Login</li></a>
				</ul>
			</div>
		</div>
		<div id="clear"> </div>
		<div id="containerbox">
			<div id="navigation">
					<a href="index.php" >Home</a> » <b> Contact </b>
				</div>
				<div id="container">
				<h1>Contact Us</h1>
				
					<p>If you have business inquiries or other questions, please fill out the following form to contact us. Thank you. </p>
					<i>Fields with * are required.</i>
					<form action="#" method="post" id="contactform">
						<label for="name"><b>Name <span class="color_red">*</span></b><br></label>
						<input type="text"><br>
						<label for="name"><b>Email <span class="color_red">*</span></b><br></label>
						<input type="text"><br>
						<label for="name"><b>Subject <span class="color_red">*</span></b><br></label>
						<input type="text" class="i_subject"><br>
						<label for="name"><b>Body <span class="color_red">*</span></b><br></label>
						<textarea rows="7" cols="45"> </textarea><br>
						<label for="name"><b>Verification Code</b><br></label>
						<img src="img/captcha.png" alt="captcha">
						<a href="#">Get a new code</a> 
						 <input type="text" class=""><br>
						 <p class="cp"> Please enter the letters as they are shown in the image above.Letters are not case-sensitive.</p>
						 <input type="submit" value="Submit">
					</form>
				</div>
			
			<?php 
			 	
			 	include 'footer.php';
			 ?>
		</div>
	</body>
</html>