<?php 
  function History_run($web, $params) {
    $history['action'] = 'gethistory';
    $history['profile'] = $web->getUserConfig()['profile']['profile'];
    $data = $web->apiReq("History", $history);
    $v['history'] = $data['status']['code'] == 200 ? $data['data'] : null;
    $v['error_history_msg'] = $data['status']['code'] !== 200 ? $data['status']['message']: '';
    $v["web"] = $web;
    return $v;
  }
?>