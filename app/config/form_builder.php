<?php

/*
| -------------------------------------------------------------------
| Form Package Configuration
| -------------------------------------------------------------------
| This file specifies form templates that used by form && Uform
| packages.
|
| -------------------------------------------------------------------
| Prototype
| -------------------------------------------------------------------
|
*/

$form_builder = array(

    'captcha' => function(){

                new Captcha;

                $this->captcha->setDriver('secure');  // or set to "cool" with no background
                $this->captcha->setPool('alpha');
                $this->captcha->setChar(5);
                $this->captcha->setFontSize(20);
                $this->captcha->setHeight(36);
                $this->captcha->setWave(false);
                $this->captcha->setColor(array('red','black','blue'));
                $this->captcha->setNoiseColor(array('red','black','blue'));

                $this->captcha->create();

                return array(
                    'image_url' => $this->captcha->getImageUrl(),
                    'image_id'  => array('name' => 'image_id', 'value' => $this->captcha->getImageId()),
                );
                // $html = '<img src="'.$this->captcha->getImageUrl().'">';
                // $html.= '<input type="hidden" value="'.$this->captcha->getImageId().'" name="image_id">';
                // $html.= '<input type="text" value="" name="answer">';
    }
);