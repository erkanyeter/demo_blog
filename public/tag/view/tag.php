<html>
	<head>
		<title><?php echo $title ?></title>

		<?php echo $this->html->css('style.css') ?>
		
		<meta charset="utf-8">
	</head>

<body>
		<?php echo $header ?>

		<div id="clear"></div>
		<div id="containerbox">
			 
			<div id="content">
			<h1 class="post_tag">Post Tagged With <?php echo $this->uri->segment(1)?></h1>

				<?php 
				if(count($posts) > 0)
				{
					foreach($posts as $row) { 
						?>

					<div id="post">		
						<div id="title"><h2><?php echo $this->url->anchor('/post/detail/'.$row['post_id'], $row['post_title']) ?></h2></div>

						<div id="author"><small>posted by <?php echo $row['user_username'] ?> on <?php echo $this->date_get->mDate("%F %d,%Y", strtotime($row['post_creation_date'])) ?></small></div>
						
						<div id="postcontent">
							<?php echo $row['post_content'] ?>
						</div>

						<div id="postnav">

							<b>Tags:</b> <?php echo $this->tag_cloud->render('html', explode(',',$row['post_tags']), false) ?><br><br>

							<a href="post.php#commentcontainer">Comments (2)</a> | Last Updated On December 15,2013
						</div>
					</div>

					<?php 
					}
				}
				?>
			</div>

			<?php echo $sidebar ?>
			<?php echo $footer ?>

		</div>
</body>

</html>