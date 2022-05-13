<?php
    require_once __DIR__ . '/classes/web.php';
    $web = new Web();
    $currentPage = $web->getcurrentPage();
    if ($web->isAjax() && in_array($currentPage, $web->getAjaxModules())) {
        $POST = $web->getPOST();
        $v['content'] = $web->render($currentPage, $POST);
        $web->setTheme($POST['theme']);
        unset($POST['theme']);
        $assets = $web->renderAssets();
        $v['css'] = $assets['css'];
        $v['js'] = $assets['js'];
        echo json_encode($v); 
    } elseif (!$web->isAjax()) {
        $POST = $web->getPOST();
        session_start();
        $islogged = $web->getIsLogged();
        if ($islogged && isset($POST['action']) && $POST['action'] == "logout") {
            //falsear para poder realizar logut desde el navegador
            if ($web->isLocalHost()) {
                if ($web->getIsMaster()) $kindheader = 'admin_token';
                else $kindheader = 'acces_token';
                $web->falseHeaders($kindheader);
            }
            $data = $web->apiReq("User", $POST);
            if ($data['status']['code'] == 200 ) {
                session_unset();
                session_destroy();
                $web->redirect($currentPage);
            } else {
                $_SESSION['errorlogin'] = $data['status'];
                $web->redirect("Errors");
            }
        } else if (!$islogged && in_array($web->getCurrentMod(),$web->getRestrictedModules())) {
            $web->redirect('/');
        } else {
            echo $web->render('Web');
        }
    } else {
        echo "ERROR";
        //$web->redirect("Errors");
    }
?>