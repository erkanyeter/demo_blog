
<div id="sidebar">

	<?php if($this->auth->hasIdentity()) { ?>

	 <div id="sidepaneluser">

	 	<div class="sidebarheader">
	 		<div id="block"></div>
	 		<div id="tags"><?php echo $this->auth->getIdentity('user_username') ?></div>
	 	</div>

	 	<div class="tags_panel">
		 	<?php echo $this->url->anchor('/post/create', 'Create New Post') ?> <br>
		 	<?php echo $this->url->anchor('/post/manage', 'Manage Posts') ?> <br>
		 	<?php echo $this->url->anchor('/post/approve', 'Approve Comments') ?> <span class="approve_comments">
		 		( <?php
		 		$this->db->where('comment_status','0');
        		echo $this->db->get('comments')->count();
		 		?> )
		 	</span> <br>
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
		 	<?php echo $this->url->anchor('tag/blog', 'blog') ?>
		 	<?php echo $this->url->anchor('tag/test', 'test') ?>
	 	</div>
	 </div>
</div>