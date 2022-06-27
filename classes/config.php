<?php
class Config
{
    private $domain;
    private $urlApi;
    private $restrictedModules;
    private $apiToken;
    private $validModules;
    private $nomediaImg;
    private $defaultLang;
    private $defaultPage;
    private $ajaxModules;

    public function __construct()
    {
        $this->restrictedModules = array(
            "Profiles", "Edit", "Events", "Collection",
            "Cart", "Admin", "Apidoc"
        );
        $this->validModules = array(
            'signup', 'singin', 'Anime', "Buscador", 'Collection', "Entradas", "Errors",
            'aleatory', 'EpisodesDetails', 'Profiles', "User", "Apidoc", 'Filesystem',
            'Showerrors', 'Backup', "Home", "Edit", "Events", "Blog", "Cart", 'OpeningsDetails',
            'EndingsDetails'
        );
        $cnfg = parse_ini_file(__DIR__ . '/../conf/config.ini');
        $this->domain = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $cnfg['domain'];
        $this->defaultLang = $cnfg['defaultLang'];
        $this->urlApi = "http://{$cnfg['urlApi']}?r=";
        $this->apiToken = $cnfg['apiToken'];
        $this->nomediaImg = $cnfg['nomediaImg'];
        $this->defaultPage = $cnfg['defaultPage'];
        $this->ajaxModules = array('Modals', 'Grid', 'List', 'Tabla');
        define("URL_BASE", "http://{$this->getDomain()}");
    }

    public function getApiToken()
    {
        return $this->apiToken;
    }

    public function getRestrictedModules()
    {
        return $this->restrictedModules;
    }

    public function getUrlApi()
    {
        return $this->urlApi;
    }

    public function getValidModules()
    {
        return $this->validModules;
    }

    public function setValidModules($validModules)
    {
        $this->validModules = $validModules;
    }

    public function getNomediaImg()
    {
        return $this->nomediaImg;
    }

    public function getDefaultLang()
    {
        return $this->defaultLang;
    }

    public function getDefaultPage()
    {
        return $this->defaultPage;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getAjaxModules()
    {
        return $this->ajaxModules;
    }
}
