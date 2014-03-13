<?php

/**
 * $c contact
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
    }
);

$c->func(
    'index',
    function ($form) {

        switch ($form){
            case 'register': 
                $jsonData = $this->_register(); 
            break;
            default: 
                echo false;
            break;
        }

        echo $jsonData;
    }
);

$c->func(
    '_register',
    function () {

        $register = array(
            'postUrl' => 'form_tuttorial/form_tuttorial_post',
            'formId' => 'registerForm',
            'inputs' => array(
                array(
                    'type'  => 'subheader',
                    'data'  => 'İletişim bilgileri',
                    'order' => 1
                ),
                array(
                    'name' => 'user_firstname',
                    'type' => 'textbox',
                    'rules' => 'required|minLen(6)|maxLen(15)',
                    'label' => 'Firstname',
                    'attr'  => 'class="form-control input-sm validation"',
                    'title' => 'Lütfen Adınızı Giriniz...',
                    'value' => '',
                    'order' => 2
                ),
                array(
                    'name' => 'user_surname',
                    'type' => 'textbox',
                    'rules' => 'required|minLen(6)|maxLen(15)',
                    'label' => 'Surname',
                    'attr'  => 'class="form-control input-sm validation"',
                    'title' => 'Lütfen Soyadınızı Giriniz...',
                    'value' => 'Akkaya',
                    'order' => 3
                ),
                array(
                    'type'  => 'groupDropdown',
                    'label' => 'Doğum Tarihiniz',
                    'order' => 4,
                    'groupDropdown' => array(
                        array(
                            'name'  => 'day',
                            'type' => 'dropdown',
                            'label' => 'Day',
                            'rules' => 'required|isNumeric',
                            'dataDropdown' => array(1 => '01',2 => '02',3 => '03',
                                                           4 => '04',5 => '05',6 => '06',
                                                           7 => '07',8 => '08',9 => '09',
                                                           10 => '10',11 => '11',12 => '12',
                                                           13 => '13',14 => '14',15 => '15',
                                                           16 => '16',17 => '17',18 => '18',
                                                           19 => '19',20 => '20',21 => '21',
                                                           22 => '22',23 => '23',24 => '24',
                                                           25 => '25',26 => '26',27 => '27',
                                                           28 => '28',29 => '29',30 => '30',
                                                           31 => '31'
                                                    ),
                            'formAttr' => 'id="genre" class="form-control input-sm input-date validation-birthday-d"',
                            'firstOption' => 'Seçiniz',
                            'value' => ''
                        ),
                        array(
                            'name'  => 'month',
                            'type' => 'dropdown',
                            'label' => 'Month',
                            'rules' => 'required|isNumeric',
                            'dataDropdown' => array('MM',1,2,3,4,5,6,7,8,9,10,11,12),
                            'formAttr' => "id='genre' class='form-control input-sm input-date validation-birthday-m'",
                            'firstOption' => 'Seçiniz',
                            'value' => ''
                        ),
                        array(
                            'name'  => 'year',
                            'type' => 'dropdown',
                            'label' => 'Year',
                            'rules' => 'required|isNumeric',
                            'dataDropdown' => array('YYYY',1996,1995,1994,1993,1992),
                            'formAttr' => 'id="genre" class="form-control input-sm input-date validation-birthday-y"',
                            'firstOption' => 'Seçiniz',
                            'value' => ''
                        ),
                    ),
                    
                ),                
                array(
                    'name' => 'country',
                    'type' => 'dropdown',
                    'rules' => 'required|isNumeric',
                    'label' => 'Ülke',
                    'dataDropdown' => array(1 => 'Türkiye', 2 => 'Almanya'),
                    'attr'  => 'class="form-control input-sm validation"',
                    'title' => 'Lütfen Soyadınızı Giriniz...',
                    'value' => '2',
                    'order' => 5
                ),
                array(
                    'name' => 'user_email',
                    'type' => 'textbox',
                    'rules' => 'required|validEmail',
                    'label' => 'Email Adresiniz',
                    'attr'  => 'class="form-control input-sm validation"',
                    'title' => 'Lütfen Email Giriniz...',
                    'value' => '',
                    'order' => 6
                ),
                array(
                    'name' => 'user_email_repeat',
                    'type' => 'textbox',
                    'rules' => 'required|matches(\'user_email\')',
                    'label' => 'Email Adresiniz tekrar',
                    'attr'  => 'class="form-control input-sm validation"',
                    'title' => 'Lütfen Email Giriniz...',
                    'value' => '',
                    'order' => 7
                ),
                array(
                    'type'  => 'subheader',
                    'data'  => 'Güvenlik Resmi',
                    'order' => 10
                ),
                array(
                    'name' => 'captcha',
                    'type' => 'captcha',
                    'rules' => 'required|exactLen(5)|callback_captcha',
                    'label' => 'Güvenlik Resmi',
                    'attr'  => '',
                    'title' => 'Lütfen güvenlik kodunu giriniz...',
                    'value' => '',
                    'order' => 11
                ),
                array(
                    'name' => 'submitbtn',
                    'type' => 'submit_button',
                    'rules' => '',
                    'label' => 'Kaydet',
                    'attr'  => '',
                    'value' => '',
                    'title' => 'Formu Kaydet',
                    'order' => 12
                ),
                array(
                    'type'  => 'subheader',
                    'data'  => 'Confirmation',
                    'order' => 13
                ),
                array(
                    'name' => 'user_18_age_agreement',
                    'type' => 'checkbox',
                    'rules' => 'required',
                    'label' => 'I am over 18 years of age and have read and accepted the general terms and conditions(see Terms and Conditions). I agree to receive information from your company. I can cancel this service in my account at any time. Please note that betin does not offer any bets to US residents.',
                    'attr'  => '',
                    'value' => 1,
                    'title' => '',
                    'order' => 14
                ),
                array(
                    'name' => 'testHidden',
                    'type' => 'hidden',
                    'rules' => '',
                    'label' => '',
                    'attr'  => '',
                    'value' => 1,
                    'title' => '',
                    'order' => 15
                ),
                
            )
        );

        $verify = true;

        if($verify){
            $register['inputs'][] = array(
                                        'type'  => 'subheader',
                                        'data'  => 'Verify',
                                        'order' => 8
                                    );

            $register['inputs'][] = array(
                                        'name' => 'verify_input',
                                        'type' => 'verify',
                                        'rules' => 'required|exactLen(6)|isNumeric',
                                        'label' => 'Doğrulama Kodu',
                                        'attr'  => '',
                                        'title' => 'Doğrulama kodunu giriniz.',
                                        'bttn_value' => 'Gönder',
                                        'order' => 9
                                    );
        }

        
        $register['inputs'] = $this->_short($register['inputs'], 'order');
        return json_encode($register);
    }
);

$c->func(
    '_short',
    function ($records, $field, $reverse=false) {
            $hash = array();
    
            foreach($records as $record)
            {
                $hash[$record[$field]] = $record;
            }
            
            ($reverse)? krsort($hash) : ksort($hash);
            
            $records = array();
            
            foreach($hash as $record)
            {
                $records []= $record;
            }
            
            return $records;
    }
);
