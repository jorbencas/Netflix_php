<?php
function User_run($web, $params) {
    $v['currentPage'] = $web->getcurrentPage();
    $POST = $web->getPOST();
    if (isset($POST)) {
        $data = $web->apiReq("User", $POST);
        if ($data['status']['code'] == 200) {
            $_SESSION['auth']['username'] = $data['data']['username'];
            $_SESSION['auth']['acces_token'] = $data['data']['acces_token'];
            $_SESSION['auth']['tipo'] = $data['data']['tipo'];
            if ($web->getIsMaster()) {
                $_SESSION['auth']['admin_token'] = $data['data']['admin_token'];
            }
            $url = "/";
        } else {
            $url = $v['currentPage'];
        }
        $web->redirect($url);
    } else if ($v['currentPage'] == 'singin') {
        $v['login'] = "<h1>" . $web->translate('User', 'login_title') . "</h1>
        <form class='formulario' action='" . $web->hrefMake('User') . "' method='post' name='formulario_registro'>
            <input type='text' name='username' id='usuario' value='jorge' placeholder='Usuario' required
                pattern='[A-Za-z0-9]{4,12}' title='Usuario debe tener de 4 a 20 caracteres'>
            <div class='concret'>
                <input type='password' name='passwd' id='passwd' value='' placeholder='Contraseña'
                    autocomplate required>
                <i class='far fa-eye' onclick='togglepassword()' style='display:none;'></i>
                <i class='fas fa-eye-slash' onclick='togglepassword()'></i>
            </div>
            <a href='" . $web->hrefMake('signup') . "'>
                <span class='texto'>". $web->translate('Header', 'registro') . "</span>
            </a>
            <div class='input-group'>
                <input type='hidden' name='action' value='Login'>
                <input type='submit' class='submit' value='" . $web->translate('User', 'login_title') . "' id='btn-submit'>
            </div>
        </form>";
    } else if ($v['currentPage'] == 'signup') {
        $v['singup'] = "<h1>" . $web->translate('User', 'singup_tittle') . "</h1>
        <form class='formulario' action='" . $web->hrefMake('User') . "' method='post' name='formulario_registro'>
            <input type='text' name='username' id='usuario' value='jorge' placeholder='Usuario' required
                pattern='[A-Za-z0-9]{4,12}' title='Usuario debe tener de 4 a 20 caracteres'>
            <input type='text' name='email' id='email' required placeholder='Correo Electronico'>
            <div class='concret'>
                <input type='password' name='passwd' id='passwd' value='Karanlik123?' placeholder='Contraseña'
                    autocomplate required>
                <i class='far fa-eye' onclick='togglepassword()' style='display:none;'></i>
                <i class='fas fa-eye-slash' onclick='togglepassword()'></i>
            </div>
            <a href='" . $web->hrefMake('singin') . "'>
                <span>" . $web->translate('Header', 'iniciar_sesion') . "</span>
            </a>
            <div class='input-group'>
                <input type='hidden' name='action' value='Register'>
                <input type='submit' class='submit' value='" . $web->translate('User', 'singup_tittle') . "' id='btn-submit'>
            </div>
        </form>";
    } else {
        $v["web"] = $web;
        $data = $web->apiReq("User&ap={$web->getUserConfig()['user']['username']}");
        $auth = $data['status']['code'] == 200 ? $data['data'] : null;
        $v['usuario'] = $auth['user'];
        $v['nombre'] = $auth['nombre'];
        $v['apellidos'] = $auth['apellidos'];
        $v['email'] = $auth['email'];
        $v['password'] = $auth['password'];
        $v['date_birthday'] = $auth['date_birthday'];
        $v['dni'] = $auth['dni'];
        $v['token'] = $auth['token'];
        $v['genere'] = $auth['genere'];
        $v['tipo'] = $auth['tipo'];
    }
    return $v;
}
