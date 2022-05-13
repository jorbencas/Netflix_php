<?php
    function Header_run($web, $params) {
        $v['currentPage'] = $web->getcurrentPage();
        $v['isMaster'] = $web->getIsMaster();
        $v['islogged'] = $web->getIsLogged();
        $v['menu'] = "<ul class='list'>
            <li class='list_element " . link_active("Home, Buscador", $v['currentPage']) . "'><a class='link' href='" . $web->hrefMake('Home') . "'>
                    <i class='fas fa-home'></i>
                    <span class='texto'>" . $web->translate('Header', 'home') . "</span>
                </a></li>
            <li class='list_element " . link_active("Anime, EpisodesDetails, EndingsDetails, OpeningsDetails", $v['currentPage']) . "'><a class='link' href='" . $web->hrefMake('Anime') . "'>
                    <i class='fas fa-list-ul'></i>
                    <span class='texto'>" . $web->translate('Header', 'list_anime') . "</span>";
        if ($v['islogged']) $v['menu'] .= " <span class='badge movil_disabled'>3</span> ";
        $v['menu'] .= "</a></li>
                <li class='list_element " . link_active('Blog, Entradas', $v['currentPage']) . "'><a class='link' href='" . $web->hrefMake('Blog') . "'>
                    <i class='fas fa-blog'></i>
                    <span class='texto'>" . $web->translate('Header', 'blog') . "</span>";
        if ($v['islogged']) $v['menu'] .= " <span class='badge movil_disabled'>3</span> ";
        $v['menu'] .= "</a></li>";
        if (!$v['islogged']) {
            $v['menu'] .= "<li class='list_element movil_disabled " . link_active("singin, signup", $v['currentPage']) . "'><a class='link' href='" . $web->hrefMake('singin') . "'>
                <i class='far fa-user-circle'></i>
                <span class='texto'>" . $web->translate('Header', 'iniciar_sesion') ."</span>
                </a></li>";
        }
        $v['menu'] .= "</a></li>";
        if ($web->getisSelectedProfile()) {
            $profile['action'] = 'get_profile';
            $profile['username'] = $web->getUserConfig()['user']['username'];
            $profile['profile'] = $web->getUserConfig()['profile']['profile'];
            $data = $web->apiReq("Profiles", $profile); 
            if ($data['status']['code'] == 200) {
                $v['avatar'] = $data['data'][0]['src'];
                $v['profile'] = $data['data'][0]['nombre'];
                $data = $web->apiReq("cart&aq=getnumproducts", array('username' => $v['usuario']));
                $v['number_products'] = $data['status']['code'] == 200 ? (int) $data['data'] : 0;
            } else {
                $v['profile'] = "profile";
                $v['avatar'] = $web->handleMedia('img','no','jpg');
                $v['number_products'] = 0;
            }
            $v['usuario'] = $profile['username'];
            $v['menu'] .= "
                <li class='list_element movil_disabled " . link_active("User", $v['currentPage']) . "'><a class='link user_item' href='" . $web->hrefMake('User') . "'>
                        <img src='{$v['avatar']}' alt='{$v['profile']}'>
                        <span class='texto'> {$v['profile']} </span>
                    </a></li>
                    <li class='list_element movil_disabled'>
                <form class='link logout' method='POST' action='" . $web->hrefMake($_SERVER["REQUEST_URI"], false) . "' >
                    <input type='hidden' name='username' value='{$v['usuario']}'>
                    <input type='hidden' name='action' value='logout'>
                    <button type='submit' class='button'> 
                        <i class='fas fa-sign-out-alt'></i> <span class='texto'>" . $web->translate('Header', 'salir') . "</span>
                    </button>
                </form></li> 
                <li class='list_element movil_disabled " . link_active("Cart", $v['currentPage']) . "'><a class='link' href='" . $web->hrefMake('Cart') . "'>
                        <span class='badge movil_disabled'> " . $v['number_products'] . "</span>
                        <i class='fas fa-shopping-cart'></i></a>
                </li>
                ";
        } else if(!$v['isMaster'] && $web->getDevelop() && $web->getCurrentMod() !== 'EpisodesDetails'){
            $v['testModals'] = array(
                "type" => "episodes",
                "name" => '01',
                'ext' => "mp4",
                "siglas" => 'MKNRM',
                'lang' => "ja"
            );

            // $v['testModals'] = array(
            //     "type" => "episodes",
            //     'name' => '136',
            //      'ext' => "mp4",
            //      'siglas' => 'nuevos/Naruto'
            // );

            // $v['testModals'] = array(
            //     'type' => 'episodes',
            //     "name" => '56',
            //     "ext" => 'mp4',
            //     "siglas" => 'SAO'
            // );
        }

        $data = $web->apiReq("Episodes&aq=getidrand");
        if($data['status']['code'] == 200) {
            $url = $data['data']['id'];
            if ($data['data']['kind'] == 'serie') {
                $url .= "&kind={$data['data']['kind']}";
            }
        }else{
            $url = 0;
        }
        $v['menu'] .= "<li class='list_element " . link_active("aleatory", $v['currentPage']) . "'><a class='link' href='" . $web->hrefMake("aleatory&id=$url") . "'>
                <i class='fas fa-random'></i>
                <span class='texto'>Aleatorio</span>
            </a></li>";
        if (!in_array($v['currentPage'],array("Edit", "Buscador"))) {
            $v['menu'] .= $web->render('Buscador');
        }
        $v['menu'] .= "<li class='list_element'><div class='link' onclick='toogleLangs()'>
            <i class='fas fa-language'></i>
            <span class='texto'>" . $web->translate('Header', 'langs') ."</span>
        </div></li>
        </ul>";

        $data = $web->apiReq("Langs",array(
            "action" => "getTittleLangs", 
            "translations" => [array("kind" => "langs")]));
        if ($data['status']['code'] == 200) {
            $v['langs'] = "";
            foreach ($data['data'] as $lang) {
                $code = $lang['code'];
                $class = $web->getLang() == $code ? "active" : "";
                $href = "?r=$code/{$v['currentPage']}";
                if ($href !== $_SERVER["REQUEST_URI"] && strstr($_SERVER["REQUEST_URI"],"?r=")) {
                    $href = str_replace($web->getLang(), $code, $_SERVER["REQUEST_URI"]);
                }
                $v['langs'] .= "<li class='list_element $class'><a class='link' href=".$web->hrefMake($href, false) .">{$lang['translation']}</a> </li>";
            }
        }

        if (!in_array($v['currentPage'],array("Edit", 'signup', 'singin', "ComingSoon", "Apidoc", "Filesystem", 
        "Events", "Blog", "Cart", "Entradas", 'Profiles', "User", 'Showerrors', 'Backup', 'Buscador'))) {
            $data = $web->apiReq('Filters', array("action" => 'getFilters'));
            if ($data['status']['code'] == 200) {
                $v['letters'] = $data['data']['letters'];
                $v['years'] = $data['data']['years'];
                $v['generes'] = $data['data']['generes'];
                $v['languajes'] = $data['data']['lang'];
                $v['kinds'] = $data['data']['kind'];
                $v['temporadas'] = $data['data']['temporadas'];
                $v['filters'] = [
                    array('title' => 'genere', 'id' => 'g'),
                    array('title' => 'type',  'id' => 'k'),
                    array('title' => 'year', 'id' => 'y'),
                    array('title' => 'lang', 'id' => 'i'),
                    array('title' => 'temporada', 'id' => 't')
                ];
                if ($web->getDevelop()) {
                    $web->setDebug($data);
                }
            }
        }
        Header_assets($web,$v['currentPage']);
        $v["web"] = $web;
        return $v;
    }

    function Header_assets($web,$params){
        $web->setmodAssets(array("assets" => 'components/Header/assets/css/Langs'));
        if (!in_array($params,array("Edit", 'signup', 'singin', "ComingSoon", "Apidoc", "Filesystem", 
        "Events", "Blog", "Cart", "Entradas", 'Profiles', "User", 'Showerrors', 'Backup', 'Buscador'))) {
            $web->setmodAssets(array("assets" => 'components/Header/assets/css/Filters'));
            $web->setmodAssets(array("assets" => 'components/Header/assets/js/Filters'));
        }

        if($web->getisSelectedProfile() || $web->getDevelop() 
        && $web->getCurrentMod() !== 'EpisodesDetails'){
            $web->setmodAssets(array("assets" => 'components/Header/assets/css/lateralbar'));
            $web->setmodAssets(array("assets" => 'components/Header/assets/js/lateralbar'));
        }
    }

    function link_active($mod, $currentPage) {
        return strstr($mod,$currentPage) ? 'active' : '';
    }
?>