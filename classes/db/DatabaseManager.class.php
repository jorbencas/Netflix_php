<?php
namespace Moolty\DB;

include_once(__DIR__ . "/DatabaseError.class.php");
include_once(__DIR__ . "/../util/Server.class.php");
include_once(__DIR__ . "/Query.class.php");
include_once(__DIR__ . "/Table.class.php");
include_once(__DIR__ . '/DatabaseConnection.class.php');
include_once(__DIR__ . '/DatabaseType.class.php');
include_once(__DIR__ . "/../Application.class.php");
use \Moolty\Config;

/**
 * Representa una conexion a una base de datos y
 * permite ejecutar sentencias SQL
 * 
 * @author Matias Zublena
 * @version 0.3
 */
class DatabaseManager{
        const CONFIG_FILE="DB.ini";
        	
	/**
	 * Instancia del objecto PDO
	 * @var $db PDO
	 */
	protected $db;

	/**
	 * SCHEMA
	 */
	public $schema;
	
	/**
	 * Sentencia SQL preparada para ejecutarse
	 * @var PDOStatement
	 */
	private $statement;
	
	/**
	 * Tipo de base de datos
	 * DB_MYSQL
	 * @var string
	 */
	protected $type;

        /**
         * Contiene la configuracion de la DB
         * @var Array
         */
        public $config;

         /**
	 * Contiene la configuracion antigua de la DB
         * @var Array
	 */
        public static $lastConfig;
        
        /**
         * Habilita el uso o no de cache;
         * @var boolean
         */
        private $useCache=true;

	/**
	 * Conecta con la base de datos
	 * $config debe ser una array asociativo con las siguientes claves
	 * type=string Tipo de base de datos ej: mysql
	 * host=string|opcional hostname de la base de datos: ej: db.company.com o direccion ip. Si no se especifica, debe utilizarse socket.
	 * socket=string|opcional Si se especifica utiliza el socket de unix para conectarse y no el host. Sino se especifica debe utilizarse el host
	 * database=string Nombre de la base de datos
	 * user=string Usuario de la base de datos
	 * password=string Password de la base de datos
	 * 
	 * @param Array $config Configuarcion de la base de datos
	 * @param string $type Tipo de base de datos
	 */
	public function __construct($config=null, $type=DatabaseType::MYSQL){
		$this->type=$type;
		if(!isset($config)){
			$config=DatabaseManager::getConfig();
		}
		$this->connect($config);
                $this->config=$config;
		//$this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$this->db->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT );
	}
	
	/**
	 * Obtiene la configuracion de la base de datos de DB.config.
	 * Si no la encuentra en esas ubicaciones utiliza el archivo de configuracion por defecto dentro 
	 * de la misma carpeta de la clase
	 * El archivo de configuracion debe tener los datos especificados en la documentacion del constructor
	 * 
	 * @return Array
	 */
	private static function getConfig(){
                include_once(dirname(__FILE__) . "/../config/Config.class.php");

                $configPath=\Moolty\Application::getConfigPath();
                if(!is_null($configPath)){
                    $configFile=$configPath . DatabaseManager::CONFIG_FILE;
                }
                if(is_null($configPath)||!file_exists($configFile)){
                    $configFile=__DIR__ . \DIRECTORY_SEPARATOR . DatabaseManager::CONFIG_FILE;
                }

		if(file_exists($configFile)){
			$config=Config::get($configFile);
		}else throw new DatabaseError("DB ini configuration file not founded at $configFile");
		return $config;
	}

	
	/**
	 * Conecta a la base de datos segun los datos $config (ver detalle en la documentacion del constructor)
	 * @param Array $config
	 */
	private function connect($config)
        {
            if (DatabaseManager::$lastConfig == null)
            {
                DatabaseManager::$lastConfig = $config;
            }

            $isHost = true;
            $options = Array();
            $force = false;
            switch ($config["type"])
            {
                case DatabaseType::MYSQL:
                    if (array_key_exists("socket", $config))
                    {
                        $isHost = false;
                    }
                    $options[\PDO::ATTR_PERSISTENT] = true;

                    break;
            }

            if (DatabaseManager::$lastConfig['database'] != $config["database"])
            {
                DatabaseManager::$lastConfig = $config;
                $force = true;
            }
            
            $dsn = $config["type"];
            if ($isHost)
            {
                $dsn.=":host=" . $config["host"];
            } else
            {
                $dsn.=":unix_socket=" . $config["socket"];
            }
            $dsn.=";dbname=" . $config["database"];
            $this->schema = $config["database"];
            try
            {
                $this->db = DatabaseConnection::getConnection($dsn, $config["user"], $config["password"], $options, $force);
            } catch (\PDOException $e)
            {
                throw new DatabaseError("Can't connect to database. DSN: $dsn");
            }
        }

