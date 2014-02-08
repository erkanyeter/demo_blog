<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <?php echo $this->html->css('welcome.css') ?>
        <title>Hello World</title>
    </head>

    <body>
        <header>
            <?php echo $this->url->anchor('/', $this->html->img('logo.png', ' alt="Obullo" ')) ?>
        </header>

        <h1>Hello Form Model ( No Schema )</h1>

        <section><?php echo $this->form->getNotice() ?></section>
        <section><?php echo $this->user->getMessage('message') ?></section>
        <section><?php echo $this->form->getErrorString() ?></section>

        <?php echo $this->form->open('tutorials/hello_form_model/index', array('method' => 'POST')) ?>

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

                <section>
                        <h3>print_r($this->user->getOutput())</h3>
                        <pre><?php print_r($this->user->getOutput()) ?></pre>

                        <h3>print_r($this->user->getMessages())</h3>
                        <pre><?php print_r($this->user->getMessages()) ?></pre>

                        <h3> echo $this->user->getMessage('errorKey')</h3>
                        <pre><?php echo $this->user->getMessage('errorKey') ?></pre>

                        <h3>print_r($this->user->getErrors())</h3>
                        <pre><?php print_r($this->user->getErrors()) ?></pre>

                        <h3>$this->user->getError('user_email')</h3>
                        <pre><?php echo $this->user->getError('user_email') ?></pre>

                        <h3>print_r($this->user->getValues())</h3>
                        <pre><?php print_r($this->user->getValues()) ?></pre>

                        <h3>$this->user->getValue('user_email')</h3>
                        <pre><?php echo $this->user->getValue('user_email') ?></pre>

                        <h3>$this->user->getValue('user_password')</h3>
                        <pre><?php echo $this->user->getValue('user_password') ?></pre>
                </section>

        <?php echo $this->form->close() ?>

        <section>
            <?php echo $footer ?>
        </section>
    </body>
</html>

        