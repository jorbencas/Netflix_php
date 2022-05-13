<?php
    function Edit_Endings_run($web, $params) {        
        $GET = $web->getGET();
        $v['currentPage'] = $web->getcurrentPage();
        if (isset($params["id"]) && isset($params['anime'])) {
            $v['text'] = "Actualizar";
            $v["id"] = $params["id"];
            $v["anime"] = $params['anime'];
            $media['action'] = 'getmediaby';
            $media['type'] = "endings"; 
            $media['kind'] = 'ending';
            $media['id_relative'] = $v['id'];
            $media["siglas"] = $params["siglas"];
            $data = $web->apiReq("Upload", $media);            
            $v['media'] = null; //$data['status']['code'] == 200 ? $data['data'] : null;
        } else {
            $v['text'] = "Insertar";
            $data = $web->apiReq("Endings&aq=lastidending");
            $last_id = (int) $data['data'];
            $v["id"] = $last_id + 1;
            $data = $web->apiReq("Anime&aq=lastidanime");
            $last_id = (int) $data['data'];
            $v['anime'] = isset($GET['id']) ? $GET['id'] : $last_id + 1;
            $v['media'] = null;
        }
        $v["nombre"] = isset($params["nombre"]) ? $params['nombre']:"";
        $v["descripcion"] = isset($params["descripcion"]) ? $params['descripcion']:"";
        $v["num"] = isset($params["num"]) ? $params['num']:null;
        $v["web"] = $web;
        return $v;
    }
?>