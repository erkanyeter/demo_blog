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
        $c['tree.db'];

        $c['db'];
        // $this->config['debug'] = false;

        echo $a;
    }
);

$app->func(
    'index', 
    function () {

        $this->treeDb->setTablename('nested_category');
        $this->treeDb->setPrimaryKey('category_id');
        $this->treeDb->setText('name');
        $this->treeDb->setLft('lft');
        $this->treeDb->setRgt('rgt');

        // $source = array(    // Portable Electronics
        //     'lft' => 9,
        //     'rgt' => 14
        // );

        // $target = array(    // Televisions
        //     'lft' => 2,
        //     'rgt' => 15 
        // );
        // $this->treeDb->moveToNextSibling($source, $target);
        // 
        
        // $this->treeDb->addTree('Electronics', $extra = array('column' => 'value'));
        // $this->category->addChild(1, 1, 'Televisions');
        // $this->category->addChild(1, 1, 'Portable Electronics');
        // $this->category->appendChild(2, 5, 'Lcd');
        // $this->category->addSibling(2, 5, 'Tube');
        // $this->category->appendSibling(2, 8, 'Plasma');
        
        // $this->category->cache(true);
        // $this->category->query('SELECT * FROM nested categoy');
        // $this->catagory->deleteCache();
        // $this->category->truncateTable();
        
        // $this->category->updateNode(2, array('name' => 'Televisions', 'column' => 'new value'));

        // $source = array(    // Portable Electronics
        //     'lft' => 9,
        //     'rgt' => 14
        // );

        // $target = array(    // Televisions
        //     'lft' => 2,
        //     'rgt' => 15 
        // );
        // $this->treeDb->moveAsFirstChild($source, $target);

        // $source = array(    // Portable Electronics
        //     'lft' => 10,
        //     'rgt' => 15
        // );

        // $target = array(    // Televisions
        //     'lft' => 2,
        //     'rgt' => 9 
        // );
        // $this->treeDb->moveAsPrevSibling($source, $target);

        
        $this->view->load(
            'hello_world', 
            function () {
                $this->assign('name', 'Obullo');
                $this->assign('footer', $this->getTpl('footer', false));
            }
        );

    }
);

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */