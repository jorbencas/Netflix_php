<?php 
    function Tabla_run($web, $params) {
        $v['elements'] = $params['elements'];
        $v["web"] = $web;
        if ($params['mod'] == 'Anime') {
            foreach ($v['elements'] as $key => $anime) { 
                $anime['star_valorations'] = array();
                for ($i=0; $i < 5; $i++) { 
                    $star = $i < $anime['valorations'] ? "fas fa-star": "far fa-star";
                    array_push($anime['star_valorations'], $star);
                }
                $anime['head_favorite'] = (isset($anime['favorite']) && ($anime['favorite'] == 1 || $anime['favorite'] == "1" || $anime['favorite'] == true )) ? 'fas fa-heart' : 'far fa-heart';
                $anime['date_publication'] = isset($anime['date_publication']) ? $anime['date_publication']:"Sin Fecha";
                $anime['date_finalization'] = isset($anime['date_finalization']) ? $anime['date_finalization']:"Sin Fecha";
                switch ($anime['state']) {
                    case 'En Emisión':$anime['state_class'] = "state_sucess"; break;
                    case 'Pendiente': $anime['state_class'] = "state_warning"; break;
                    case 'Finalizado': $anime['state_class'] = "state_danger"; break;
                }
                if ($anime['kind'] == 'serie') {
                    $anime['temporada'] = "&kind=serie";
                } else {
                    $anime['temporada'] = "";
                }
                $v['elements'][$key] = $anime;
            }
        }
        $v['mod'] = $params['mod'];
        return $v;
    }
?>