	/**
	 * Inicia una transaccion. Una vez iniciada se pueden ejecutar distintas sentencias SQL
	 * que al finalizar la transaccion se ejecutaran todas como una sola unidad, pudiendo volver 
	 * atras si hay algun error en alguna transaccion.
	 * 
	 * ATENCION: Solo funciona con base de datos que soporten transacciones. 
	 * Por ejemplo, no funciona con bases de datos MYSAM en MYSQL
	 * 
	 */
	public static function beginTransaction(){
        $dbManager = new DatabaseManager();
        $dbManager->db->beginTransaction();
	}
	
	/**
	 * Finaliza la transaccion y envia las sentencias SQL para que se ejecuten.
	 */
	public static function commitTransaction(){
        $dbManager = new DatabaseManager();
        $dbManager->db->commit();
	}
	
	/**
	 * Si falla una transaccion puede utilizarse este metodo para que vuelva 
	 * atras todos los cambios que se hayan hecho dentro de la transaccion
	 * en la base de datos previos a la sentencia que fallo
	 */
	public static function rollbackTransaction(){
            $dbManager = new DatabaseManager();
            $dbManager->db->rollBack();
	}
	
	/**
	 * Ejecuta un query directamente
	 * Hay que tener en cuenta que no escapa la sentencia SQL
	 * 
	 * @param string $sql
	 */
	public function query($sql){
		if($sql instanceof Query)$sql=(string)$sql;
		$row=$this->db->query($sql);
		/*
		 * @todo arreglar esto
		 */
		if($this->db->errorCode()!='00000'){
			$dbError=$this->db->errorInfo();
			throw new DatabaseError($dbError[2] . ". [SQL: " . $sql . "] [SQLSTATE: " . $dbError[0] . "] [Error Code: " . $dbError[1] . "]");
		}
		return $row;
		
	}

	public function simpleQuery($sql, $params = array()){
		if(!is_null($params)&&!is_array($params))$params=(array)$params;
                if($this->type === DatabaseType::MYSQL)$this->db->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
                $separateStatement=false;
		if($sql instanceof Query){
                    switch($sql->type){
                        case Query::DELETE:
                        case Query::UPDATE:
                        case Query::INSERT:
                            $separateStatement=true;
                            break;
                    }
                    $sql=(string)$sql;
                }
                if($separateStatement){
                    $statement=$this->db->prepare($sql);
                    
                    $this->internalExecute($statement, $params);
                    $rowCount=$statement->rowCount();
                }else{
		$this->executeQuery($sql, $params);
		$rowCount=$this->statement->rowCount();
                }
		$this->db->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
		return $rowCount;
	}
	
	public function setQuery($sql){
		if($sql instanceof Query)$sql=(string)$sql;
		$this->statement=$this->db->prepare($sql);
	}
	
	public function execute($params=null){
		$this->checkStatement();
                $this->internalExecute($this->statement, $params);
			}
        
