<?php
  function Collections_run($web, $params) {
    $GET = $web->getGET();
    $v['currentPage'] = $web->getcurrentPage();
      $v['episode'] = $GET['id'];
      $data = $web->apiReq("Collections&aa={$web->getUserConfig()['profile']['profile']}");
      if ($data['status']['code'] == 200) {
        $collections = $data['data'];
        $data = $web->apiReq("Collections&aq=getcollectionsby&id={$GET['id']}&profile={$web->getUserConfig()['profile']['profile']}");
        $mycollections = $data['data'];
        $v['inputs'] = "";
        if ($data['status']['code'] == 200) {
          foreach ($collections as $key => $collection) {
            foreach ($mycollections as $key => $my) {
              if ($collection['id'] == $my['id']) {
                $v['inputs'] .= "
                <div class='input-group radio'>
                  <input type='radio' id='{$collection['id']}' checked  onclick='removeone(`{$collection['id']}`)' value='{$collection['id']}'>
                  <label for='{$collection['id']}' class='label'>{$collection['titulo']}</label>
                </div>";
              } else {
                $v['inputs'] .= "
                <div class='input-group radio'>
                  <input type='radio' id='{$collection['id']}' onclick='addelement(`{$collection['titulo']}`)' value='{$collection['id']}'>
                  <label for='{$collection['id']}' class='label'>{$collection['titulo']}</label>
                </div>";
              }
            }
          }
        } else {
          foreach ($collections as $key => $collection) {
              $v['inputs'] .= "
              <div class='input-group radio'>
                <input type='radio' id='{$collection['id']}' onclick='addelement(`{$collection['titulo']}`)' value='{$collection['id']}'>
                <label for='{$collection['id']}' class='label'>{$collection['titulo']}</label>
              </div>";
          }
        }
      } else {
        $v['error_collections_msg'] = $data['status']['message'];
      }
    $v["web"] = $web;
    return $v;
  }
?>