
<div id="sidebar">

	<?php if($this->auth->hasIdentity()) { // check is auth ok. ?>

	 <div id="sidepaneluser">

	 	<div class="sidebarheader">
	 		<div id="block"></div>
	 		<div id="tags"><?php echo $this->auth->getIdentity('user_username') ?></div>
	 	</div>

	 	<div class="tags_panel">
		 	<?php echo $this->url->anchor('/post/create', 'Create New Post') ?> <br>
		 	<?php echo $this->url->anchor('/post/manage', 'Manage Posts') ?> <br>
		 	<?php echo $this->url->anchor('/post/approve', 'Approve Comments') ?> <span class="approve_comments">(3)</span> <br>
		 	<?php echo $this->url->anchor('/logout', 'Logout') ?> <br>
	 	</div>

	 </div>

	<?php } ?>

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