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
			
			<section>
				<?php 
					echo $this->form->getNotice();
				?>
			</section>

			<div id="content">

				<div id="navigation">
					<a href="index.php" >Home</a> » <b> Approve Comments </b>
				</div>

				<h1 class="h1 left">Comments </h1>
				<div id="clear"></div>

				<div id="approve_comments">

				<?php foreach ($comments as $row) { ?>

					<div id="postcomment">	
						<div id="sender"><?php echo $row->comment_name ?> store says on <?php echo $this->url->anchor('/post/preview/'.$row->post_id, $row->post_title); ?> </div>

						<div id="commentlink"><a href="#">#<?php echo $row->comment_id?></a></div>
						
						<small class="pending left"><?php 
						$approval = ($row->comment_status == 0) ? 'Pending approval |' : ' ';
						echo $approval;
						?>
						<?php 

						$approve = ($row->comment_status == 1) ? $this->url->anchor('/post/approve/update/'.$row->comment_id.'/unapprove', 'Unapprove') : $this->url->anchor('/post/approve/update/'.$row->comment_id.'/approve', 'Approve');

						echo $approve;
						?>
						| <?php echo $this->url->anchor('/post/approve/delete/'.$row->comment_id, 'Delete') ?>
						</small>

						<div id="detail_left"><?php echo $this->date_get->mDate("%F %d,%Y", strtotime($row->comment_creation_date)) ?></div>
						<div id="clear"></div>
						<div id="commentext"><?php echo $row->comment_comment ?></div>
					</div>	

				<?php } ?>

				</div>

			</div>

			<?php echo $sidebar ?>
			<?php echo $footer ?>

		</div>

<script type="text/javascript">
function keyPress(e){
	if(e.keyCode == 13){
		submitPage();
	}
}
function submitPage(){
	document.forms[0].submit();
}
</script>

</body>

</html>