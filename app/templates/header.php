
<?php $this->view->get('@header') // View Controller ?>

<div id="header"> 
	<h1 class="logo"><?php echo $this->url->anchor('/home', 'Blog Demo') ?></h1>

	<div id="menu">
		<ul>
			<?php echo $this->view->header->navbar() ?>
		</ul>
	</div>
	
</div>