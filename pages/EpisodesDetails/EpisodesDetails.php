<?php
    function EpisodesDetails_run($web, $params) {
        $GET = $web->getGET();
        if ($GET['id'] == 0 ) {
            $web->redirect('/');
        }
        $v['currentPage'] = $web->getcurrentPage();
        if (in_array($v['currentPage'],array("OpeningsDetails","EndingsDetails"))) {
            if ($v['currentPage'] == 'EndingsDetails') {
                $v['page_tittle'] = "Ending";
                $url = "Endings&ap={$GET['id']}";
            } else if ($v['currentPage'] == 'OpeningsDetails') {
                $v['page_tittle'] = "Opening";
                $url = "Openings&ap={$GET['id']}";
            }
            if (isset($GET['kind'])) {
                $url .= "&kind={$GET['kind']}";
            }
            $data = $web->apiReq($url);
            $v['episode'] = $data['status']['code'] == 200 ? $data['data'] : null;
            if (isset($v['episode'])) {
                $text = "{$v['page_tittle']} {$v['episode']['num']}";
                $web->setMetadescription("$text {$v['episode']['id']}, {$v['episode']['nombre']}");
                $web->setKeywords("{$v['episode']['nombre']}");
                $v['titulo'] = $v['episode']["anime_titulo"];
            }
        } else {
            $v['page_tittle'] = 'Capitulo';
            $url = "Episodes&ap={$GET['id']}";
            if (isset($GET['kind'])) {
                $url .= "&kind={$GET['kind']}";
            }
            $data = $web->apiReq($url);
            $v['episode'] = $data['status']['code'] == 200 ? $data['data'] : null; 
            if (isset($v['episode'])) {
                $text = "{$v['page_tittle']} {$v['episode']['num']}";
                $web->setMetadescription("$text {$v['episode']['id']}, {$v['episode']['epititulo']}");
                $web->setKeywords("{$v['episode']['epititulo']}");
                $v['titulo'] = $v['episode']["epititulo"];
            }
        }

        if (!$web->getIsMaster()) {
            $data = $web->apiReq("Anime&as=0_9&od=created");
            $v['animes'] = $data['status']['code'] == 200 ? $data['data'] : null;
            $v['params'] = array(
                'mod' => 'Anime',
                'elements' => $v['animes']
            );
            $v['error_animes_msg'] = $data['status']['code'] != 200 ? $data['status']['message']: '';
        } 

        if (!isset($v['episode'])) {
            $v['error_episodes_msg'] = $data['status']['code'] != 200 ? $data['status']['message']: '';
        } else {
            if (isset($v['episode']['prev'])) {
                $url = "{$v['currentPage']}&id={$v['episode']['prev']}";
                if (isset($GET['kind'])) {
                    $url .= "&kind={$GET['kind']}";
                }
                $v['prev'] = "<a class='first-child option' href='". $web->hrefMake($url) . "'><i class='fa fa-caret-left'></i>". $web->translate("EpisodesDetails",'episode_detil_prev')."</a>";
            }
            if (isset($v['episode']['next'])) {
                $url = "{$v['currentPage']}&id={$v['episode']['next']}";
                if (isset($GET['kind'])) {
                    $url .= "&kind={$GET['kind']}";
                }
                $v['next'] = "<a class='second-child option' href='". $web->hrefMake($url) . "'>". $web->translate("EpisodesDetails",'episode_detil_next') ." <i class='fa fa-caret-right'></i></a>";
            }
            $web->setPageTittle($v['episode']["anime_titulo"] . " " . $v["page_tittle"] . " " . $v['episode']['num']  . " - " . $v['titulo']);
        }
        
        if (isset($v['episode']['src'])) {
            $v['video'] = $v['episode'];
        } else {
            $v['video'] = $v['episode'];
        }
        $v["web"] = $web;
        return $v;
    }
?>