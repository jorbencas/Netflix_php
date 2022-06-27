<?php
require_once __DIR__ . '/validators.php';
require_once __DIR__ . '/config.php';
class Utils extends Config
{
    use Validators;
    private $currentPage;
    private $lang;
    private $GET;
    private $POST;
    private $headers;
    private $headersCurl;

    public function __construct()
    {
        parent::__construct();
        $this->GET = $this->getrequest();
        $req = $this->getGET();
        $this->headers = apache_request_headers();
        $headers = $this->getHeaders();
        $apiToken = isset($headers['api_token']) ? 'api_token:' . $headers['api_token'] : 'api_token:' . $this->getApiToken();
        $accesToken = isset($headers['acces_token']) ? 'acces_token:' . $headers['acces_token'] : null;
        $adminToken = isset($headers['admin_token']) ? 'admin_token:' . $headers['admin_token'] : null;
        // if (isset($adminToken)) {
        //     $adminToken = str_replace(' ','X', str_pad('u'.$adminToken, 32));//remplazar espacios por X
        // }
        $this->headersCurl = [
            'Content-Type: application/json',
            'charset: utf-8',
            $apiToken,
            $accesToken,
            $adminToken
        ];
        $this->lang = $this->urlLang($req);
        $this->currentPage = $this->getpage($req);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') $this->POST = $this->postrequest();
    }

