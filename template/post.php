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
					<a href="login.php"><li>Login</li></a>
				</ul>
			</div>
		</div>
		<div id="clear"> </div>
		<div id="containerbox">
			<div id="content">
				<div id="navigation">
					<a href="index.php" >Home</a> » <b> Post </b>
				</div>
				<div id="post">
					<div id="title"><h2>Title</h2></div>
					<div id="author"><small>posted by demo on December 16,2013</small></div>
					<div id="postcontent">Content</div>
						<div id="postnav">
							<b>Tags:</b><br>
							<a href="#">Permalink</a> | <a href="#commentcontainer">Comments (2)</a> | Last Updated On December 15,2013
						</div>
				</div>
					<div id="commentcontainer">
						<h2>2 comments</h2>
							<div id="postcomment">
								<div id="sender"> <a href="#"> woolrich store</a> says:</div>
								<div id="commentlink"><a href="#">#3</a></div>
								<div id="clear"></div>
								<div id="detail">December 16, 2013 at 12:36 am</div>
								<div id="commentext">55 </div>
							</div>
							<div id="postcomment">
								<div id="sender"> <a href="#"> rivenditori woolrich</a> says:</div>
								<div id="commentlink"><a href="#">#3</a></div>
								<div id="clear"></div>
								<div id="detail">December 16, 2013 at 12:36 am</div>
								<div id="commentext">
								I like this post, enjoyed this one thanks for posting. "To the dull mind all nature is leaden.
								To the illumined mind the whole world sparkles with light." by Ralph Waldo Emerson. 
								</div>
							</div>
							<h3>Leave a Comment</h3>
					<i class="required-t">Fields with * are required.</i>
					<form action="#" method="post" id="commentform">
						<label for="name"><b id="name_text">Name <span class="color_red">*</span></b><br></label>
						<input type="text" class="comment_input" id="name"><br>
						<div id="null_name">Name Cannot be Blank !</div>

						<label for="name"><b id="email_text">Email <span class="color_red">*</span></b><br></label>
						<input type="text" class="comment_input" id="email"><br>
						<div id="null_email">Email Cannot be Blank !</div>


						<label for="name"><b id="website_text">Website <span class="color_red">*</span></b><br></label>
						<input type="text" class="comment_input" id="website"><br>
						<div id="null_website">Website Cannot be Blank !</div>

						<label for="name"><b id="comment_text">Comment <span class="color_red">*</span></b><br></label>
						<textarea rows="7" cols="45" id="comment"> </textarea><br><br>
						<div id="null_comment">Comment Cannot be Blank !</div>

						 <input type="submit" value="Submit">
					</form>
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
				 	<a href="#">Logout</a><br>
				 	<a href="#">Logout</a><br>
				 	<a href="#">Logout</a><br>
				 	<a href="#">Logout</a><br>
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
				 	<a href="tag.php">Obullo</a><a href="tag.php">Graphics</a>
				 	<a href="tag.php">Blog</a>
				 	<a href="tag.php">Test</a>
				 	<a href="tag.php">Obullo</a><a href="tag.php">Graphics</a>
				 	<a href="tag.php">Blog</a>
				 	<a href="tag.php">Test</a>
				 	<a href="tag.php">Obullo</a><a href="tag.php">Graphics</a>
				 	<a href="tag.php">Blog</a>
				 	<a href="tag.php">Test</a>
				 	<a href="tag.php">Obullo</a>
				 	</div>
				 </div>

				<div id="sidepaneluser">
				 	
				 	<div class="sidebarheader">
				 		<div id="block"></div>
				 		<div id="tags">Recent Comments</div>
				 	</div>

				 	<div class="tags_a">
				 	<a href="#">woolrich store</a> on
				 	<a href="#">Title</a>
				 	<a href="#">rivenditori woolrich</a> on
				 	<a href="#">Title</a> Tester on
				 	<a href="#"> A Test Post</a>
				 	</div>
				 </div>
			</div>
			
			<?php 
				

				include 'footer.php';
			 ?>
		</div>
	</body>
</html>