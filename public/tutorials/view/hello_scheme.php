<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo $this->html->css('welcome.css') ?>
        <title>Scheme Tutorial</title>
    </head>

    <body>

<header>
    <?php
     echo $this->url->anchor('/', $this->html->img('logo.png', ' alt="Obullo" '))
     ?>
</header>

<h1>Hello Scheme World</h1>

<section>
	<p>This my first content using schemes.</p>
	<p>This page use the scheme function located in your <kbd>app/config/scheme.php</kbd> file.</p>

<pre>$scheme = array(
    'welcome' => function () {
        $this->set('footer', '@get.tpl.footer');
    },
);
</pre>

<p></p>
<p>The <kbd>$this->getScheme();</kbd> method load the <b>welcome</b> scheme and fetches the <b>hello_scheme</b> view using <kbd>$this->view->get();</kbd> function.</p>

<pre>$this->view->get('hello_scheme', function() {
        $this->set('name', 'Obullo');
        $this->set('title', 'Hello Scheme World !');
        $this->getScheme('welcome');
});</pre>
</section>

<?php echo $footer ?>
</body>
</html>