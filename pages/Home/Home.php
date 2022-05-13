<?php 
    function Home_run($web, $params) {
        $v['currentPage'] = $web->getcurrentPage();
        if ($web->getisSelectedProfile()) {
            $profile['profile'] = $web->getUserConfig()['profile']['profile'];
            $url = "Anime&aq=getfav";
        } else {
            $profile = null;
            $url = "Anime&as=0_8&od=created";
        }
        $data = $web->apiReq($url, $profile);
        $v['animes'] = $data['status']['code'] == 200 ? $data['data'] : null;
        if (isset($v['animes'])) {
            $v['params'] = array(
                'mod' => 'Anime',
                'elements' => $v['animes']
            ); 
        }
        $v['error_animes_msg'] = $data['status']['code'] != 200 ? $data['status']['message']: '';
        $data = $web->apiReq("Episodes&as=0_9");
        $v['episodes'] = $data['status']['code'] == 200 ? $data['data'] : null; 
        $v['error_episodes_msg'] = $data['status']['code'] != 200 ? $data['status']['message']: '';
        if (isset($v['episodes'])) {
            foreach ($v['episodes'] as $key => $value) {
                $value['urlTemporada'] = "";
                if (isset($value['kind']) && $value['kind'] == 'serie') {
                    $value['urlTemporada'] .= "&kind={$value['kind']}";
                }
                $v['episodes'][$key] = $value;
            }
        }
        $data = $web->apiReq("Anime&as=0_7&oa=created");
        $v['entradashead'] = $data['status']['code'] == 200 ? $data['data'] : null;
        $v['error_entradashead_msg'] = $data['status']['code'] != 200 ? $data['status']['message']: '';
        if (isset($v['entradashead'])) {
            foreach ($v['entradashead'] as $key => $value) {
                $value['urlTemporada'] = "";
                if (isset($value['kind']) && $value['kind'] == 'serie') {
                    $value['urlTemporada'] .= "&kind={$value['kind']}";
                }
                $v['entradashead'][$key] = $value;
            }
        }
        $v['generes'] = "<!-- -->";
        $data = $web->apiReq('Filters', array("action" => 'getFiltersAvaible'));
        $filters = $data['status']['code'] == 200 ? $data['data'] : null;
        if (isset($filters)) {
            $value = current($filters)['filter'];
            $data = $web->apiReq("Anime&f=generes_$value&as=0_9");
            if ($data['status']['code'] == 200 ) {
                $v['generes'] .= "<div class='home_slide_banner'><h3>{$filters[0]['title']}</h3> <a class='link' href='". $web->hrefMake("Anime&f=generes_$value") ."'>Ver mas + </a></div>";
                $v['generes'] .= $web->render('Grid', array(
                    'mod' => 'Anime',
                    'elements' => $data['data']
                ));  
                $v['generes'] .= "<div id='generes_final'></div>";
                current($filters)['avable'] = false;   
                $filters[1]['avable'] = true;
            } else {
                $v['generes'] = "<div id='generes_final'></div>";
            }
            $v['filters'] = "<script> var filters = JSON.stringify(". json_encode($filters, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) .") </script>";
        } else {
            $v['filters'] = null;
        }
        if ($web->getDevelop()) {
            $web->setDebug($data);
        }
        $v["web"] = $web;
        return $v;
    }
?>