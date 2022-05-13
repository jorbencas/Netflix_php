<?php
    function Video_run($web, $params) {
        if (isset($params['src'])) {
            $v['video'] = "
            <video preload='auto' class='screen' autoplay id='video' poster='{$params['img']}'>
                <source src='{$params['src']}' type='video/mp4' />
                <source src='{$params['src']}' type='video/ogg' />
                <source src='{$params['src']}' type='video/webm' />
                <p> Navegador no soporta este tipo de formato de video</p>
            </video>";
        } else {
            $v['no_video'] = $params['img'];
        }
        $v["web"] = $web;
        return $v;
    }
?>