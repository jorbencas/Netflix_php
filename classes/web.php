<?php
    require_once __DIR__ . '/utils.php';
    class Web extends Utils {
        private $metadescription;
        private $keywords;
        private $modAssets;
        private $debug;
        private $userconfig;
        private $currentMod;
        private $theme;
        private $cache;
        private $develop;
        private $pageTittle;
        private $isLogged;
        private $isSelectedProfile;
        private $isMaster;
        
        public function __construct() {
            parent::__construct();
            $this->modAssets = array();
            $this->debug = array();
            $this->metadescription = 'Proyecto Cosas de Anime';
            $this->keywords = "HTML, CSS, JavaScript, Anime";
            $cnfg = parse_ini_file(__DIR__.'/../conf/config_web.ini');
            $this->theme = $cnfg['theme'];
            $this->cache = $cnfg['cache'];
            $this->develop = $cnfg['develop'];
            $this->isLogged = $this->isLogged();
            $this->isSelectedProfile = $this->isSelectedProfile();
            $this->isMaster = $this->isMaster();
            // error_log(json_encode(flush()));
            //debug_print_backtrace();
            $currentPage = $this->getcurrentPage();
            $router = array(
                'signup' => 'User', 'singin' => 'User', 'EndingsDetails' => 'EpisodesDetails', 
                'aleatory' => 'EpisodesDetails', 'Showerrors' => 'Admin', 'Backup' => 'Admin',
                'OpeningsDetails' => 'EpisodesDetails', 'Filesystem' => 'Admin', 'Entradas' => 'ComingSoon'
            );
            if($this->getDevelop()){
                $routerDevelop = array('Entradas' => 'Entradas');
                if (isset($router[$currentPage]) && $currentPage == $router[$currentPage] ) {
                    unset($router[$currentPage]);
                }
                $router = array_merge($routerDevelop, $router);
            }

            if (isset($router[$currentPage])) {
                $this->currentMod = $router[$currentPage];
            } else {
                $this->currentMod = $currentPage;
            }
        }
        
        public function getCache(){
            return $this->cache;
        }

        public function getMetadescription() {
            return $this->metadescription;
        }

        public function setMetadescription($metadescription) {
            $this->metadescription .= $metadescription;
        }

        public function getKeywords() {
            return $this->keywords;
        }

        public function setKeywords($keywords) {
            $this->keywords .= $keywords;
        }

        public function getmodAssets() {
            return $this->modAssets;
        }

        public function setmodAssets($mod) {
            $mods = $this->getmodAssets();
            if (count($mods) == 0) {
                array_push($this->modAssets, $mod);
            } else {
                $themes = array();
                $assets = array();
                $modals = array();
                foreach ($mods as $value) {
                    if (isset($value['assets'])) array_push($assets,$value['assets']);
                    if (isset($value['theme'])) array_push($themes,$value['theme']);
                    if (isset($value['modals'])) array_push($modals,$value['modals']);
                }
                if ((isset($mod['modals']) && !in_array($mod['modals'],$modals)) 
                || (isset($mod['theme']) && !in_array($mod['theme'],$themes))
                || (isset($mod['assets']) && !in_array($mod['assets'],$assets))) {
                    array_push($this->modAssets, $mod);
                }
            }
        }
        
        public function getDebug() {
            return $this->debug;
        }

        public function setDebug($debug) {
            array_push($this->debug, $debug);
        }

        public function getUserConfig() {
            return $this->userconfig;
        }

        public function setUserConfig($userconfig) {
            $this->userconfig = $userconfig;
        }

        public function getCurrentMod(){
            return $this->currentMod;
        }

        public function setCurrentMod($currentMod){
            $this->currentMod = $currentMod;
        }

        public function getTheme(){
            return $this->theme;
        }

        public function setTheme($theme){
            $this->theme = $theme;
        }

        public function getDevelop(){
            return $this->develop;
        }

        public function getPageTittle(){
            return $this->pageTittle;
        }

        public function setPageTittle($pageTittle){
            $this->pageTittle = $pageTittle;
        }

        public function getIsLogged(){
            return $this->isLogged;
        }

        public function setIsLogged($isLogged){
            $this->isLogged = $isLogged;
        }

        public function getIsSelectedProfile(){
            return $this->isSelectedProfile;
        }

        public function setIsSelectedProfile($isSelectedProfile){
            $this->isSelectedProfile = $isSelectedProfile;
        }
        
        public function getIsMaster(){
            return $this->isMaster;
        }

        public function setIsMaster($isMaster){
            $this->isMaster = $isMaster;
        }

        private function islogged() {
            if (isset($_SESSION)) {
                return isset($_SESSION['auth']['username']) ? true : false;
            } else {
                return isset($this->getHeaders()['acess_token']) ? true : false;
            }
        }

        private function isSelectedProfile() {
            if (isset($_SESSION)) {
                return $this->getIsLogged() && isset($_SESSION['auth']['profile']) ? true : false;
            } else {
                return isset($this->getHeaders()['acess_token']) ? true : false;
            }
        }

        private function isMaster() {
            if (isset($_SESSION)) {
                return isset($_SESSION['auth']['tipo']) && $_SESSION['auth']['tipo'] == 'admin' ? true : false;
            } else {
                return isset($this->getHeaders()['admin_token']) ? true : false;
            }
        }

        public function render($mod = null, $params = null) {//43
            if(isset($mod)){
                $baseDir = "components";
            } else {
                $baseDir = "pages";
                $mod = $this->getCurrentMod();
            }
            $controller = "$baseDir/$mod/$mod.php";
            if (is_dir("$baseDir/$mod") && file_exists($controller)) {
                // if ($this->getCache()) {
                //     $cache = $this->instanceClases("cache");
                //     $cached = $cache->getcache($mod);
                //     if (isset($cached)) {
                //         $render = $cached;
                //     }
                // }

                // if (!isset($render)) {
                    include_once $controller;
                    $func = "{$mod}_run";
                    $view = "$baseDir/$mod/{$mod}_view.php";
                    if (file_exists($view) && function_exists($func)) {
                        if ($mod == 'Web') {
                            $this->setmodAssets(array("assets" => "components/Web/colors/{$this->getTheme()}"));
                        } elseif ($this->isAjax() && $this->getCurrentMod() == 'Modals') {
                            if (isset($params['kind']) && isset($params['theme']) ) {
                                $this->setmodAssets(array("assets" => "components/Web/colors/{$this->getTheme()}"));
                                $this->setmodAssets(array('modals' => $params['kind']));
                            }
                            $this->setmodAssets(array('modals' => $mod));
                        }
                        $this->setmodAssets(array("assets" => "$baseDir/$mod/$mod", "theme" => "$baseDir/$mod"));
                        $v = call_user_func_array($func, array( 'web' => $this, 'params' => $params ));
                        ob_start(); # apertura de bufer
                        include $view;
                        $obj = ob_get_contents();
                        ob_end_clean(); # cierre de bufer
                        // if ($this->getCache() && in_array($mod, array("Apidoc", "ComingSoon", "Errors", "Showerrors"))) {
                        //     $cache->savecache($mod, $obj);
                        // }
                        $render = $obj;
                    } else {
                        $message = "Error al cargar el modulo  $mod, ya que no existe la función {$mod}_run";
                        $render = $this->msg($message, "error");
                    }
                // }
            } else {
                $message = "Error al cargar el modulo $mod, no existe $controller";
                $render = $this->msg($message, "error");
            }
            return $render;
        }
    
        public function translate($mod, $string) {
            $baseDir = is_dir("components/$mod") ? "components": "pages";
            $file = "$baseDir/$mod/locales/{$this->getLang()}.json";
            return $this->tt($file, $string);
        }

        public function redirect($url, $alertCode = null, $alertText = null) {
            if ($url == '/') $url = 'Home';
            if (isset($alertCode) && isset($alertText)) {
                $this->setCookie('alert',array(
                    'c' => $alertCode,
                    't' => $this->translate($url,$alertText)
                ));
            }
            die(header("Location: " . $this->hrefMake($url)));
        }
    
        public function setCookie($name, $data, $delete = false) {
            $expires =  ($delete == true) ? time() - 3600 : time() + 60*60*24*30;
            if($delete) unset($_COOKIE[$name]);
            $cookieoptions = array(
                'expires' => $expires,
                'path' => '/',
                'domain' => $this->getDomain(), // leading dot for compatibility or use subdomain
                'secure' => false, // or false
                'httponly' => false, // or false
                'samesite' => 'Strict' // None || Lax  || Strict
            );
            setcookie($name, $data, $cookieoptions);
        }
        
        public function getCookie($name){
            if (isset($_COOKIE[$name])) {
                return $_COOKIE[$name];
            } else {
                return null;
            }
        }
        
        public function msg($txt,$tipo="info") {
            $mensajes_array = array();
            $key = "msg_".md5($txt);  // $key para evitar duplicados
            $mensajes = "";
            
            switch ($tipo) {
                case 'warning':
                    $mensajes_array[$key] = array('type' => 'warning', 'msg' => $txt);
                    break;
                case 'success':
                    $mensajes_array[$key] = array('type' => 'success', 'msg' => $txt);
                    break;
                case 'error':
                    $mensajes_array[$key] = array('type' => 'danger', 'msg' => $txt);
                    break;
                case 'info':                
                default: // info
                    $mensajes_array[$key] = array('type' => 'info', 'msg' => $txt);
                    break;
            }
    
            foreach ($mensajes_array as $m) {
                //$btn_remove = $m['btn_remove'] ? "<span class='delete' onclick='closemsg(event.target)'>&times;</span>" : "";
                $mensajes .= "  <div class='notification {$m['type']} timeout'>
                                    {$m['msg']}
                                </div>";
            }
            return "<div class='mensajes'>$mensajes</div>";
        }
    
        public function renderAssets(){
            $v['css'] = '';
            $v['js'] = '';
            $theme = $this->getTheme();
            $version = '0.2';
            $modals = array();
            foreach ($this->getmodAssets() as $assets) {
                if (isset($assets["assets"]) && file_exists("{$assets["assets"]}.css")) {
                    $v['css'] .= "<link rel='stylesheet' href='{$assets["assets"]}.css?v={$version}'/>";
                }
                if (isset($assets['theme']) && file_exists("{$assets['theme']}/themes/$theme.css")) {
                    $v['css'] .= "<link rel='stylesheet' href='{$assets['theme']}/themes/$theme.css?v={$version}'>";
                }
                if (isset($assets["assets"]) && file_exists("{$assets["assets"]}.js")){
                    $v['js'] .= "<script src='{$assets["assets"]}.js?v={$version}'></script>";
                }

                //Parche para eliminar por javascript los assets cargados al cerrar el modal
                if (isset($assets['modals'])) {
                    array_push($modals,$assets['modals']);
                }
            }

            //Parche para eliminar por javascript los assets cargados al cerrar el modal
            if (count($modals) > 0) {
                $v['js'] .= "<script id='modalsAssets'> var modalsAssets = JSON.stringify(". json_encode($modals, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) .") </script>";
            }
            return $v;
        }

        public function snedDataModal($options, $d = null){
            return json_encode(array(
                "kind" => $options,
                'theme' => $this->getTheme(),
                "data" => $d
            ));
        }

        public function hrefMake($ruta, $withparams = true) {
            //$ruta = str_replace(" ", "\\", $ruta);
            //if (file_exists($asset)) unlink($asset);
            // $amigableson = true;
            // $link = "";
            // if ($amigableson) {
            //     $url = explode("&", str_replace("?", "", $ruta));
            //     foreach ($url as $value) {
            //         $aux = explode("=", $value);
            //         $link .=  $aux[1]."/";
            //     }
            // $ruta = $link;
            // }
            if ($withparams) {
                return "http://{$this->getDomain()}/?r={$this->getLang()}/$ruta";
            } else {
                return "http://{$this->getDomain()}/$ruta";
            }
        }
    }
?>