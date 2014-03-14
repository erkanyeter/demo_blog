<?php

/**
 * $c auth/query
 * 
 * @var Private Controller
 */
$c = new Controller(
    function () {  
        new Post;
        new Db;
        new Auth;
    }
);

$c->func(
    'index',
    function () {

        //-------- DISABLED LOGIN -------//

        if ($this->auth->getItem('allow_login') == false) {  // Disabled login
            $r = array(
                'success' => 0,
                'message' => 'LOGIN_DISABLED',
            );
            echo json_encode($r);
            return;
        }
        //-------- Secure Pdo Query -------//

        $this->db->prepare(
            'SELECT user_id,
            user_username,
            user_password,
            user_email 
            FROM users WHERE user_email = :user_email'
        );
        $this->db->bindValue(':user_email', $this->post->get('email'), PARAM_STR);
        $this->db->execute();
        $row = $this->db->getRowArray();

        //-------- WRONG USERMAME -------//

        if ($row === false) {
            $r = array(
                'success' => 0,
                'message' => 'LOGIN_WRONG_USERMAME',
            );
            echo json_encode($r);
            return;
        }
        //-------- SUCCESS -------//
        
        if ($this->auth->verifyPassword($this->post->get('password'), $row['user_password'])) { // Verify User Password 
            $r = array(
                'success' => 1,
                'results' => $row,
            );
            echo json_encode($r);  // auth success.
            return;
        }

        //-------- WRONG PASSWORD -------//

        $r = array(
            'success' => 0,
            'message' => 'LOGIN_WRONG_PASSWORD',
        );
        echo json_encode($r);
    }
);