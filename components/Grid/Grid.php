<?php 
    function Grid_run($web, $params) {
        $v['mod'] = $params['mod'];
        $v['elements'] = $params['elements'];
        Grid_assets($web,$v);
        if ($v['mod'] == 'Anime' ) {
            $v['modulo'] = $web->getcurrentPage();
            foreach ($v['elements'] as $key => $anime) {
                switch ($anime['kind']) {
                    case 'pelicula': $anime['kind_class'] = 'pelicula'; break;
                    case 'ova': $anime['kind_class'] = 'ova';  break;
                    case 'temporada': $anime['kind_class'] = 'temporada'; break;
                    default: $anime['kind_class'] = 'serie';  break;
                }
                $anime['urlTemporada'] = "";
                if ($anime['kind'] == 'serie' || $anime['kind'] == "temporada") {
                    $anime['urlTemporada'] .= "&kind=serie";
                }
                if ($anime['kind'] == "temporada") {
                    $anime['urlTemporada'] .= "&seasion={$anime['seasion']}";
                    $anime['nuevo'] = "";
                } else {
                    $anime['sinopsis'] = strlen($anime['sinopsis']) > 150 ? substr($anime['sinopsis'], 0, 149) . "..." : $anime['titulo'];
                    $restar_una_semana = date("Y/m/d", time()-7*24*3600);
                    //$day = 1;
                    //$date = (new DateTime())->modify("-".$day." day");
                    $anime['nuevo'] = strtotime($anime['created']) > strtotime($restar_una_semana) ? 'nuevo':'';
                }
                $v['elements'][$key] = $anime;
            }
        } else {
            $v['urlTemporada'] = "";
            if (isset($v['elements'][0]["kind"]) && $v['elements'][0]["kind"] == "serie") {
                $v['urlTemporada'] = "&kind=serie";
            }
            $v['field'] = $params['field'];
        }
        $v["web"] = $web;
        return $v;
    }

    function Grid_assets($web,$params){
        if ($params['mod']  == 'Anime' ) {
            $web->setmodAssets(array("assets" => 'components/Grid/assets/css/Anime'));
        } else {
            $web->setmodAssets(array("assets" => 'components/Grid/assets/css/Grid'));
        }

        if(isset($params['elements'][0]["kind"]) && $params['elements'][0]["kind"] == "temporada"){
            $web->setmodAssets(array("assets" => 'components/Grid/assets/css/Sessions'));
        }
    }
?>