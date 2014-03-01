
<div id="header"> 
	<h1 class="logo"><?php echo $this->url->anchor('/home', 'Blog Demo') ?></h1>

	<div id="menu">
		<ul>
			<?php echo $this->hvc->get('private/views/navbar') // get navbar view with HVC  ?>
		</ul>
	</div>
	
</div>