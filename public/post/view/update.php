<html>
	<head>
		<title><?php echo $title ?></title>

		<?php echo $this->html->css('style.css') ?>
		
		<meta charset="utf-8">
	</head>

<body>

<?php $this->form->setSchema('posts')  // set schema for form  ?>

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
					<?php echo $this->url->anchor('/home', 'Home') ?> » <b> Update Post </b>
				</div>

				<h1 class="h1">Update Post </h1>

				<div id="createpost">
					<i>Fields with * are required.</i>

					<p></p>

					<?php echo $this->form->open('/post/update/index/'.$post_id, array('method' => 'POST', " id='createform' ")) ?>

			                <table>
			                    <tr>
			                        <td style="width:15%;"><?php echo $this->form->label('Title') ?></td>
			                        <td><?php 
			                            echo $this->form->error('title');
			                            echo $this->form->input('title', $row, " ");
			                            ?><span class="color_red">*</span></td>
			                    </tr>

			                    <tr>
			                        <td><?php echo $this->form->label('Content') ?></td>
			                        <td><?php 
			                            echo $this->form->error('content');
			                            echo $this->form->textarea('content', $row, ' rows="15" cols="80" size="50" style="width:50%" ');
			                            ?><span class="color_red">*</span></td>
			                    </tr>

			                    <tr>
			                        <td><?php echo $this->form->label('Tags') ?></td>
			                        <td>
			                        <?php 
			                            echo $this->form->error('tags');
			                            echo $this->form->input('tags', $row, " ");
			                            ?><span class="color_red">*</span>
									
									<p class="cp">Please separate different tags with commas.</p>

			                        </td>
			                    </tr>

			                    <tr>
			                        <td><?php echo $this->form->label('Status') ?></td>
			                        <td>
			                        <?php 
			                            echo $this->form->error('status');
										echo $this->form->dropdown('status','getSchema(posts)[status][_enum]', $row, " ");
			                            ?><span class="color_red">*</span>
			                        </td>
			                    </tr>

			                    <tr>
			                    	<td></td>
			                        <td><?php echo $this->form->submit('dopost', 'Do Post') ?></td>
			                    </tr>

			                    <tr>
			                        <td colspan="2">&nbsp;</td>
			                    </tr>
			                </table>
					
					<?php echo $this->form->close() ?>

				</div>
			</div>

			<?php echo $sidebar ?>
			<?php echo $footer ?>

		</div>
</body>

</html>