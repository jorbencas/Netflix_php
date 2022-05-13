<?php
    function Edit_Episodes_run($web, $params) {
        $GET = $web->getGET();
        $v['currentPage'] = $web->getcurrentPage();
        if (isset($params["id"]) && isset($params['anime'])) {
            $v['text'] = "Actualizar";
            $v["id"] = $params["id"];
            $v["anime"] = $params['anime'];
            $media['action'] = 'getmediaby';
            $media['type'] = "episodes"; 
            $media['kind'] = 'episode';
            $media['id_relative'] = $v['id'];
            $media["siglas"] = $params["siglas"];
            $data = $web->apiReq("Upload", $media);
            $v['media'] = $data['status']['code'] == 200 ? $data['data'] : null;
        } else {
            $v['text'] = "Insertar";
            $data = $web->apiReq("Episodes&aq=lastidepisode");
            $last_id = (int) $data['data'];
            $v["id"] = $last_id + 1;
            $data = $web->apiReq("Anime&aq=lastidanime");
            $last_id = (int) $data['data'];
            $v['anime'] = isset($GET['id']) ? $GET['id'] : $last_id + 1;
            $v['media'] = null;
        }
        //$v["titulo_es"] = isset($params["titulo_es"]) ? $params['titulo_es']:"";
        //$v["titulo_en"] = isset($params["titulo_en"]) ? $params['titulo_en']:"";
        //$v["titulo_va"] = isset($params["titulo_va"]) ? $params['titulo_va']:"";
        //$v["titulo_ca"] = isset($params["titulo_ca"]) ? $params['titulo_ca']:"";
        //$v["sinopsis_es"] = isset($params["sinopsis_es"]) ? $params['sinopsis_es']:"";
        //$v["sinopsis_en"] = isset($params["sinopsis_en"]) ? $params['sinopsis_en']:"";
        //$v["sinopsis_va"] = isset($params["sinopsis_va"]) ? $params['sinopsis_va']:"";
        //$v["sinopsis_ca"] = isset($params["sinopsis_ca"]) ? $params['sinopsis_ca']:"";
        $v["num"] = isset($params["num"]) ? $params['num']:null;
        //$v["views"] = isset($params["views"]) ? $params['views']:0;
        //$v["downloads"] = isset($params["downloads"]) ? $params['downloads']:0;
        $v["web"] = $web;
        return $v;
    }
?>