    private function internalExecute(\PDOStatement $statement, $params=null){
    	$debug = true;
        if(!is_null($params)&&!is_array($params))$params=(array)$params;
        
        if($statement->execute($params))
        {
            $dbError=$statement->errorInfo();
			if(isset($statement->errorCode) && $statement->errorCode!='00000'){
				throw new DatabaseError($dbError[2] . ". [SQL: " . $statement->queryString . "] [SQLSTATE: " . $dbError[0] . "] [Error Code: " . $dbError[1] . "]");
			}
        }elseif($debug)
        {
            error_log("---- ERROR IN DB MANAGER ---------------------");
            $traces = debug_backtrace();
            foreach ($traces as $trace) {
            	error_log($trace['file']." :: ".$trace['line']);
            }
            error_log(print_r($params,1));
        }
    }
	
	public function executeQuery($sql, $params=Array()){
		if($sql instanceof Query)$sql=(string)$sql;
		if($params instanceof stdClass)$params=(array)$params;
		$this->setQuery($sql);
		$this->execute($params);
	}
	
	private function checkStatement(){
		if(!$this->statement instanceof \PDOStatement)throw new DatabaseError("You must set the query first. Use setQuery().");
	}
	
	
	public function getResult($resultType=null){
		//$this->checkStatement();
		return $this->statement->fetch($resultType);
	}
	
	public function getResultAsObject($className="stdClass", $constructorArguments=array()){
		//$this->checkStatement();
		return $this->statement->fetchObject($className, $constructorArguments); 
	}
	
	public function getResultAsArray(){
		return $this->getResult(\PDO::FETCH_ASSOC);
	}
	
	public function getAll($sql, $resultType=null, $className="stdClass"){
			$this->setQuery($sql);
                        $this->execute();
			if($resultType==\PDO::FETCH_CLASS) return $this->statement->fetchAll($resultType, $className);
			else return $this->statement->fetchAll($resultType);
	}
	
	public function getAllAsArray($sql){
		return $this->getAll($sql, \PDO::FETCH_ASSOC);
	}
	
	public function getAllAsObjects($sql, $className="stdClass"){
		return $this->getAll($sql, \PDO::FETCH_CLASS, $className);
	}

	public function getLastId(){
		return $this->db->lastInsertId();
	}
	
	public function bind($paramName, &$variable, $dataType=\PDO::PARAM_STR, $length=null){
		//$this->checkStatement();
		if(!$this->statement->bindParam($paramName, $variable, $dataType, $length)){
			throw new DatabaseError("Can't bind $paramName.");
		}
	}
	
	public function bindFields($fields=null){
		if(!is_null($fields)&&is_array($fields)){
			foreach($fields as $field=>$value){
				$this->bind($field, $value);
			}
		}
	}


        public function getTables(){
            $databaseType=strtoupper($this->type) . "DatabaseType";
            include_once("types/$databaseType.class.php");
            $tables=call_user_func($databaseType . "::getTables", $this);
            $tablesArray=array();
            foreach($tables as $table){
                $tablesArray[]=array_pop($table);
            }
            return $tablesArray;
        }


	/**
	 * Obtiene la estructura de la tabla como asi tambien sus relaciones a otras tablas.
	 * Primero intenta obtenerla desde el cache y si no lo consigue, hace la consulta en la base de datos
	 */
	public function getTableStructure($tableName){
            $databaseType=strtoupper($this->type) . "Adaptor";
            include_once(__DIR__ . "/adapters/$databaseType.class.php");
            $databaseType= __NAMESPACE__ . "\\$databaseType";
            $table=$databaseType::getTableStructure($tableName, $this);
            return $table;
	}

	/**
	 * Almacena la estructura de la tabla y las relaciones
	 * en la cache.
	 */
	/*private static function saveTableStructureToCache(Table $table){
			$cache=new Cache(Cache::APC);
			$cache->set("db_table_" . $table->name, $table);
	}*/

	/**
	 * Obtiene la estructura de la tabla y las relaciones
	 * desde el cache.
	 */
	/*private static function getTableStructureFromCache($tableName){
		$cache=new Cache(Cache::APC);
		return $cache->get("db_table_" . $tableName);
	}*/ 		
	

	public function __destruct(){
		$this->db=null;
	}
	

	
	
}
