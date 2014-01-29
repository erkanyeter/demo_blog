<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <?php echo $this->html->css('welcome.css') ?>
        <title>Hello World</title>
    </head>

    <body>
        <header>
            <a href="/"><img src="/assets/images/logo.png" alt="logo" border="0" /></a>
        </header>

        <h1>Hello Form Model ( No Schema )</h1>

        <section>
            <?php echo $this->form->getNotice() ?>
        </section>
        
        <section><?php echo $this->user->getMessage('string') ?></section>

        <section>
            <?php echo $this->form->errorString() ?>
        </section>

            <?php
            echo $this->form->open('tutorials/hello_form_model/index', array('method' => 'POST')) ?>

                <table width="100%">
                    <tr>
                        <td style="width:20%;"><?php echo $this->form->label('Email') ?></td>
                        <td><?php 
                            echo $this->form->error('user_email');
                            echo $this->form->input('user_email', $this->form->setValue('user_email'), " id='user_email' ");
                            ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->form->label('Password') ?></td>
                        <td><?php 
                            echo $this->form->error('user_password');
                            echo $this->form->password('user_password', '', " id='user_password' ");
                            ?></td>
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

        <section>
            <?php echo $footer ?>
        </section>
    </body>
</html>

        