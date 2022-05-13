<?php
    function Admin_run($web, $params) {
        $v['currentPage'] = $web->getcurrentPage();
        $v["web"] = $web;
        //$v['config'] = $web->getUserConfig();
        if ($v['currentPage'] == 'Backup') {
            $data = $web->apiReq("Admin&aq=gettables");
            $v['data'] = $data['status']['code'] == 200 ? $data['data'] : null; 
        } elseif ($v['currentPage'] == 'Showerrors') {
            $data = $web->apiReq("Admin&aq=geterrors");
            $v['data'] = $data['status']['code'] == 200 ? $data['data'] : null; 
        } elseif ($v['currentPage'] == 'Filesystem') {
            $GET = $web->getGET();
            $path = isset($GET['fp']) ? $GET['fp'] : 'media';
            $array_data = array();
            if (strstr($path," ")) {
                $path = str_replace(" ", "%20", $path);
            }

            $data = $web->apiReq("Admin", array(
                "action" => 'filesystem',
                "path" => $path
            ));
            $v['file_list'] = $data['status']['code'] == 200 ? $data['data'] : null; 
            $v['file_error_list'] = $data['status']['code'] !== 200 ? $data['status']['message']: '';
        }
        if (isset($data['specials']) && isset($data['specials']['alert'])) {
            $v["web"]->setCookie('alert',$data['specials']['alert']);
        }
        return $v;
    }
?>