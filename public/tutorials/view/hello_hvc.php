<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo $this->html->css('welcome.css') ?>
        <title>Hvc Tutorial</title>
    </head>

    <body>
        <header>
            <?php echo $this->url->anchor('/', $this->html->img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        <h1>Hvc Tutorial</h1>
<pre>
new Hvc;
$this->hvc->get('tutorials/hello_dummy/test/1/2/3');
$this->hvc->get('tutorials/hello_dummy/test/4/5/6');</pre>

        <section><p>&nbsp;</p></section>

        <section>
            <?php echo $response_a ?>
            <?php echo $response_b ?>
        </section>

        <?php echo $footer ?>

        <section><p>&nbsp;</p></section>
        
    </body>
    
</html>