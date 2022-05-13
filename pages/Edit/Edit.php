<?php 
    function Edit_run($web, $params) {
        $GET = $web->getGET();
        $v['currentPage'] = $web->getcurrentPage();
        if (isset($GET["id"])) {
            $data = $web->apiReq("Anime&ap={$GET['id']}");
            $v['anime'] = $data['data'];
            $data = $web->apiReq("Episodes&aa={$GET['id']}");
            $v['episode'] = $data['data'];
            $data = $web->apiReq("Openings&aa={$GET['id']}");
            $v['opening'] = $data['data'];
            $data = $web->apiReq("Endings&aa={$GET['id']}");
            $v['ending'] = $data['data'];
        } else {
            $v['anime'] =  null;
            $v['episode'] = null;
            $v['opening'] = null;
            $v['ending'] = null;
            $v['steps'] = array(
                array("name" => "1", "translate" => "list_anime", "class" => "anime"),
                array("name" => "2", "translate" => "anime_detail_list_episode", "class" => "episodes"),
                array("name" => "3", "translate" => "anime_detail_list_openings", "class" => "openings"),
                array("name" => "4", "translate" => "anime_detail_list_endings", "class" => "endings"),
                array("name" => "A", "translate" => "end", "class" => "all")
            );
        }
        Edit_assets($web, $params);
        $v["web"] = $web;
        return $v;
    }

    function Edit_assets($web, $params) {
        $web->setmodAssets(array("assets" => 'pages/Edit/assets/js/validators'));
    }