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
        // 
        
    }
);

$app->func(
    'index', 
    function () {

        // echo $sizeOfTree = 2--1;

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
        
        $source = array(    // Portable Electronics
            'lft' => 9,
            'rgt' => 14
        );

        $target = array(    // Televisions
            'lft' => 2,
            'rgt' => 15 
        );
        $this->treeCategory->moveAsNextSibling($source, $target);
        // 
        
        // $source = array(    // Portable Electronics
        //     'lft' => 9,
        //     'rgt' => 14
        // );

        // $target = array(    // Televisions
        //     'lft' => 2,
        //     'rgt' => 15 
        // );
        // $this->treeCategory->moveAsFirstChild($source, $target);

        // $source = array(    // Portable Electronics
        //     'lft' => 10,
        //     'rgt' => 15
        // );

        // $target = array(    // Televisions
        //     'lft' => 2,
        //     'rgt' => 9 
        // );
        // $this->treeCategory->moveAsPrevSibling($source, $target);
        

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