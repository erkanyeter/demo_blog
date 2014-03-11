<?php

/**
 * $c test
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
    
    new Captcha;
});

$c->func(
    'index',
    function () {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        $this->captcha->setDriver('secure');  // or set to "cool" with no background
        $this->captcha->setPool('alpha');
        $this->captcha->setChar(5);
        $this->captcha->setFontSize(15);
        $this->captcha->setHeight(25);
        $this->captcha->setWave(false);
        $this->captcha->setColor(array('red','black','blue'));
        $this->captcha->setNoiseColor(array('red','black','blue'));
        $this->captcha->setFont('NightSkK');
        $this->captcha->sendOutputHeader();
        $this->captcha->create();

    }
);

/* End of file welcome.php */
/* Location: .public/welcome/controller/welcome.php */