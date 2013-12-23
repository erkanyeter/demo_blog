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
					<a href="index.php"><li>Home</li></a>
					<a href="about.php"><li>About</li></a>
					<a href="contact.php"><li>Contact</li></a>
					<a href="login.php"><li>Login</li></a>
				</ul>
			</div>
		</div>
		<div id="clear"> </div>
		<div id="containerbox">
			<div id="content">
			<h1 class="post_tag">Post Tagged With title</h1>
				<div id="post">
					<div id="title"><h2><a href="post.php">Title</a></h2></div>
					<div id="author"><small>posted by demo on December 16,2013</small></div>
					<div id="postcontent">Content</div>
						<div id="postnav">
							<b>Tags: </b><a href="#">ares</a><br>
							<a href="post.php">Permalink</a> | <a href="post.php#commentcontainer">Comments (2)</a> | Last Updated On December 15,2013
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