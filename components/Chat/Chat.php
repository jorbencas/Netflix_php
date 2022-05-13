<?php 
  function Chat_run($web, $params) {
    $v['user'] = $web->getUserConfig()['user']['username'];
    $v['usuario'] =  "Seleciona un contacto";
    $v['avatar'] = $web->handleMedia('img','no_chat_user','png');
    $data = $web->apiReq("User&aa={$v['user']}"); 
    if ($data['status']['code'] == 200) {
      $v['list_users'] = $data['data'];
      if (count($v['list_users']) > 0) {
        $params['action'] = 'listmessages';
        $params['user'] = $v['user'];
        $params['receptor'] = $v['list_users'][0]['user'];
        $data = $web->apiReq('Chat', $params);
        $v['chat'] = $data['data']; 

        $data = $web->apiReq("User&aa={$params['receptor']}"); 
        $receptor = $data['data'];
        if ($receptor[0]['user'] !== $v['user']) {
          $v['usuario'] = $receptor[0]['user'];
          $v['avatar'] = $receptor[0]['avatar'];
        }
      } 
    }
    return $v;
  }
?>