<?php 
    function List_run($web, $params) {
        $v['mod'] = $params['mod'];
        $v['elements'] = $params['elements'];
        $v["web"] = $web;
        if ($v['mod'] == 'Anime') {
            foreach ($v['elements'] as $key => $anime) { 
                $anime['titulo'] = strlen($anime['titulo']) > 150 ? substr($anime['titulo'], 0, 149) . "..." : $anime['titulo'];
                if ($anime['kind'] == 'serie') {
                    $anime['urlTemporada'] = "&kind=serie";
                } else {
                    $anime['urlTemporada'] = "";
                }
                $v['elements'][$key] = $anime;
            }
        }
        return $v;
    }
?>