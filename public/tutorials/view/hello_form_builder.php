<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo $this->html->css('welcome.css') ?>
            <?php echo $this->html->css('uform.css') ?>
        <title>Odm Tutorial</title>
    </head>

    <body>
        <header><?php echo $this->url->anchor('/', $this->html->img('logo.png', ' alt="Obullo" ')) ?></header>
        
        <h1>Hello Uform</h1>

        <section><?php echo $this->user->getMessage('errorMessage') ?></section>
        <section><?php echo $this->form->getNotice() ?></section>

        
        <section>
                    <!-- print form output -->

                    <?php echo $this->uform->printForm() ?>

                    <!-- print form output -->
    
                    <h2>Test Results</h2>
                    <?php if(isset($this->user) AND is_object($this->user)) { ?>

                        <section>
                            <h3>print_r($this->user->getOutput())</h3>
                            <pre><?php print_r($this->user->getOutput()) ?></pre>

                            <h3>print_r($this->user->getMessages())</h3>
                            <pre><?php print_r($this->user->getMessages()) ?></pre>

                            <h3> echo $this->user->getMessage('errorKey')</h3>
                            <pre><?php echo $this->user->getMessage('errorKey') ?></pre>

                            <h3>print_r($this->user->getErrors())</h3>
                            <pre><?php print_r($this->user->getErrors()) ?></pre>

                            <h3>$this->user->getError('email')</h3>
                            <pre><?php echo $this->user->getError('email') ?></pre>

                            <h3>print_r($this->user->getValues())</h3>
                            <pre><?php print_r($this->user->getValues()) ?></pre>

                            <h3>$this->user->getValue('email')</h3>
                            <pre><?php echo $this->user->getValue('email') ?></pre>
                        </section>

                    <?php } ?>        

        </section> 
        
        <section>
            <p>Total memory usage <?php echo round(memory_get_usage()/1024/1024, 2).' MB' ?></p>
        </section>
    </body>
    
</html>