<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo $this->html->css('welcome.css') ?>
        <title>Hello Form(s)</title>
    </head>

    <body>
        <header>
            <?php echo $this->url->anchor('/', $this->html->img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        
        <h1>Hello Form(s)</h1>

        <section><?php echo $this->form->getNotice() ?></section>

        <?php if($this->post->get('form1_dopost')) { ?>
            <section><?php echo $this->form->getMessage() ?></section>
            <section><?php echo $this->form->getErrorString() ?></section>
        <?php } ?>

        <h2>Form1</h2>

        <section>
            <?php echo $this->form->open('tutorials/hello_forms/index', array('method' => 'POST')) ?>

                <table width="100%">
                    <tr>
                        <td style="width:20%;"><?php echo $this->form->label('Email') ?></td>
                        <td><?php 
                            echo $this->form->error('form1_email');
                            echo $this->form->input('form1_email', $this->form->setValue('form1_email'), " id='form1_email' ");
                            ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->form->label('Password') ?></td>
                        <td><?php 
                            echo $this->form->error('form1_password');
                            echo $this->form->password('form1_password', '', " id='form1_password' ");
                            ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->form->label('Confirm') ?></td>
                        <td><?php 
                            echo $this->form->error('confirm_password');
                            echo $this->form->password('confirm_password', '', " id='confirm' ");
                            ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><?php
                            echo $this->form->error('agreement');
                            echo $this->form->checkbox('agreement', 1, $this->form->setValue('agreement'), " id='agreement' ");
                            echo $this->form->label('I agree terms and conditions', 'agreement');
                            ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><?php echo $this->form->submit('form1_dopost', 'Do Post') ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    </table>
                
            <?php echo $this->form->close() ?>

            <?php if($this->post->get('form1_dopost')) { ?>

            <h2>Test Results of Form1</h2>
            <section>
                <h3>$this->form->getOutput()</h3>
                <pre><?php print_r($this->form->getOutput()) ?></pre>

                <h3>$this->form->error('form1_email')</h3>
                <pre><?php echo $this->form->error('form1_email') ?></pre>

                <h3>print_r($this->form->getValue('form1_email'))</h3>
                <pre><?php print_r($this->form->getValue('form1_email')) ?></pre>
            </section>

            <?php } ?>

            <?php if($this->post->get('form2_dopost')) { ?>
                <section><?php echo $this->form->getMessage() ?></section>
                <section><?php echo $this->form->getErrorString() ?></section>
            <?php } ?>

            <h2>Form2</h2>

            <?php echo $this->form->open('tutorials/hello_forms/index', array('method' => 'POST')) ?>

                <table width="100%">
                    <tr>
                        <td style="width:20%;"><?php echo $this->form->label('Email') ?></td>
                        <td><?php 
                            echo $this->form->error('form2_email');
                            echo $this->form->input('form2_email', $this->form->setValue('form2_email'), " id='form2_email' ");
                            ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->form->label('Password') ?></td>
                        <td><?php 
                            echo $this->form->error('form2_password');
                            echo $this->form->password('form2_password', '', " id='form2_password' ");
                            ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><?php echo $this->form->submit('form2_dopost', 'Do Post') ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    </table>
                
                <?php echo $this->form->close() ?>

            <?php if($this->post->get('form2_dopost')) { ?>

            <h2>Test Results of Form2</h2>

            <section>
                <h3>$this->form->getOutput()</h3>
                <pre><?php print_r($this->form->getOutput()) ?></pre>

                <h3>$this->form->error('form2_email')</h3>
                <pre><?php echo $this->form->error('form2_email') ?></pre>

                <h3>print_r($this->form->getValue('form2_email'))</h3>
                <pre><?php print_r($this->form->getValue('form2_email')) ?></pre>
            </section>

            <?php } ?>

        </section> 
        
        <section><p>Total memory usage <?php echo round(memory_get_usage()/1024/1024, 2).' MB' ?></p></section>
    </body>
    
</html>