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
					<a href="about.php"><li>About</li></a>
					<a href="contact.php"><li>Contact</li></a>
					<a href="login.php"><li>Login</li></a>
				</ul>
			</div>
		</div>
		<div id="clear"> </div>
		<div id="containerbox">

			<div id="content">
				
				<div id="navigation">
					<a href="index.php" >Home</a> » <b> Create Post </b>
				</div>
				
				<h1 class="h1">Create Post </h1>
				<div id="createpost">
					<i>Fields with * are required.</i>
					<form action="#" method="post" id="createform">
						<label for="name" ><b>Title <span class="color_red">*</span></b><br></label>
						<input type="text" class="create_input"><br>
						<label for="name"><b>Content <span class="color_red">*</span></b><br></label>
						<textarea rows="7" cols="65"> </textarea><br>
					<i>You may use <a href="#">Markdown syntax</a></i><br>
							<b>Tags</b><br>
						 <input type="text" class="create_input"><br>
						 <p class="cp">Please separate different tags with commas.</p>
							<b>Status</b><br>
						 <select name="" id="">
						 	<option value="Draft">Draft</option>
						 	<option value="Published">Published</option>
						 	<option value="Archived">Archived</option>
						 </select><br><br>				 
						 <input type="submit" value="Create">
					</form>
				</div>
	
			</div>

			<div id="sidebar">

				 <div id="sidepaneluser">
				 	
				 	<div class="sidebarheader">
				 		<div id="block"></div>
				 		<div id="tags">Demo</div>
				 	</div>

				 	<div class="tags_panel">
				 	<a href="create.php">Create New Post</a><br>
				 	<a href="manage.php">Manage Posts</a><br>
				 	<a href="approve.php">Approve Comments </a><span class="approve_comments">(3)</span><br>
				 	<a href="#">Logout</a><br>
				 	</div>
				 </div>

				 <div id="sidepaneluser">
				 	
				 	<div class="sidebarheader">
				 		<div id="block"></div>
				 		<div id="tags">Tags</div>
				 	</div>

				 	<div class="tags_a">
				 	<a href="tag.php">Graphics</a>
				 	<a href="tag.php">Blog</a>
				 	<a href="tag.php">Test</a>
				 	</div>
				 </div>
			</div>

			
			<?php 
				

				include 'footer.php';
			 ?>
		</div>
	</body>
</html>