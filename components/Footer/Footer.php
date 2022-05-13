<?php
    function Footer_run($web, $params) {        
        $data = $web->apiReq("Anime&aq=lastanimes&as=0_9&od=id");
        $v['animes'] = $data['status']['code'] == 200 ? $data['data'] : null; 
        $v['error_anime_msg'] = $data['status']['code'] != 200 ? $data['status']['message']: '';
        foreach ($v['animes'] as $key => $anime) {
            $anime['urlTemporada'] = $anime['kind'] == 'serie' ? "&kind=serie" : "";
            $v['animes'][$key] = $anime;
        }
        $v['views'] = "";
        if ($web->getcurrentPage() == "Entradas") {
            $data = $web->apiReq("Footer&aq=getviews");
            $views = $data['status']['code'] == 200 ? $data['data'] : null;     
            if (isset($views) && is_numeric($views)) {
                $v['views'] = "Visitas:";
                $numbers = str_pad((string)$views, 6, '0', STR_PAD_LEFT);
                $lenght = str_split($numbers);
                foreach ($lenght as $value) {
                    $v['views'] .= "<li><b>$value</b></li>";
                }
            }
            if (!$web->getDevelop()) {
                $data = $web->apiReq("Footer&aq=setviews");
                $updated = $data['status']['code'] == 200 ? true:false;
            }
        }
        $_dias = array('Domingo', 'Lunes', 'Martes', 'Mi&eacute;rcoles', 'Jueves', 'Viernes', 'S&aacute;bado');
        $_meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $v['fecha_sitio'] = $_dias[date('w')] . " " . date('d') . " de ". $_meses[date('m')-1] . " del " . date('Y');
        $v["web"] = $web;
        return $v;
    }
?>