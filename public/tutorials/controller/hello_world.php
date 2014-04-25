<?php

/**
 * $c hello_world
 * 
 * @var Controller
 */
$app = new Controller(
    function () {
        global $c;
        $c['view'];
        // $c['tree.category'];

        // 
        // $c['db'];
        // $this->config['debug'] = true;
        // 
        
    }
);

$app->func(
    'index', 
    function () {

        // $nested->insertTree('electronics');
        // $nested->insertFirstChild(2, 2, 'Televisions');
        // $nested->appendNewChild(2, 5, 'plazma');
        // $nested->insertSibling(2, 5, 'crt');
        // $nested->appendSibling(2, 4, '3d lcd');
        // 
        // 
        $this->category = new Tree_Category;
        // $this->category->insertTree('Electronics', $extra = array('column' => 'value'));
        // $this->category->insertFirstChild(1, 1, 'Televisions');
        // $this->category->insertFirstChild(1, 1, 'Portable Electronics');
        // $this->category->appendNewChild(2, 5, 'Lcd');
        // $this->category->insertSibling(2, 5, 'Tube');
        // $this->category->appendSibling(2, 8, 'Plasma');
        // $this->category->truncateTable();
        
        $this->category->updateNode(2, array('name' => 'Televisions', 'column' => 'new value'));
        

        $this->view->load(
            'hello_world', 
            function () {
                $this->set('name', 'Obullo');
                $this->set('footer', $this->getTpl('footer', false));
            }
        );

    }
);

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */