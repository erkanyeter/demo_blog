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
        $c['tree.category'];
        // $c['tree.list'];
        // $c['tree.move'];

        // $c['tree.category'];

        // 
        // $c['db'];
        
        // $this->config['debug'] = true;
    }
);

$app->func(
    'index', 
    function () {

        // print_r($this->treeCategory);

        // $nested->insertTree('electronics');
        // $nested->insertFirstChild(2, 2, 'Televisions');
        // $nested->appendNewChild(2, 5, 'plazma');
        // $nested->insertSibling(2, 5, 'crt');
        // $nested->appendSibling(2, 4, '3d lcd');

        // $this->treeCategory->insertTree('Electronics', $extra = array('column' => 'value'));
        // $this->treeCategory->insertFirstChild(1, 1, 'Televisions');
        // $this->treeCategory->addChild(1, 1, 'Portable Electronics');
        // $this->treeCategory->appendChild(2, 5, 'Lcd');
        // $this->treeCategory->insertSibling(2, 5, 'Tube');
        // $this->treeCategory->appendSibling(2, 8, 'Plasma');
        // $this->treeCategory->truncateTable();
        
        // $this->treeCategory->updateNode(2, array('name' => 'Televisions', 'column' => 'new value'));
        

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