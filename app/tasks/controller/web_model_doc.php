<?php
defined('STDIN') or die('Access Denied');

/**
 * $c web model api 
 * documentation build task
 * 
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
    new Url;
    new Html;
});

$c->func('index', function(){

$html_file = '<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
    '.$this->html->css('web_model/bootstrap.css').'
    '.$this->html->css('web_model/bootstrap-theme.css').'
<script type="text/javascript">
function ExceptionElement() {
    var elements = new Array();
    for (var i = 0; i < arguments.length; i++){
        var element = arguments[i];
        if (typeof element == \'string\')
            element = document.getElementById(element);
        if (arguments.length == 1)
            return element;
        elements.push(element);
    }
    return elements;
}
function ExceptionToggle(obj){
    var el = ExceptionElement(obj);
    if (el == null){
        return false;
    }
    el.className = (el.className != \'collapsed\' ? \'collapsed\' :  \'\' );
}
</script>
</head>

<body>
<div class="container">
    <div class="page-header">
        <h1><span class="green">Web Models</span> API <span style="font-size: 16px"> v1.0</span></h1> 
        <p class="lead"></p>
    </div>
    <div class="panel-group" id="accordion">';

    $folder = './public/web_models';

    $scan = scandir($folder);

    $dataArray = array();
    $table_tr = '';
    $i=0;
    foreach ($scan as $key => $value) 
    {
        if($value != '.' and $value != '..' and $value !='docs')
        {
            $subFolder = $folder.'/'.$value;
            $files = scandir($subFolder);
            foreach ($files as $k => $fileName) 
            {
                $xmlfile = strpos($fileName, '.xml');
                if ($xmlfile !== false) 
                {
                    $i++;
                    $xmlfile = $subFolder . '/' . $fileName;

                    $dom = new DomDocument; 
                    $dom->load($xmlfile);
                    
                    $data = array();
                    $phpPath = str_replace($folder.'/','',$xmlfile);
                    $phpPath = str_replace('.xml','',$phpPath);

                    $data['folder']       =  $phpPath;
                    $data['name']         =  $dom->getElementsByTagName('name')->item(0)->nodeValue;
                    $data['title']        =  $dom->getElementsByTagName('title')->item(0)->nodeValue;
                    $data['description']  =  $dom->getElementsByTagName('description')->item(0)->nodeValue;
                    $data['visibility']   =  $dom->getElementsByTagName('visibility')->item(0)->nodeValue;
                    $data['publish_date'] =  $dom->getElementsByTagName('publish_date')->item(0)->nodeValue;
                    $data['version']      =  $dom->getElementsByTagName('version')->item(0)->nodeValue;
                    $data['author']       =  $dom->getElementsByTagName('author')->item(0)->nodeValue;
 

                    if($dom->getElementsByTagName('post')->length > 0){
                        $data['request'] = 'POST';
                    
                    }
                    elseif($dom->getElementsByTagName('get')->length > 0){
                        $data['request'] = 'GET';   
                    
                    }
                    else{
                        $data['request'] = 'REQUEST';   
                    
                    }
                   $html_file .='  
                                <div class="panel panel-default">
                                    <a href="javascript:void(0);"  onclick="ExceptionToggle(\'arg_toggle_'.$i.'\');">
                                        
                                        
                                        <div class="panel-heading">
                                            <div class="postapi">'.$data['request'].'</div>
                                            <h4 class="panel-title" style="margin-left: 30px;">
                                               '.$data['folder'].' 
                                            </h4>
                                        </div>
                                        <div class="apipanelico"><span class="glyphicon glyphicon-chevron-down"></span></div>
                                    </a>
                                    <div id="arg_toggle_'.$i.'" class="collapsed">

                                        <div class="panel-body">
                                            <h1 class="green">'.$data['name'].' </h1><!-- -->
                                            <p><p>
                                            <h3 class="green">'.$data['title'].' </h3>
                                            <p><p>
                                            '.$data['description'].' 
                                            <p>
                                            <span class="visibilty green">Visibility</span>  <strong>'.$data['visibility'].' </strong>
                                            <p></p>
                                                <p></p>

                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Data</th>
                                                                <th>Type</th>
                                                                <th>Rules</th>
                                                                <th>Necessary</th>
                                                                <th>Description</th>
                                                                <th>Example</th>
                                                               
                                                            </tr>
                                                        </thead>
                                                        <tbody>';

                    foreach ($dom->getElementsByTagName('data') as $feeditem){
                        if ($feeditem->getAttribute('necessary') == 'required') {
                            $necessary = '<div class="required">'.$feeditem->getAttribute('necessary').'</div>';

                        }
                        else
                        {
                            $necessary = '<div class="optional">'.$feeditem->getAttribute('necessary').'</div>';
                        }
                        $dataData = trim($feeditem->nodeValue);
                        
                        $html_file .='
                                                            <tr>
                                                                <td>'.$feeditem->getAttribute('key') .'</td>
                                                                <td>'.$feeditem->getAttribute('type').'</td>
                                                                <td>'.$feeditem->getAttribute('rules').'</td>
                                                                <td>'.$necessary.'</td>
                                                                <td>'.$feeditem->getAttribute('desc').'</td>
                                                                <td>'.$dataData.'</td>
                                                            </tr>
                        ';
                    } 

                    $html_file.='  
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <p>
                                                <div class="author">Author : '.$data['author'].' </div>
                                            </p>
                                            <p>
                                                <div class="publishdate">v'.$data['version'].' <br>Publish Date : '.$data['publish_date'].' </div>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                           ';               
                }
            }
        }
    }

 $html_file .='
    </div>
</div>
</body>
</html>';

echo $html_file;

});

/* End of file web_model_doc.php */
/* Location: .app/tasks/controller/web_model_doc.php */