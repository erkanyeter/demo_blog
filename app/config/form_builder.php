<?php

/*
| -------------------------------------------------------------------
| Form Builder Package Configuration
| -------------------------------------------------------------------
| This file specifies the configurations that used by form_builder
| packages.
|
| -------------------------------------------------------------------
| Prototype
| -------------------------------------------------------------------
|
*/
$form_builder = array(

    'captcha'   => array(
        'image_template'    => '<img src="%s" />',
        'hidden_input_name' => 'image_id',
        'func' => function () {  // Captcha Widget Settings

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
                'image_url'             => $this->captcha->getImageUrl(),
                'image_id'              => $this->captcha->getImageId(), // hidden_field_name
            );
        }
    ),
);