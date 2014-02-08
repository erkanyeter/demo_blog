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
   
    $folder =PUBLIC_FOLDER.'web_model';
    $scan = scandir($folder);

$html_file = '<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
    '.$this->html->css('web_model/bootstrap.css').'
    '.$this->html->css('web_model/bootstrap-theme.css').'
<script type="text/javascript">
    function ExceptionToggle(obj)
    {
        document.getElementById(obj).classList.toggle(\'collapsed\');
        
        var objArray = obj.split("_");
        maintoggle= "maintoggle_"+objArray[1].toString();
        document.getElementById(maintoggle).innerHTML = document.getElementById(maintoggle).innerHTML == "+" && "-" || "+";
    }
</script>
</head>

<body> 
<div class="container">
    <div class="page-header">
        <span class="green" style=" font-size: 30px;">Web Models</span>
    </div>
    <div class="panel-group" id="accordion">';

    
    $dataArray = array();
    $table_tr  = '';
    $i = 0;
$ii =0;
    foreach ($scan as $key => $value) 
    {
        if($value != '.' and $value != '..' and $value !='docs')
        {
            $subFolder = $folder.'/'.$value;
$ii++;
            $files = scandir($subFolder);
              $html_file .=' <div class="panel panelmain">
                                <div class="title_web_model">
                                    <a href="javascript:void(0);"  onclick="ExceptionToggle(\'argtogglediv_'.$ii.'\');" >
                                        [<span id="maintoggle_'.$ii.'">+</span>] '.$value.'
                                    </a>
                                </div>
                                <div class="collapsed"  id="argtogglediv_'.$ii.'" >
                                    ';


            foreach ($files as $k => $fileName) 
            {
                $xmlfile = strpos($fileName, '.xml');
                if ($xmlfile !== false) 
                {
                    $i++;
                    $xmlfile = $subFolder . '/' . $fileName;
                    
                    $dom     = new DomDocument; 
                    $dom->load($xmlfile);
                    
                    $data    = array();
                    $phpPath = str_replace($folder.'/','',$xmlfile);
                    $phpPath = str_replace('.xml','',$phpPath);

                    $data['folder']       =  $phpPath;
                    $data['name']         =  $dom->getElementsByTagName('name')->item(0)->nodeValue;
                    $data['description']  =  $dom->getElementsByTagName('description')->item(0)->nodeValue;
                    $data['visibility']   =  $dom->getElementsByTagName('visibility')->item(0)->nodeValue;
                    $data['publish_date'] =  $dom->getElementsByTagName('publish_date')->item(0)->nodeValue;
                    $data['version']      =  $dom->getElementsByTagName('version')->item(0)->nodeValue;
                    $data['author']       =  $dom->getElementsByTagName('author')->item(0)->nodeValue;
 

                    if($dom->getElementsByTagName('post')->length > 0)
                    {
                        $data['request'] = 'POST';
                    }
                    elseif($dom->getElementsByTagName('get')->length > 0)
                    {
                        $data['request'] = 'GET';
                    }
                    else
                    {
                        $data['request'] = 'REQUEST';
                    }

                    $html_file .='  
                               
                               
                                 <div class="panel panel-default  left25 ">
                                    
                                    <a href="javascript:void(0);"  onclick="ExceptionToggle(\'arg_toggle_'.$i.'\');">
                                        
                                        <div class="panel-heading">
                                            <div class="postapi">'.$data['request'].'</div>
                                            <h4 class="panel-title" style="margin-left: 30px;">
                                               '.$data['folder'].' 
                                            </h4>
                                        </div>
                                        <div class="apipanelico arrov"></div>
                                    </a>
                                    <div id="arg_toggle_'.$i.'" class="collapsed">

                                        <div class="panel-body">
                                            <div class="title green">'.$data['name'].' </div>
                                            '.$data['description'].' 
                                                <div class="table-responsive">
                                                    <table class="table">
                                                       
                                                            <tr>
                                                                <th>Data</th>
                                                                <th>Type</th>
                                                                <th>Rules</th>
                                                                <th>Is Require</th>
                                                                <th>Description</th>
                                                                <th>Example</th>
                                                               
                                                            </tr>
                                                        
                                                    ';

                        foreach ($dom->getElementsByTagName('data') as $feeditem)
                        {
                            if ($feeditem->getAttribute('required') == 'yes')
                            {
                                $required = '<div class="required">'.$feeditem->getAttribute('required').'</div>';
                            }
                            else
                            {
                                $required = '<div class="optional">'.$feeditem->getAttribute('required').'</div>';
                            }
                        
                            $dataData  = trim($feeditem->nodeValue);
                            
                            $html_file .='
                                                            <tr>
                                                                <td>'.$feeditem->getAttribute('key') .'</td>
                                                                <td>'.$feeditem->getAttribute('type').'</td>
                                                                <td>'.$feeditem->getAttribute('rules').'</td>
                                                                <td>'.$required.'</td>
                                                                <td>'.$feeditem->getAttribute('desc').'</td>
                                                                <td>'.$dataData.'</td>
                                                            </tr>';
                        } 

                    $html_file.='  
                                                       
                                                    </table>
                                                </div>
                                            <p>
                                                <div class="author">
                                                     v'.$data['version'].' <br>
                                                    Visibility : <span class="red">'.$data['visibility'].' </span>
                                           
                                                   
                                                   
                                                </div>
                                            </p>
                                            <p>
                                                <div class="publishdate"> Author : '.$data['author'].'  <br>Publish Date : '.$data['publish_date'].' </div>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                           
                           ';               
                }
            }
            $html_file.='   </div></div>';
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