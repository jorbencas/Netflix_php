<?php
    function Upload_run($web, $params) {
        $v['SinID'] = "";
        if (isset($params)) {
            $v['upload_div'] = "";
            if (!empty($params)) {
                $v['li'] = "";
                foreach ($params as $l) {
                    if ($web->isImage($l['extension'])) {
                        $tamaño = "<p> tamaño: {$l['filesize']} </p>";
                        $principal =  $l['main'] == '1' ? "div_principal" : "";
                        if ($l['main'] == '1') {
                            $texto_principal = '<p class="principal" id="principal" > Imagen Principal </p>';
                        } else {
                            $texto_principal = '<p style="display:none;" class="principal" id="principal" >Imagen Principal</p>';
                        }
                        $descargar_fichero = "<a class='descargarfoto' href='$l[urldescarga]' download><i class='fas fa-cloud-download-alt' style=''></i></a>";
                        $v['li'] .= "<tr class='div_img {$principal}' id='idlimedia_{$l['id']}'><td class='img_div'>{$descargar_fichero}<img  class='{$principal}' idmedia='idmedia_{$l['id']}' type='{$l['type']}' kind='{$l['kind']}' id_relative='{$l['id_relative'] }' siglas='{$l['siglas']}' idioma='{$l['idiomas']}' onclick='selecionar_principal()' name='{$l['name']}.{$l['extension']}' src='{$l['urlarchivo']}' title='{$l['name']}.{$l['extension']}'/> </td> <td> <p> {$l['name']}.{$l['extension']} </p> {$tamaño} $texto_principal </td> <td style='text-align:right;'><div class='buttons'><div class='button' onclick='deletefile({$l['id']})'><i class='fa fa-trash' style='font-size:20px;'></i></div></div></td> </tr>";
                    } else if ($web->isVideo($l['extension'])) {
                        $descargar_fichero = "<a class='descargarfoto' href='$l[urldescarga]' download><i class='fas fa-cloud-download-alt'></i></a>";
                        $v['li'] .= "<tr class='div_pdf' id='idlimedia_{$l['id']}'><td class='img_div'>{$descargar_fichero}<img  idmedia='idmedia_{$l['id']}' name='{$l['name']}.{$l['extension']}' src='{$l['urlarchivo']}' type='{$l['type']}' kind='{$l['kind']}' id_relative='{$l['id_relative'] }' siglas='{$l['siglas']}' idioma='{$l['idiomas']}' title='{$l['name']}.{$l['extension']}' title='{$l['name']}.{$l['extension']}'/> </td> <td> <p> {$l['name']}.{$l['extension']} </p> <p> tamaño: {$l['filesize']} </p> </td> <td style='text-align:right;'><div class='buttons'><div class='button' onclick='deletefile({$l['id']})'><i class='fa fa-trash' style='font-size:20px;'></i></div></div></td> </tr>";
                    }
                }
            } else {
                $v['li'] = "<p>No hay ninguna foto!!!!</p>";
            }
        } else {
            $v['SinID'] = " <div class='sinID'> <div>Para agregar archivos debe antes guardar el contenido.</div> </div>";
            $v['upload_div'] = "style='cursor:not-allowed !important;'";
            $v['li'] = "";
        }
        return $v;
    }
?>