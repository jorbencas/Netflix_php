    <?php 
    function Anime_run($web, $params) {
        $GET = $web->getGET();
        if (isset($GET['id'])) {
            $url = "Anime&ap={$GET['id']}";
            if (isset($GET['seasion'])) {
                $url .= "&seasion={$GET['seasion']}";
            }
            if (isset($GET['kind'])) {
                $url .= "&kind={$GET['kind']}";
            }
            $data = $web->apiReq($url);
            $v['animes'] = $data['status']['code'] == 200 ? $data['data'] : null;
            $v['error_msg'] = $data['status']['code'] !== 200 ? $data['status']['message']: '';
            if (isset($v['animes'])) {
                if ($web->getDevelop()) {
                    $web->setDebug($v['animes']);
                }
                $web->setPageTittle($v['animes']["titulo"]);
                switch ($v['animes']['kind']) {
                    case 'pelicula': $v['animes']['kind_class'] = 'pelicula'; break;
                    case 'ova': $v['animes']['kind_class'] = 'ova';  break;
                    default: $v['animes']['kind_class'] = 'serie';  break;
                }
                $v['animes']['sinopsis'] = strlen($v['animes']['sinopsis']) > 150 ? substr($v['animes']['sinopsis'], 0, 149) . "..." : $v['animes']['titulo'];
                $v['animes']['star_valorations'] = array();
                for ($i=0; $i < 5; $i++) {
                    $star = $i < $v['animes']['valorations'] ? "fas fa-star": "far fa-star";
                    array_push($v['animes']['star_valorations'], $star);
                }
                $v['animes']['head_favorite'] = (isset($v['animes']['favorite']) && ($v['animes']['favorite'] == 1 || $v['animes']['favorite'] == "1" || $v['animes']['favorite'] == true )) ? 'fas fa-heart' : 'far fa-heart';
                $v['animes']['date_publication'] = isset($v['animes']['date_publication']) ? $v['animes']['date_publication']:"Sin Fecha";
                $v['animes']['date_finalization'] = isset($v['animes']['date_finalization']) ? $v['animes']['date_finalization']:"Sin Fecha";
                switch ($v['animes']['state']) {
                    case 'En Emisión':$v['animes']['state_class'] = "state_sucess"; break;
                    case 'Pendiente': $v['animes']['state_class'] = "state_warning"; break;
                    case 'Finalizado': $v['animes']['state_class'] = "state_danger"; break;
                }

                if (strstr($v['animes']['temporada'],",")) {
                    $v['animes']['temporada'] = explode(',',$v['animes']['temporada']);
                }
                if (isset($v['animes']['seasions'])) {
                    $seasions = array();
                    foreach ($v['animes']['seasions'] as $seasion) {
                        array_push($seasions, array(
                            "id" => $v['animes']['id'],
                            "src" => $v['animes']['src'],
                            "titulo" => $seasion['title'],
                            "kind" => "temporada",
                            "seasion" => $seasion['id'],
                        )); 
                    }
                    $v['params'] = array(
                        'mod' => $web->getIsMaster() ? 'Edit':'Anime',
                        'elements' => $seasions
                    );
                }

                $url = "&aa={$GET['id']}";
                if (isset($GET['seasion'])) {
                    $url .= "&seasion={$GET['seasion']}";
                }
                if (isset($GET['kind'])) {
                    $url .= "&kind={$GET['kind']}";
                }

                $data = $web->apiReq("Episodes$url");
                $v['episodes'] = $data['status']['code'] == 200 ? $data['data'] : null;
                $v['episodes_error_msg'] = $data['status']['code'] != 200 ? $data['status']['message']: '';
                if (isset($v['episodes'])) {
                    foreach ($v['episodes'] as $anime) { 
                        $web->setMetadescription("{$anime['epititulo']}");
                        $web->setKeywords("{$anime['epititulo']}");
                    }
                    $v['episodesparams']['mod'] = "EpisodesDetails";
                    $v['episodesparams']['field'] = "epititulo";
                    $v['episodesparams']['elements'] = $v['episodes'];
                }

                $url = "&aa={$GET['id']}";
                if (isset($GET['kind'])) {
                    $url .= "&kind={$GET['kind']}";
                }

                $data = $web->apiReq("Openings$url");
                $v['openings'] = $data['status']['code'] == 200 ? $data['data'] : null;
                $v['openings_error_msg'] = $data['status']['code'] !== 200 ? $data['status']['message']: '';
                if (isset($v['openings'])) {
                    foreach ($v['openings'] as $anime) { 
                        $web->setMetadescription("{$anime['nombre']}");
                        $web->setKeywords("{$anime['nombre']}");
                    }
                    $v['openingsparams']['mod'] = "OpeningsDetails";
                    $v['openingsparams']['field'] = "nombre";
                    $v['openingsparams']['elements'] = $v['openings'];
                }

                $url = "&aa={$GET['id']}";
                if (isset($GET['kind'])) {
                    $url .= "&kind={$GET['kind']}";
                }
                $data = $web->apiReq("Endings$url");
                $v['endings'] = $data['status']['code'] == 200 ? $data['data'] : null;
                $v['endings_error_msg'] = $data['status']['code'] !== 200 ? $data['status']['message']: '';
                if (isset($v['endings'])) {
                    foreach ($v['endings'] as $anime) {
                        $web->setMetadescription("{$anime['nombre']}");
                        $web->setKeywords("{$anime['nombre']}");
                    }
                    $v['endingparams']['mod'] = "EndingsDetails";
                    $v['endingparams']['field'] = "nombre";
                    $v['endingparams']['elements'] = $v['endings'];
                }
            }
        } else {
            $url = "";
            if (isset($GET['f'])) {
                $url .= "&f={$GET['f']}";
            }

            if (isset($GET['od'])) {
                $url .= "&od={$GET['od']}";
            } else if (isset($GET['oa'])) {
                $url .= "&oa={$GET['oa']}";
            }

            if ($web->getIsMaster()) {
                $apiUrl = "getlistanime&profile={$web->getUserConfig()['profile']['profile']}";
            } else {
                $apiUrl = "getnumanimes";
            }
            $data = $web->apiReq("Anime$url&aq=$apiUrl");
            if ($data['status']['code'] == 200) {
                $num_animes = $data['data'];
                $maxlimit = 25;
                setcookie('lastpage', ($num_animes / $maxlimit));
                $actual_page = isset($_GET['p']) ? $_GET['p'] : 1;
                if((int)$_COOKIE['lastpage'] < $actual_page){
                    $actual_page = 1;
                }
                $first = $actual_page > 1 ? ($actual_page - 1) * $maxlimit : 0;
                $last = ($num_animes - ($first + $maxlimit)) < $maxlimit ? $num_animes : ($first + $maxlimit);
                $f = $num_animes == 1 ? 0 : $first;
                $data = $web->apiReq("Anime$url&as=".$f . "_" . $last);
                $v['animes'] = $data['status']['code'] == 200 ? $data['data'] : null;
                $v['error_msg'] = $data['status']['code'] !== 200 ? $data['status']['message']: '';
                if (isset($v['animes'])) {
                    $v['params'] = array(
                        'mod' => $web->getIsMaster() ? 'Edit':'Anime',
                        'elements' => $v['animes']
                    );
                    $v['mod'] = $url;
                    foreach ($v['animes'] as $anime) {  
                        $web->setMetadescription("{$anime['titulo']}, {$anime['sinopsis']}, {$anime['kind']}");
                        $web->setKeywords("{$anime['titulo']}, {$anime['kind']}");
                    }
                    if ($web->getisSelectedProfile()) {
                        $config = $web->getUserConfig();
                        $v['option_paginator'] = isset($config['profile']) ? $config['profile']['option_paginator'] : 'classic';
                    } else {
                        $v['option_paginator'] = 'new';
                    }
                    if ($v['option_paginator'] == 'new') {
                        if ($maxlimit < $num_animes) {
                            if ($actual_page == 1) {
                                $v['next_page'] = $actual_page + 1;
                                $result = (int)$_COOKIE['lastpage'];
                                //cuando se quiera redondear un float se debe pasar a string y luego ha float 
                                if (is_float($result)) {
                                    $res = explode(".",$result);
                                    $v['prev_page'] = $res[0];
                                } elseif (is_int($result)) {
                                    $v['prev_page'] = $result;
                                }
                            } elseif ($num_animes == $last) {
                                $v['next_page'] = 1;
                                $v['prev_page'] = $actual_page - 1;
                            } else {
                                $v['next_page'] = $actual_page + 1;
                                $v['prev_page'] = $actual_page - 1;
                            }
                            $v['first'] = $first;
                            $v['last'] = $last;
                            $v['num_animes'] = $num_animes;
                        }
                    } else if ($v['option_paginator'] == 'classic') {
                        $v['num_animes'] = $num_animes;
                        $v['maxlimit'] = $maxlimit;
                    }
                }
            } else {
                $v['error_msg'] = $web->translate("Anime","api_Anime_error_msg");
            }
        }
        Anime_assets($web, $params);
        $v["web"] = $web;
        $v['currentPage'] = $web->getcurrentPage();
        return $v;
    };


    function Anime_assets($web, $params){
        $GET = $web->getGET();
        if (isset($GET['id'])) {
            $file = 'pages/Anime/assets/css/details';
        } else {
            $file = 'pages/Anime/assets/css/list';
        }
        $web->setmodAssets(array("assets" => $file));

    }
?>