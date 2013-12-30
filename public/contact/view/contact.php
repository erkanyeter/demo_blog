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
					<?php echo $this->url->anchor('/home', 'Home') ?> » <b> Contact</b>
				</div>

				<h1 class="h1">Contact</h1>


				<div id="createpost">
					<i>Fields with * are required.</i>

					<p></p>

					<?php echo $this->form->open('/contact/index', array('method' => 'POST', " id='createform' ")) ?>

			                <table>
			                    <tr>
			                        <td style="width:15%;"><?php echo $this->form->label('Name', 'name') ?></td>
			                        <td><?php 
			                            echo $this->form->error('name');
			                            echo $this->form->input('name', $this->form->setValue('name'), " ");
			                            ?><span class="color_red">*</span></td>
			                    </tr>
			                    <tr>
			                        <td><?php echo $this->form->label('Email', 'email') ?></td>
			                        <td><?php 
			                            echo $this->form->error('email');
			                            echo $this->form->input('email', $this->form->setValue('email'), " ");
			                            ?><span class="color_red">*</span></td>
			                    </tr>
			                    <tr>
			                        <td><?php echo $this->form->label('Subject', 'subject') ?></td>
			                        <td><?php 
			                            echo $this->form->error('subject');
			                            echo $this->form->input('subject', $this->form->setValue('subject'), " ");
			                            ?><span class="color_red">*</span></td>
			                    </tr>

			                    <tr>
			                        <td><?php echo $this->form->label('Body', 'body') ?></td>
			                        <td><?php 
			                            echo $this->form->error('body');

										$content_data = array(
										              'name'        => 'body',
										              'value'       => $this->form->setValue('body'),
										              'size'        => '50',
										              'style'       => 'width:50%',
										            );

			                            echo $this->form->textarea($content_data);
			                            ?><span class="color_red">*</span></td>
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