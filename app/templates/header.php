<?php
	$menu = $this->config->item('menu');

	$segment = $this->uri->segment(0);
	$currentPage = (empty($segment)) ? 'home' : $segment;
?>

<div id="header"> 
	<h1 class="logo">Blog Demo</h1>
	<div id="menu">
		<ul>
			<?php 
			foreach ($menu as $key => $value)
			{
				$active = ($currentPage == $key) ? ' id="active" ' : '';

				echo '<li>'.$this->url->anchor($key, $value, " $active ").'</li>';
			}
			?>
		</ul>
	</div>
</div>