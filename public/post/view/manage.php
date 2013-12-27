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

				<div id="navigation">
					<a href="index.php" >Home</a> » <b> Manage Posts </b>
				</div>

				<h1 class="h1 left">Manage Posts </h1>
				<div id="clear"></div>

				<div id="manageposts">

				<?php echo $this->form->open('post/manage', array('method' => 'POST')) ?>

					<table>
						<th class="table_head x">Title</th>
						<th class="table_head">Status</th>
						<th class="table_head x">Create Time</th>
						<th class="table_head"> </th>

						<tr >
							<td><input type="text" name="title" onkeypress="keyPress();"></td>
							<td>
			
<?php 

$customOptions = array('' => '');
echo $this->form->dropdown('status',array('getSchema(posts)[status][_enum]', $customOptions), $this->form->setValue('status'), ' onchange="submitPage();" '); 
							?>
							</td>
							<td>  </td>
							<td>  </td>
						</tr>

				<?php 
				$i = 0;
				foreach($posts as $post) {
				$i++; 
				$mod = ($i%2) == 0 ? 'datacolor1' : 'datacolor2';
				 ?>
						<tr id="<?php echo $mod ?>">
							<td><?php echo $post->post_title ?></td>
							<td><?php echo $post->post_status ?></td>
							<td><?php echo $post->post_creation_date ?></td>
							<td class="options">
							<?php echo $this->url->anchor('post/manage_detail/'.$post->post_id,
								$this->html->img('view.png')
							) ?>
							<?php echo $this->url->anchor('post/manage_update/'.$post->post_id,
								$this->html->img('update.png')
							) ?>
							<?php echo $this->url->anchor('post/manage_delete/'.$post->post_id,
								$this->html->img('delete.png')
							) ?>
							</td>
						</tr>
				 <?php } ?>
					</table>

				<?php echo $this->form->close() ?>

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