    public function getcurrentPage()
    {
        return $this->currentPage;
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    public function getGET()
    {
        return $this->GET;
    }

    public function getPOST()
    {
        return $this->POST;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    public function getHeadersCurl()
    {
        return $this->headersCurl;
    }

    public function falseHeaders($headerName)
    {
        $headers = $this->getHeaders();
        if (!isset($headers[$headerName])) {
            switch ($headerName) {
                case 'api_token':
                    $headers[$headerName] = $this->getApiToken();
                    break;
                case 'acces_token':
                case 'admin_token':
                    $headers[$headerName] = $_SESSION['auth'][$headerName];
                    break;
            }
            $this->setHeaders($headers);
        }
    }

    private function getrequest()
    {
        if (isset($_GET) && isset($_GET['r']) /*&& !strstr($_GET['r'],"api")*/) {
            $nuevoGET = array();
            foreach ($_GET as $k => $valor) {
                $valor = str_replace('SELECT', '', $valor);
                $valor = str_replace('CREATE', '', $valor);
                $valor = str_replace('UPDATE', '', $valor);
                $valor = str_replace('INSERT', '', $valor);
                $valor = str_replace('DELETE', '', $valor);
                $valor = str_replace('FROM', '', $valor);
                $valor = str_replace('WHERE', '', $valor);
                $valor = str_replace('SET', '', $valor);
                $valor = str_replace('ALTER', '', $valor);
                $valor = str_replace('EXECUTE', '', $valor);
                $valor = str_replace('SLEEP', '', $valor);
                $valor = str_replace('COPY', '', $valor);
                $valor = str_replace('SELECT', '', $valor);
                $valor = str_replace("DUMP", "", $valor);
                $valor = str_replace(" OR ", "", $valor);
                $valor = str_replace("%", "",    $valor);
                $valor = str_replace("LIKE", "", $valor);
                $valor = str_replace("--", " ",     $valor);
                $valor = str_replace("^", "",       $valor);
                $valor = str_replace("[", "",       $valor);
                $valor = str_replace("]", "",       $valor);
                $valor = str_replace("(", "",       $valor);
                $valor = str_replace(")", "",       $valor);
                $valor = str_replace("\\", "",      $valor);
                $valor = str_replace("!", "",       $valor);
                $valor = str_replace("¡", "",       $valor);
                //$valor = str_replace("?","",       $valor);
                //$valor = str_replace("=","",       $valor);
                $valor = str_replace("\"", " ",     $valor);
                $valor = str_replace("'", " ",      $valor);
                $valor = str_replace('\'', " ",      $valor);
                $valor = str_replace('%20', "_",     $valor);
                // $valor = str_replace("&","",       $valor);
                $nuevoGET[$k] = trim($valor);
            }
            return $nuevoGET;
        } else {
            return $_GET;
        }
    }

    private function postrequest()
    {
        $POST = isset($_POST) && count($_POST) > 0 ? $_POST : json_decode(file_get_contents('php://input'), true);
        // if (count($_POST) > 0) {
        //     $nuevoPOST = Array();
        //     foreach($POST as $key=>$valor) {
        //         $valor = preg_replace('([^A-Za-z0-9])', '', $valor); // faltaria agregar - _ /
        //         // Para todo el mundo, no se permite:
        //             if (!isset($this->getHeaders()['admin_token'])) {
        //                 $valor = str_replace("^","",       $valor);
        //                 $valor = str_replace("\\","",      $valor);
        //                 $valor = str_replace("%","",       $valor);
        //                 $valor = str_replace("[","",       $valor);
        //                 $valor = str_replace("]","",       $valor);
        //                 $valor = str_replace("!","",       $valor);
        //                 $valor = str_replace("¡","",       $valor);
        //                 $valor = str_replace("?","",       $valor);
        //                 $valor = str_replace("=","",       $valor);
        //                 $valor = str_replace("\""," ",     $valor);
        //                 $valor = str_replace("'"," ",      $valor);
        //                 $valor = str_replace('\''," ",      $valor);
        //                 $valor = str_replace("&","",       $valor);
        //             } else {
        //                 $valor = str_replace("SLEEP","",   $valor);
        //                 $valor = str_replace("SELECT","",  $valor);
        //                 $valor = str_replace("COPY","",    $valor);
        //                 $valor = str_replace("DELETE","",  $valor);
        //                 $valor = str_replace("DROP","",    $valor);
        //                 $valor = str_replace("DUMP","",    $valor);
        //                 $valor = str_replace(" OR ","",    $valor);
        //                 $valor = str_replace("LIKE","",    $valor);
        //                 $valor = str_replace("--"," ",     $valor);
        //                 $valor = str_replace('%27'," ",     $valor); // '
        //             }
        //         // filtrar también el key?
        //         $key = preg_replace('([^A-Za-z0-9]\-\_\/\??)', '', $key); // faltaria agregar -   
        //         $nuevoPOST[$key] = $valor;
        //     }
        //     return $nuevoPOST;
        // } else {
        //     return $POST;
        // }
        return $POST;
    }

    private function getpage($req)
    {
        $page = $this->getDefaultPage();
        if (isset($req['r']) && !empty($req['r'])) {
            $getpath = explode('/', $req['r']);
            if (count($getpath) == 2) {
                $modules = $this->getValidModules();
                $urlpage = $getpath[1];
                if ($urlpage != $this->getLang() && in_array($urlpage, $modules)) {
                    $page = $urlpage;
                } else {
                    $urlpage = reset($getpath);
                    if ($urlpage != $this->getLang() && in_array($urlpage, $modules)) {
                        $page = $urlpage;
                    } else {
                        $page = 'Errors';
                    }
                }
            }
        }
        return $page;
    }

    private function urlLang($req)
    {
        $lang = $this->getDefaultLang();
        if (isset($req['r']) && !empty($req['r'])) {
            if ($this->isAjax()) {
                $this->setValidModules($this->getAjaxModules());
            }
            $getpath = explode('/', $req['r']);
            $urlang = reset($getpath);
            if (in_array($urlang, $this->getValidModules()) && isset($getpath[1])) {
                $urlang = $getpath[1];
            }
            $data = $this->apiReq("Langs", array("action" => 'getcodelangs'));
            if ($data['status']['code'] == 200) {
                $valids = array();
                foreach ($data['data'] as $l) {
                    if ($urlang == $l['code']) {
                        $this->setLang($l['code']);
                    }
                    array_push($valids, $l['code']);
                }
            } else {
                $valids = array("es", "en", "va", "ca");
                foreach ($valids as $key => $l) {
                    if ($urlang == ($key + 1)) {
                        $this->setLang(($l));
                    }
                }
            }
            if (in_array($urlang, $valids)) {
                $lang = $urlang;
            }
        }
        return $lang;
    }

    public function handleMedia($tipo, $name, $ext, $id_anime = null, $id_element = null)
    {
        $mediapath = null;
        $nomedia = $this->getNomediaImg();
        switch ($tipo) {
            case 'musica':
            case 'notifications':
            case 'img':
            case 'libs':
                $mediaSrc = "public/$tipo/{$name}.$ext";
                break;
            case 'fontawesome':
                $mediaSrc = "public/libs/$tipo/css/{$name}.$ext";
                break;
            case 'assets':
                $mediaSrc = "public/{$name}.$ext";
                break;
            default:
                $mediaSrc = $nomedia;
                break;
        }

        if (file_exists($mediaSrc)) {
            $mediapath = $mediaSrc;
        } else if ($this->isImage($ext)) {
            $mediapath = $nomedia;
        } else if ($this->isMusic($ext)) {
            $mediapath = 'public/notifications/error.mp3';
        } else {
            var_dump($mediaSrc);
        }

        if ($this->isAjax()) {
            $mediapath = "http://{$this->getDomain()}/".$mediapath;
        }

        return $mediapath;
    }

    public function tt($file, $string)
    {
        if (file_exists($file)) {
            $lang_json = json_decode(file_get_contents($file));
            $trans = isset($lang_json->$string) ? $lang_json->$string : $string;
        } else {
            $trans = $string;
        }
        return $trans;
    }

    public function apiReq($peticion, $params = null)
    {
        $date = new DateTime();
        $peticion .= "&rand={$date->getTimestamp()}";
        return $this->curl("{$this->getUrlApi()}{$this->getLang()}&am=$peticion", $params);
    }

    private function curl($url, $params)
    {
        $ch = curl_init($url);
        if (isset($params)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeadersCurl());
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $res = curl_exec($ch);
        if ($curl_error = curl_error($ch)) {
            $result = array(
                'data' => null,
                'status' => array(
                    'code' => 500,
                    'text' => 'Internal Server Error',
                    'message' => 'error',
                    'http' => array(
                        'url_error' => $curl_error,
                        'code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
                        'content_type' => curl_getinfo($ch, CURLINFO_CONTENT_TYPE)
                    )
                )
            );
        } else {
            $result = json_decode($res, true);
        }
        curl_close($ch);
        return $result;
    }
}
