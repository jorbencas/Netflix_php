<?php 
    function Events_run($web, $params) {
        $data = $web->apiReq("Events&aa={$params}");
        $v['events'] = $data['status']['code'] == 200 ? $data['data'] : null;
        foreach ($v['events'] as $anime) { 
            $web->setMetadescription("{$anime['titulo']}");
            $web->setKeywords("{$anime['titulo']}");
        }
        return $v;
    };
?>