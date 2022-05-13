<?php
    function Apidoc_run($web, $params) {
        $data = $web->apiReq("Admin", array(
            "action" => 'scan'
        ));
        $v['apis'] = $data['status']['code'] == 200 ? $data['data'] : null; 
        $v['error_msg'] = $data['status']['code'] !== 200 ? $data['status']['message']: '';
        /*
        //opciones
        $_GET['r'] ruta principal del proyecto
        $_GET['id'] id de detalle
        $_GET['p'] pagina
        $_GET['c'] code del alert
        $_GET['t'] texto del alert
        $_GET['f'] obtener filtros de la url
        $_GET['oa'] obtener listado ordenado ascedentemente
        $_GET['od'] obtener listado ordenado descendentemente
        $_GET['fp'] file path para que el admin filesystem lo escane
        $_GET['am'] opción para cargar el modulo
        $_GET['ap'] parametro de detalle
        $_GET['as'] slides que debe retornar
        $_GET['aa'] opción para obtener algo apartir de un anime
        $_GET['aq'] se le pasa directamente el nombre de la función ha ejecutar
        $_GET['profile'] se le pasa el profile para ciertas aciones
        $_GET['seasion'] se pasa la temporada del anime
        $_GET['kind'] se pasa el tipo de anime que es para saber si se tiene que filtrar por la temporada
        */
        
        return $v;
    }
?>