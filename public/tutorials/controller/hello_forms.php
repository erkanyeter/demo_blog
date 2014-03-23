<?php

/**
 * $o hello_forms
 * 
 * @var Controller
 */
$o = new Controller(
    function () {
        new Url;
        new Html;
        new View;
        new Form;
        new Post;
        new Sess;
        $this->translator->load('hello_form');
    }
);

$o->func(
    'index', 
    function () {

        if ($this->post->get('form1_dopost')) {   //--------- Form 1 --------//

            $this->form->setRules('form1_email', 'Form1 Email', 'required|validEmail');
            $this->form->setRules('form1_password', 'Form1 Password', 'required|minLen(6)');
            $this->form->setRules('confirm_password', 'Form1 Confirm Password', 'required|matches(form1_password)');
            $this->form->setRules('agreement', 'Form1 User Agreement', '_int|required');

            if ($this->form->isValid()) {        
                $this->form->setNotice('Form1 Validation Success', SUCCESS);    // Set flash notice using Session Class.
                $this->url->redirect('tutorials/hello_forms/index');       // Redirect to user same page.
            }
        }

        if ($this->post->get('form2_dopost')) {   //-------- Form 2 --------//

            $this->form->setRules('form2_email', 'Form2 Email', 'required|validEmail');
            $this->form->setRules('form2_password', 'Form2 Password', 'required|minLen(6)');

            if ($this->form->isValid()) {
                $this->form->setNotice('Form2 Validation Success', SUCCESS);    // Set flash notice using Session Class.
                $this->url->redirect('tutorials/hello_forms/index');       // Redirect to user same page.
            }
        }

        $this->view->get(
            'hello_forms',
            function () {
                $this->set('name', 'Obullo');
                $this->set('footer', $this->getTpl('footer', false));
            }
        );
    }
);

/* End of file hello_forms.php */
/* Location: .public/tutorials/controller/hello_forms.php */