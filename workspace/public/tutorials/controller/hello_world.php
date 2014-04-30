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
<<<<<<< HEAD:lvc/public/tutorials/controller/hello_world.php
        $c['tree.db'];
=======
        $c['db'];

        // $this->config['debug'] = false;
        echo $a;
>>>>>>> 66e5e5486c9aac5c81e6dd10ab65c7589234e803:workspace/public/tutorials/controller/hello_world.php
    }
);
$app->func(
    'index',
    function () {
<<<<<<< HEAD:lvc/public/tutorials/controller/hello_world.php
        
        
        
        // $this->treeDb->addTree('Electronics', $extra = array('column' => 'value'));
        // $this->treeDb->addChild(1, 'Televisions');
        // $this->treeDb->addChild(1, 'Portable Electronics');
        // $this->treeDb->appendChild($category_id = 2, 'Lcd');
        
        // $this->treeDb->addSibling($category_id = 4, 'Tube');
        // $this->treeDb->appendSibling($category_id = 4, 'Plasma');
        // 
        $this->treeDb->deleteNode($category_id = 7);


=======

        // var_dump($this->cache->getOption('OPT_SERIALIZER'));
>>>>>>> 66e5e5486c9aac5c81e6dd10ab65c7589234e803:workspace/public/tutorials/controller/hello_world.php

        // $this->treeDb->moveAsPrevSibling(8, 5);

        // echo '<pre>';

        // $source = array(
        //     'category_id' => 9,
        //     'parent_id'   => 0,
        //     'lft'         => 17,
        //     'rgt'         => 18
        // );
        // $target = array(
        //     'category_id' => 1,
        //     'parent_id'   => 0,
        //     'lft'         => 1,
        //     'rgt'         => 16
        // );
        // $this->treeDb->moveAsFirstChild($source, $target);

        // print_r($this->treeDb->isRoot(1));
        die;
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