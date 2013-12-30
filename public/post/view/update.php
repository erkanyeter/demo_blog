<html>
	<head>
		<title><?php echo $title ?></title>

		<?php echo $this->html->css('style.css') ?>
		
		<meta charset="utf-8">
	</head>

<body>

<?php
$value  = array();
$schema = getSchema('posts');
unset($schema['*']);

foreach($schema as $field => $val)
{
	if(isset($_POST[$field]))
	{
		$value[$field] = $this->form->setValue($field);
	}
	else
	{
		$value[$field] = $row->{'post_'.$field};
	}
}
?>

		<?php echo $header ?>

		<div id="clear"></div>
		<div id="containerbox">
			 
			<div id="content">
				
				<div id="navigation">
					<?php echo $this->url->anchor('/home', 'Home') ?> » <b> Update Post </b>
				</div>

				<h1 class="h1">Update Post </h1>


				<div id="createpost">
					<i>Fields with * are required.</i>

					<p></p>

					<?php echo $this->form->open('/post/update/index/'.$row->post_id, array('method' => 'POST', " id='createform' ")) ?>

			                <table>
			                    <tr>
			                        <td style="width:15%;"><?php echo $this->form->label('Title', 'title') ?></td>
			                        <td><?php 
			                            echo $this->form->error('title');
			                            echo $this->form->input('title', $value['title'], " ");
			                            ?><span class="color_red">*</span></td>
			                    </tr>

			                    <tr>
			                        <td><?php echo $this->form->label('Content', 'content') ?></td>
			                        <td><?php 
			                            echo $this->form->error('content');

										$content_data = array(
										              'name'        => 'content',
										              'value'       => $value['content'],
										              'size'        => '50',
										              'style'       => 'width:50%',
										            );

			                            echo $this->form->textarea($content_data);
			                            ?><span class="color_red">*</span></td>
			                    </tr>

			                    <tr>
			                        <td><?php echo $this->form->label('Tags', 'tags') ?></td>
			                        <td>
			                        <?php 
			                            echo $this->form->error('tags');
			                            echo $this->form->input('tags', $value['tags'], " ");
			                            ?><span class="color_red">*</span>
									
									<p class="cp">Please separate different tags with commas.</p>

			                        </td>
			                    </tr>

			                    <tr>
			                        <td><?php echo $this->form->label('Status', 'status') ?></td>
			                        <td>
			                        <?php 
			                            echo $this->form->error('status');

	echo $this->form->dropdown('status','getSchema(posts)[status][_enum]', $value['status'], " ");
	
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