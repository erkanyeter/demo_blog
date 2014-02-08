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
    function ExceptionToggle(obj)
    {
       document.getElementById(obj).classList.toggle(\'collapsed\'); 
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

    $folder = './public/web_model';

    $scan = scandir($folder);

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
              $html_file .=' <div class="panel panel-default">
                                <div class="title_web_model">
                                    <a href="javascript:void(0);" onclick="ExceptionToggle(\'arg_toggle_div_'.$ii.'\');" >
                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAxBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MzE4ODYzMUU5MEEzMTFFMzhGQ0NDOTA2RTMyM0U3NUYiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MzE4ODYzMUQ5MEEzMTFFMzhGQ0NDOTA2RTMyM0U3NUYiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiBXaW5kb3dzIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9IjI0MjUzODExRjlFMkYwN0Q0OEVFMTkxMENDMEExODMzIiBzdFJlZjpkb2N1bWVudElEPSIyNDI1MzgxMUY5RTJGMDdENDhFRTE5MTBDQzBBMTgzMyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PpM+VI4AAAChSURBVHjaYmTICGfAA4qBuAdNrBeISzBU/v3LsLOwloGJgcpg1EDaG/gDi9hPXIq52NgZWKBJAxdwxCJmi1UPExPDxH3bGBiB6fA/1fz76yeVwxDo5UEey79+gSOlF48SM2gkIINzQLwfQ+X//wxOekZgA0vwGJiNxcAdQFyNofLfP4Z672CCXubAIsaOS/HPP3+oG4b/gd4eLRwoBwABBgA5CSJGM6NVhwAAAABJRU5ErkJggg==">
                                        '.$value.'
                                    </a>
                                </div>
                                <div class="collapsed"  id="arg_toggle_div_'.$ii.'" >
                                    ';


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
                                            <h3 class="green">'.$data['name'].' </h3><!-- -->
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
                        
                            $dataData = trim($feeditem->nodeValue);
                        
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