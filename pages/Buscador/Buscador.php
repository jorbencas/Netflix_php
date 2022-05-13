<?php 
    function Buscador_run($web, $params) {
        $POST = $web->getPOST();
        if ($POST) {
            $v['class'] = "search_full";
            $data = $web->apiReq("Filters", $POST);
            if ($data['status']['code'] == 200 ) {
                $v['results'] = " <div class='animes'>";
                $v['results'] .= $web->render("Grid",array(
                    'mod' => 'Anime',
                    'elements' => $data['data']
                ));
                $v['results'] .= " </div>";
            } else {
                $v['results'] = "<div class='wrapper'>
                    {$web->msg($data['status']['message'],"error")}
                </div>";
                $v['placeholder'] = $web->translate("Buscador",'search');
                $v['searched'] = '';
            }
            $v['function'] = "heandlesearched";
            $v['chekcFunction'] = "onsubmit='ceckContent()'";
            $v['searched'] = $POST['search'];
            $v['placeholder'] = $POST['search'];
            if ($web->getisSelectedProfile()) {
                $v['profile'] = $web->getUserConfig()['profile']['profile'];
            } else {
                $v['profile'] = "";
            }
            $v["web"] = $web;
        } else {
            $web->redirect('/');
        }
        return $v;
    }
?>