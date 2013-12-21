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
					<a href="index.php"><li id="active">Home</li></a>
					<a href="about.php"><li>About</li></a>
					<a href="contact.php"><li>Contact</li></a>
					<a href="login.php"><li>Login</li></a>
				</ul>
			</div>
		</div>
		<div id="clear"> </div>
		<div id="containerbox">
			 
			 <div id="content">
				<div id="post">
					<div id="title"><h2><a href="post.php">Title</a></h2></div>
					<div id="author"><small>posted by demo on December 16,2013</small></div>
					<div id="postcontent">Content</div>
						<div id="postnav">
							<b>Tags:</b><br>
							<a href="post.php">Permalink</a> | <a href="post.php#commentcontainer">Comments (2)</a> | Last Updated On December 15,2013
						</div>
				</div>

				<div id="post">
					<div id="title"><h2>Welcome!</h2></div>
					<div id="author"><small>posted by demo on December 16,2013</small></div>
					<div id="postcontent">
					This blog system is developed using Obullo. It is meant to demonstrate how to use Obullo to build a complete real-world application.
					Complete source code may be found in the Obullo releases.
					Feel free to try this system by writing new posts and leaving comments.
					</div>
						<div id="postnav">
							<b>Tags:</b><br>
							<a href="#">Permalink</a> | <a href="#">Comments (2)</a> | Last Updated On December 15,2013
						</div>
				</div>

				<div id="post">
					<div id="title"><h2>A Test Post</h2></div>
					<div id="author"><small>posted by demo on December 16,2013</small></div>
					<div id="postcontent">
					Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
				    incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
				    exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
				    dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
				    Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
					</div>
						<div id="postnav">
							<b>Tags:</b><br>
							<a href="#">Permalink</a> | <a href="#">Comments (2)</a> | Last Updated On December 15,2013
						</div>
				</div>	
				
				<div id="blockbottom"> </div>
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