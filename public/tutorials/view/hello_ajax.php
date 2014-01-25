<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        
        <?php echo $this->html->css('welcome.css') ?>
        <?php echo $this->html->js('ajax.js') ?>

        <title>Odm Ajax Tutorial</title>
    </head>
    <body>
        <header>
            <?php echo $this->url->anchor('/', $this->html->img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        
        <h1>Hello Ajax</h1>
        <section>
        
            <?php
            echo $this->form->open('tutorials/hello_ajax/index', array('method' => 'POST', 'id' => 'odm_tutorial')) ?>

                <table width="100%">
                    <tr>
                        <td style="width:20%;"><?php echo $this->form->label('Email') ?></td>
                        <td>
                            <?php echo $this->form->input('email', $this->form->setValue('email'), " id='email' ");?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $this->form->label('Password') ?></td>
                        <td>
                            <?php echo $this->form->password('password', '', " id='password' ") ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $this->form->label('Confirm') ?></td>
                        <td>
                            <?php echo $this->form->password('confirm_password', '', " id='confirm' ") ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <?php echo $this->form->checkbox('agreement', 1, $this->form->setValue('agreement'), " id='agreement' ") ?>

                            <label for="agreement">I agree terms and conditions.</label>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <?php echo $this->form->submit('dopost', 'Do Post', ' onclick="submitAjax(\'odm_tutorial\')" ') ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                </table>

            <?php echo $this->form->close(); ?>
            
        </section>
        <section>
            <p>&nbsp;</p>
        </section>
    </body>
</html>