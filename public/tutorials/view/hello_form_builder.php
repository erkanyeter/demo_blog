<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo $this->html->css('welcome.css') ?>
            <?php echo $this->form_builder->printCss() ?>
        <title>Odm Tutorial</title>
    </head>

    <body>
        <header><?php echo $this->url->anchor('/', $this->html->img('logo.png', ' alt="Obullo" ')) ?></header>
        
        <h1>Hello Form Builder</h1>

        <section><?php echo $this->form->getMessage('message') ?></section>
        <section><?php echo $this->form->getNotice() ?></section>

        
        <section>
                    <!-- print form output -->

                    <?php echo $this->form_builder->printForm('test') ?>

                    <!-- print form output -->

                    <hr />
                    
                    <h2>Second form</h2>

                    <!-- print second form -->
                    <?php echo $this->form_builder->printForm('login') ?>

    
                    <h2>Test Results</h2>

                        <section>
                            <h3>print_r($this->form->getOutput())</h3>
                            <pre><?php print_r($this->form->getOutput()) ?></pre>

                            <h3>print_r($this->form->getErrors())</h3>
                            <pre><?php print_r($this->form->getErrors()) ?></pre>

                            <h3>$this->form->getError('email')</h3>
                            <pre><?php echo $this->form->getError('email') ?></pre>

                            <h3>$this->form->getValue('email')</h3>
                            <pre><?php echo $this->form->getValue('email') ?></pre>
                        </section>
        </section> 
        
        <section>
            <p>Total memory usage <?php echo round(memory_get_usage()/1024/1024, 2).' MB' ?></p>
        </section>
    </body>
    
</html>