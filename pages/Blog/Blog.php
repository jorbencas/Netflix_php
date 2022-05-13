<?php 
    function Blog_run($web, $params) {
        $v['currentPage'] = $web->getcurrentPage();
        $data = $web->apiReq("Blog&aq=entradas");
        $v['entradas'] = $data['status']['code'] == 200 ? $data['data'] : null; 
        $data = $web->apiReq("Blog&aq=lastposts");
        $v['entradashead'] = $data['status']['code'] == 200 ? $data['data'] : null; 
        $v['error_entradashead_msg'] = $data['status']['code'] != 200 ? $data['status']['message']: '';
        $v["web"] = $web;
        return $v;
    }
?>