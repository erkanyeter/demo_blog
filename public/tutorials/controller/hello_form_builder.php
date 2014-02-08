<?php

/**
 * $c hello_odm
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
    new Post;      
    new Url;
    new Html;
    //new Form_Builder;
    new View;
    
    new Model('user', 'users');
});

$c->func('index', function(){


    // if($this->post->get('dopost'))
    // {
    //     echo 'hello';
    // }

    // if($this->post->get('udopost'))
    // {
    //     echo 'world';
    // }


    // echo '<form action="/tutorials/hello_form_builder" method="POST">';

    //     echo '<input type="submit" value="dpost" name="dopost" />';
    //     echo '<input type="submit" value="udopost" name="udopost" />';

    // echo '</form>';

    // $new = new Form;
    // $new2 = clone $new;

    // $new->setRules('user_name','d','required');
    // $new2->setRules('pass','ddd','xssClean');

    // $new2->isValid();

    new Form_Builder('/tutorials/hello_form_builder', array('method' => 'post'), function(){

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->setPosition('error', 'top');
        $this->addCol(array(
            'label' => 'Email',
            'rules' => 'required|validEmail',
            'input' => $this->input('email', $this->setValue('email')),
        ));

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->setPosition('error', 'top');
        $this->addCol(array(
            'label' => 'Password',
            'rules' => 'required|minLen(6)',
            'input' => $this->password('password', $this->setValue('password')),
        ));

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => '&nbsp;',
            'input' => $this->submit('udopost', 'Do Post', ' id="udopost" '),
        ));

    });

    $this->form_builder->create('login');

    new Form_Builder('/tutorials/hello_form_builder', array('method' => 'post'), function(){

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Email',
            'rules' => 'required|validEmail',
            'input' => $this->input('user_email', $this->setValue('user_email')),
        ));

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Password',
            'rules' => 'required|minLen(6)',
            'input' => $this->password('user_password', $this->setValue('user_password')),
        ));

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Confirm',
            'rules' => 'required|matches(user_password)',
            'input' => $this->password('confirm_password', $this->setValue('confirm_password'), ' id="confirm" ' ),
        ));

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Policy : ',
            'rules' => 'required|contains(n,y)',
            array('label' => 'Yes', 'input' => $this->radio('pp', 'y', $this->setRadio('pp', 'y')) ),
            array('label' => 'No', 'input' => $this->radio('pp', 'n', $this->setRadio('pp', 'n')) ),
        ));
        $this->addCol(array(
                   'label' => 'Country',
                   'input' => $this->dropdown('country', array(1 => 'Germany' , 2 => 'US' , 3 => 'Syria'), $this->setValue('country'), " id='cntry' "),
                   'rules' => 'required|xssClean'
                  )
               );

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Languages : ',
            'rules' => 'contains(en,de,ar)',
            array('label' => 'En', 'input' => $this->checkbox('lang[]', 'en', $this->setCheckbox('lang', 'en')) ),
            array('label' => 'De', 'input' => $this->checkbox('lang[]', 'de', $this->setCheckbox('lang', 'de')) ),
            array('label' => 'Ar', 'input' => $this->checkbox('lang[]', 'ar', $this->setCheckbox('lang', 'ar')) ),
        ));

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Security Image',
            'rules' => 'required',
            'input' => $this->captcha('answer')
        ));

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => '&nbsp;',
            'input' => $this->submit('dopost', 'Do Post', ' id="dopost" '),
        ));
    });

    $this->form_builder->create('test');

    if($this->post->get('dopost'))
    {
        $this->form_builder->isValid('test');
    }

    if($this->post->get('udopost'))
    {
        $this->form_builder->isValid('login');
    }

    $this->view->get('hello_form_builder', function() {

        $this->set('name', 'Obullo');
        $this->set('footer', $this->tpl('footer', false));
    });

});

/* End of file hello_odm.php */
/* Location: .public/tutorials/controller/hello_odm.php */