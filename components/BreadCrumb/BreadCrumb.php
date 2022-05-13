<?php   
    function BreadCrumb_run($web, $params) {
        if (isset($params)) {
            $v['currentPage'] = $web->getcurrentPage();
            $breadcrumb = array();
            if (in_array($v['currentPage'],array("OpeningsDetails","EndingsDetails","EpisodesDetails",'aleatory'))) {
                array_push($breadcrumb, (object) array('name' => 'Anime', 'href' => $web->hrefMake('Anime') ));
                if (isset($params['seasions'])) {
                    array_push($breadcrumb, (object) array('name' => $params['anime_titulo'], 'href' => $web->hrefMake("Anime&id={$params['anime']}&kind=serie&seasion={$params['id_external']}") ));
                }else{
                    array_push($breadcrumb, (object) array('name' => $params['anime_titulo'], 'href' => $web->hrefMake("Anime&id={$params['anime']}") ));
                }
                if ($v['currentPage'] == 'EndingsDetails') {
                    $text = "Ending {$params['num']} {$params['nombre']}";
                } else if ($v['currentPage'] == 'OpeningsDetails') {
                    $text = "Opening {$params['num']} {$params['nombre']}";
                } else {
                    if (isset($params['seasions'])) {
                        array_push($breadcrumb, (object) array('name' => "Temporada " . $params['seasions'], 'href' => $web->hrefMake("Anime&id={$params['anime']}&kind=serie&seasion={$params['id_external']}") ));
                    }
                    $text = "Capitulo {$params['num']} {$params['epititulo']}";
                }
                array_push($breadcrumb, (object) array('name' => $text));
            } elseif ($v['currentPage'] == 'Filesystem') {
                $content = explode("/",$params);
                if (sizeof($content) > 2) {
                    foreach ($content as $key => $value) {
                        if ($key < (sizeof($content) - 1 )) {
                            array_push($breadcrumb, (object) array('name' => $value, 'href' => $web->hrefMake("{$v['currentPage']}&fp=$value/") ));
                        }
                    }
                } else {
                    array_push($breadcrumb, (object) array('name' => "Media", 'href' => $web->hrefMake("{$v['currentPage']}") ));
                }
            // } elseif ($v['currentPage'] == 'User') {
            //     # code...
            } elseif ($v['currentPage'] == 'Entradas') {
                array_push($breadcrumb, (object) array('name' => 'Blog', 'href' => $web->hrefMake($v['currentPage']) ));
            }
            $v['modules'] = $breadcrumb;
        }
        return  $v;
    }
?>