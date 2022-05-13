<?php 
    function Buscador_run($web, $params) {
        $v['class'] = "search_contenent";
        $v['results'] = '';
        $v['function'] = "handlesearch";
        $v['chekcFunction'] = "onsubmit='ceckContent()'";
        $v['placeholder'] = $web->translate("Buscador",'search');
        if ($web->getisSelectedProfile()) {
            $v['profile'] = $web->getUserConfig()['profile']['profile'];
        } else {
            $v['profile'] = "";
        }
        $v["web"] = $web;
        return $v;
    }
?>