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
        // $c['db'];
        // $this->config['debug'] = true;
        
        echo $a;

        // $this->lvc->post('private/query');
        // if ($r[SUCCESS] == 1) {

        // }
    }
);

$app->func(
    'index', 
    function () {

        // $nested->addTree('electronics');
        // $nested->addChild(2, 2, 'Televisions');
        // $nested->appendChild(2, 5, 'plazma');
        // $nested->addSibling(2, 5, 'crt');
        // $nested->appendSibling(2, 4, '3d lcd');
        // 
        // 
        // $this->category = new Tree_Category;
        
        // $this->category->addTree('Electronics', $extra = array('column' => 'value'));
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