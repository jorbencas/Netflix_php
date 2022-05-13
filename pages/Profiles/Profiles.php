<?php
  function Profiles_run($web, $params) {
    $POST = $web->getPOST();
    if (isset($POST)) {
      $data = $web->apiReq("Profiles", $POST);
      if ($data['status']['code'] == 200 ) {
        $_SESSION['auth']['profile'] = $data['data']['id'];
        $alert = "s"; 
        $url = "/";
      } else {
        $alert = "d";
        $url = $web->getcurrentPage();
      }
      $web->redirect($url, $alert,$data['status']['message']);
    } else if ($web->getisSelectedProfile()) {
      $profile['action'] = 'get_profile';
      $profile['username'] = $web->getUserConfig()['user']['username'];
      $profile['profile'] = $web->getUserConfig()['profile']['profile'];
      $data = $web->apiReq("Profiles", $profile); 
      $v['profile'] = $data['status']['code'] == 200 ? $data['data'] : null;
      if (isset($v['profile'])) {
        $v['avatar'] = $v['profile'][0]['src'];
        $v['nombre'] = $v['profile'][0]['nombre'];
      }
      
      $profile['profile'] = $web->getUserConfig()['profile']['profile'];
      $data = $web->apiReq("Anime&aq=getfav", $profile); 
      $v['favorites'] = $data['status']['code'] == 200 ? $data['data'] : null;
      if (isset($v['favorites'])) {
        $v['params'] = array(
          'mod' => 'Anime',
          'elements' => $v['favorites']
        );
      }
      $v['error_favorites_msg'] = $data['status']['code'] !== 200 ? $data['status']['message']: '';
      $media['action'] = 'getmediaby';
      $media['type'] = 'profiles';
      $media['kind'] = 'profile';
      $media['id_relative'] = $v['profile'];
      $data = $web->apiReq("Upload", $media);
      $v['media'] = $data['status']['code'] == 200 ? $data['data']:'';
    } else {
      $v['username'] = $web->getUserConfig()['user']['username'];
      $profile['action'] = 'getprofilesbyuser';
      $profile['username'] = $v['username'];
      $data = $web->apiReq("Profiles", $profile); 
      $v['profiles'] = $data['status']['code'] == 200 ? $data['data'] : null;
      $v['error_msg'] = $data['status']['code'] !== 200 ? $data['status']['message']: '';
    }   
    $v["web"] = $web;
    return $v;
  }
?>