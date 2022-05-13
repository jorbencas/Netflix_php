<?php
    function Web_run($web, $params) {
        Web_assets($web);
        $v['islogged'] = $web->getIsLogged();
        $v['currentPage'] = $web->getcurrentPage();
        $pageTitle = $web->translate('Web','titulo') ." | ". $v['currentPage'];
        $web->setPageTittle($pageTitle);
        if ($v['currentPage'] !== 'Errors') {
            if (count($_COOKIE) == 0){
                $v['modals'] = $web->render('Modals', $web->snedDataModal('Cookies') );
            }

            if ($v['islogged']) {
                if ($v['currentPage'] == 'Entradas') {
                    $v['buttons'] .= "<button class='boton_modal button_fixed' onclick='openchat()'> <i class='fas fa-comment'></i> </button>";
                    $v['buttons'] .= "<button class='boton_chat button_fixed' onclick='openchat()'> <i class='fas fa-comment'></i> </button>";
                }
                if ($web->getisSelectedProfile()) {
                    $data = $web->apiReq("User", array(
                        'user' => $_SESSION['auth']['username'], 
                        'action' => "getconfig_user",
                    ));
                    $userConfig = $data['data'];
                    $data = $web->apiReq("Config", array(
                        'profile' => $_SESSION['auth']['profile'], 
                        'action' => "getconfig_profile",
                    ));
                    $web->setUserConfig(array(
                        "user" => $userConfig,
                        "profile" => $data['data'],
                    ));
                    $web->setTheme($data['data']['theme']);
                } else {
                    $v['modals'] = $web->render('Modals', $web->snedDataModal('Profiles'));
                }

                if (isset($_SESSION['auth']['acces_token'])) {
                    $web->setCookie('acces_token', $_SESSION['auth']['acces_token']);
                }
                if ($web->getIsMaster() && isset($_SESSION['auth']['admin_token'])) {
                    $web->setCookie('admin_token', $_SESSION['auth']['admin_token']);
                }
            } elseif (!$v['islogged'] && $v['currentPage'] == 'Entradas') {
                $v['buttons'] = "<button class='boton_modal button_fixed' onclick='openchat()'> <i class='fas fa-comment'></i> </button>";
                $v['buttons'] .= "<button class='ir-arriba button_fixed'><i class='fas fa-chevron-up'></i></button>";
            }

            $cookie = $web->getCookie('alert');
            if (isset($cookie) || isset($web->getPOST()['alert'])) {
                if (isset($cookie)) {
                    $alert = json_decode($cookie);
                    $web->setCookie("alert",null,true);
                } else {
                    $alert = $web->getPOST()['alert'];
                    unset($web->getPOST()['alert']);
                }
                $alert = get_object_vars($alert);
                $v['open_alert'] = "openalert('{$alert['c']}', '" . $alert['t'] . "');";
                switch ($alert['c']) {
                    case 's': 
                    case 'w':
                    case 'i': 
                        $sound = 'notification'; 
                    break;
                    default: 
                        $sound = 'error'; 
                    break;
                }
                $v['notification'] = $web->handleMedia('notifications', $sound, 'mp3');
            }
            
            $v['header'] = $web->render('Header');
            $v['footer'] = $web->render('Footer');
        } else if ($v['islogged'] && isset($_SESSION['errorlogin'])) {
            $params = $_SESSION['errorlogin'];
        }
        
        $v['main'] = $web->render(null, $params);
        $v['pageTitle'] = $web->getPageTittle();
        $assets = $web->renderAssets();
        $v['css'] = $assets['css'];
        $v['js'] = $assets['js'];
        $v["web"] = $web;
        return $v;
    }

    function Web_assets($web){
        if ($web->getDevelop()) {
            $web->setmodAssets(array("assets" => 'components/Web/assets/css/debug'));
        }

        if ($web->getcurrentPage() == 'Entradas') {
            $web->setmodAssets(array("assets" => 'components/Web/assets/css/entrada'));
            $web->setmodAssets(array("assets" => 'components/Web/assets/js/entrada'));
        }
        $web->setmodAssets(array("assets" => 'components/Web/assets/js/Web'));
    }
?>