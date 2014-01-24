<?php
	$menu        = $this->config->getItem('menu');
	$segment     = $this->uri->getSegment(0);
	
	$currentPage = (empty($segment)) ? 'home' : $segment;
?>

<div id="header"> 
	<h1 class="logo"><?php echo $this->url->anchor('/home', 'Blog Demo') ?></h1>
	<div id="menu">
		<ul>
			<?php 
			$hasIdentity = $this->auth->hasIdentity();
			
			foreach ($menu as $key => $value)
			{
				$active = ($currentPage == $key) ? ' id="active" ' : '';

				if(($key == 'login' OR $key == 'signup') AND $hasIdentity)
				{
					// don't show login
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