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
				<div id="navigation">
					<a href="index.php" >Home</a> » <b> Manage Posts </b>
				</div>
				<h1 class="h1 left">Manage Posts </h1>
				<i class="right result">Displaying 1-4 of 4 result(s).</i>
				<div id="clear"></div>
				<div id="manageposts">
						<table>
							<th class="table_head x">Title</th>
							<th class="table_head">Status</th>
							<th class="table_head x">Create Time</th>
							<th class="table_head"> </th>
							<tr >
								<td><input type="text"></td>
								<td>
									<select name="" id="">
										<option value=""> </option>
										<option value="Draft">Draft</option>
										<option value="Published">Published</option>
										<option value="Archived">Archived</option>
									</select>
								</td>
								<td>  </td>
								<td>  </td>
							</tr>
							<tr id="datacolor2">
								<td><a href="#">Title</a></td>
								<td>Published</td>
								<td> 2013/12/15 11:44:55 PM </td>
								<td class="options">
									<a href="#"><img src="img/view.png" alt="view"></a>
									<a href="#"><img src="img/update.png" alt="update"></a>
									<a href="#"><img src="img/delete.png" alt="delete"></a>
								</td>
							</tr>
							<tr id="datacolor1">
								<td><a href="#">Test html code</a></td>
								<td>Published</td>
								<td> 2013/12/15 11:44:55 PM </td>
								<td class="options">
									<a href="#"><img src="img/view.png" alt="view"></a>
									<a href="#"><img src="img/update.png" alt="update"></a>
									<a href="#"><img src="img/delete.png" alt="delete"></a>
								</td>
							</tr>
							<tr id="datacolor2">
								<td><a href="#">Welcome!</a></td>
								<td>Published</td>
								<td> 2013/12/15 11:44:55 PM </td>
								<td class="options">
									<a href="#"><img src="img/view.png" alt="view"></a>
									<a href="#"><img src="img/update.png" alt="update"></a>
									<a href="#"><img src="img/delete.png" alt="delete"></a>
								</td>
							</tr>
							<tr id="datacolor1">
								<td><a href="#">A Test Post</a></td>
								<td>Published</td>
								<td>2013/12/15 11:44:55 PM</td>
								<td class="options">
									<a href="#"><img src="img/view.png" alt="view"></a>
									<a href="#"><img src="img/update.png" alt="update"></a>
									<a href="#"><img src="img/delete.png" alt="delete"></a>
								</td>
							</tr>
						</table>
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