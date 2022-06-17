<?php
    function Edit_Anime_run($web, $params) {
        $v['currentPage'] = $web->getcurrentPage();
        if (isset($params["id"])) {
            $v['input-hiden'] = "style='display:none;'";
            $v['text'] = "Actualizar";
            $v["id"] = $params["id"];
            $media['action'] = 'getmediaby';
            $media['type'] = 'anime';
            $media['kind'] = 'anime';
            $media['id_relative'] = $v['id'];
            $media["siglas"] = $params["siglas"];
            $data = $web->apiReq("Upload", $media);
            $v['media'] = $data['status']['code'] == 200 ? $data['data']:'';
        } else {
            $v['input-hiden'] = "";
            $data = $web->apiReq("Anime&aq=lastidanime");
            $last_id = (int) $data['data'];
            $v["id"] = $last_id +1;
            $v['text'] = " Insertar";
            $v['media'] = null;
        }

        $v["siglas"] = isset($params["siglas"]) ? $params['siglas']:"";
        //$v["titulo_es"] = isset($params["titulo_es"]) ? $params['titulo_es']:"";
        //$v["titulo_en"] = isset($params["titulo_en"]) ? $params['titulo_en']:"";
        //$v["titulo_va"] = isset($params["titulo_va"]) ? $params['titulo_va']:"";
        //$v["titulo_ca"] = isset($params["titulo_ca"]) ? $params['titulo_ca']:"";
        //$v["sinopsis_es"] = isset($params["sinopsis_es"]) ? $params['sinopsis_es']:"";
        //$v["sinopsis_en"] = isset($params["sinopsis_en"]) ? $params['sinopsis_en']:"";
        //$v["sinopsis_va"] = isset($params["sinopsis_va"]) ? $params['sinopsis_va']:"";
        //$v["sinopsis_ca"] = isset($params["sinopsis_ca"]) ? $params['sinopsis_ca']:"";
        $v['generes'] = isset($params["generes"]) ? $params['generes']:"";
        $v["idiomas"] = isset($params["idiomas"]) ? $params['idiomas']:"";
        $v["date_publication"] = isset($params["date_publication"]) ? $params['date_publication']:date("Y-m-d");
        $v["date_finalization"] = isset($params["date_finalization"]) ? $params['date_finalization']:date("Y-m-d");
        $v["state"] = isset($params["state"]) ? $params['state']:"";
        $v["kind"] = isset($params["kind"]) ? $params['kind']:"";
        $v["temporada"] = isset($params["temporadas"]) ? $params['temporadas']:"";
        $v["valorations"] = isset($params["valorations"]) ? $params['valorations']:0;
        // $v["favorites"] = isset($params["favorites"]) ? $params['favorites']:false;
        //$v["views"] = isset($params["views"]) ? $params['views']:0;
        //$v["downloads"] = isset($params["downloads"]) ? $params['downloads']:0;

        $v['kind_list'] = " <div class='input-group radio'>  <p class='label'>Tipo: </p>";
            if ($v['kind'] == 'serie') $v['kind_list'] .= "<input type='radio'  id='serie' name='kind' checked value='serie'>";
            else $v['kind_list'] .= "<input type='radio' id='serie' name='kind' value='serie'>";
            $v['kind_list'] .= "<label for='serie' class='label' >Serie</label>";

            if ($v['kind'] == 'pelicula') $v['kind_list'] .= "<input type='radio' id='pelicula' name='kind' checked value='pelicula'>";
            else $v['kind_list'] .= " <input type='radio' id='pelicula' name='kind' value='pelicula'>";
            $v['kind_list'] .= "<label for='pelicula' class='label' >Pelicula</label>";

            if ($v['kind'] == 'ova') $v['kind_list'] .= "<input type='radio' id='ova' name='kind' checked value='ova'>";
            else $v['kind_list'] .= "<input type='radio' id='ova' name='kind' value='ova'>";
            $v['kind_list'] .= "<label for='ova' class='label' >OVA</label>";
        $v['kind_list'] .= "</div>";

        $data = $web->apiReq('Filters&aq=getFilters');
        if ($data['status']['code'] == 200) {
            $filters = $data['data'] ;
            $v['generes_list'] = '<div class="input-group checkbox">  <p class="label">Generos: </p>';
            foreach ($filters['generes'] as $genere ) {
                $array = array();
                array_push($array,$v['generes']);
                if (is_array($v['generes']) && in_array($genere['filter'], $array[0] )) {
                    $v['generes_list'] .= "<input  onchange='inputchanges(event.target)' type='checkbox' id='{$genere['filter']}' name='generes[]' checked value='{$genere['filter']}'>";
                } else {
                    $v['generes_list'] .= "<input  onchange='inputchanges(event.target)' type='checkbox' id='{$genere['filter']}' name='generes[]' value='{$genere['filter']}'>";
                }
                $v['generes_list'] .= "<label for='{$genere['filter']}' >{$genere['title']}</label>";
            }
            $v['generes_list'] .= '</div>'; 
    
            $v['temporada_list'] = '<div class="input-group checkbox">  <p class="label">Temporadas: </p>';
            foreach ($filters['temporadas'] as $genere) {
                $array = array();
                array_push($array,$v['temporada']);
                if (is_array($v['temporada']) && in_array($genere, $array[0] )) {
                    $v['temporada_list'] .= "<input  onchange='inputchanges(event.target)' type='checkbox' id='{$genere['filter']}' name='temporada[]' checked value='{$genere['filter']}'>";
                } else {
                    $v['temporada_list'] .= "<input  onchange='inputchanges(event.target)' type='checkbox' id='{$genere['filter']}' name='temporada[]' value='{$genere['filter']}'>";
                }
                $v['temporada_list'] .= "<label for='{$genere['filter']}' >{$genere['title']}</label>";
            }
            $v['temporada_list'] .= '</div>'; 
        } else {
            $v['kind_list'] = "<!-- -->";
            $v['generes_list'] = "<!-- -->";
            $v['temporada_list']  = "<!-- -->";
        }
        $v['star_valorations'] = array();
        for ($i=0; $i < 5; $i++) { 
            $star = $i < $v['valorations'] ? "fas fa-star": "far fa-star";
            array_push($v['star_valorations'], $star);
        }
        $v["web"] = $web;
        return $v;
    }
?>
