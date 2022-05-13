<?php
    function Comments_run($web, $params) {
        if ($web->getisSelectedProfile()) {
            $currentPage = $web->getcurrentPage();
            $GET = $web->getGET();
            $data = $web->apiReq("User&ap={$web->getUserConfig()['user']['username']}"); 
            $auth = $data['data'];
            $v['usuario'] = $auth['username'];
            $v['avatar'] = $auth['avatar'];
            $v['episode'] = null;
            $v['anime'] = null;
            if ($currentPage == 'User') {
                $url = "Comments&ap={$web->getUserConfig()['profile']['profile']}";
            } else if ($currentPage == 'aleatory' || $currentPage == 'EpisodesDetails') {
                $url = "Comments&aa={$GET['id']}";
                $v['episode'] = $GET['id'];
            } else {
                $url = "Comments&aa={$GET['id']}";
                $v['anime'] = $GET['id'];
            }
            
            $data = $web->apiReq($url);
            $comments = $data['status']['code'] == 200 ? $data['data'] : null;  
            if (isset($comments)) {
                foreach ($comments as $key => $value) {
                    $data = $web->apiReq("User&ap={$value['username']}"); 
                    $comments[$key]['avatar'] = $data['data'][0]['avatar'];
                }
            }

            $v['comments'] = $comments;
            $v['error_msg'] = $data['status']['code'] !== 200 ? $data['status']['message']: '';
            $v['modulo'] = $currentPage;
        } else {
            $v['error_msg'] = "Para ver los comentarios debes de iniciar sessión";
        }
        $v["web"] = $web;
        return $v;
    }
?>