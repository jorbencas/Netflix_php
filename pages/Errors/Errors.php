<?php
    function Errors_run($web, $params) {
        $v['code'] = isset($params['code']) ? $params['code'] : '404';
        $v['text'] = isset($params['text']) ? $params['text'] : 'El modulo que se intenta cargar no es valido!!!!';
        $v["web"] = $web;
        return $v;
    }
?>