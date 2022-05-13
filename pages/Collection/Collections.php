<?php
  function Collection_run($web, $params) {
    $GET = $web->getGET();
    $v['episode'] = $GET['id'];
    $data = $web->apiReq("Collections&ap={$GET['id']}");
    $v['collection'] = $data['data'];
    $proifile['username'] = $web->getUserConfig()['user']['username'];
    $proifile['action'] = 'get_profile';
    $proifile['profile'] = $web->getUserConfig()['profile']['profile'];
    $data = $web->apiReq("Profiles", $proifile); 
    $v['usuario'] = $data['data']['nombre'];
    $v['avatar'] = $data['data']['src'];
    $v["web"] = $web;
    return $v;
  }
?>