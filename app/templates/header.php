<?php

	// Header Uri functions

	$menuConfig     = $this->config->getItem('menu'); // Get menu array
	$firstSegment   = $this->uri->getSegment(0);	   // Get first segnment
	$currentSegment = (empty($firstSegment)) ? 'home' : $firstSegment;  // Set current segment as "home" if its empty

	// echo $this->web->get('views/header');
	// @todo $this->view->get('@views/header');

?>

<div id="header"> 
	<h1 class="logo"><?php echo $this->url->anchor('/home', 'Blog Demo') ?></h1>

	<div id="menu">
		<ul>
			<?php
			$userHasIdentity = $this->auth->hasIdentity(); // get auth Identity of user
			
			foreach ($menuConfig as $key => $value)
			{
				$active = ($currentSegment == $key) ? ' id="active" ' : '';

				if(($key == 'login' OR $key == 'signup') AND $userHasIdentity == true)
				{
					// don't show login button
				} 
				else 
				{
					echo '<li>'.$this->url->anchor($key, $value, " $active ").'</li>';
				}
			}
			?>
		</ul>
	</div>
	
</div>