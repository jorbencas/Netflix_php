<?php
namespace Moolty\DB;
include_once(__DIR__ . "/Column.class.php");
include_once(__DIR__ . "/DatabaseManager.class.php");
include_once(__DIR__ . "/Query.class.php");
include_once(__DIR__ . "/../cache/Cache.class.php");

/**
 * Esta clase representa una tabla de la base de datos
 *  
 * @author Matias Zublena
 * @version 0.2
 * @todo agregar funcionalidad para multiple primary keys
 */
class Table {


        const TYPE_TABLE=0;
        const TYPE_VIEW=1;
	/**
	 * Instancia de DBManager para poder realizar las consultas de la tabla
	 * @var DBManager
	 */
	public $db;
	
	/**
	 * Nombre de la tabla
	 * @var string
	 */
	public $name;
	
	/**
	 * Columnas de la tabla 
	 * Array de Column
	 * @var Array
	 */
	public $columns;
	
	/**
	 * Clave primaria de la tabla
	 * @var string
	 */
	public $primaryKey;
	
	/**
	 * Array de relaciones de la tabla
	 * @var array
	 */
	public $relations;
	
        /**
         * Array de relaciones inversas de la tabla
         * @var array;
         */
         public $inverseRelations;
        
	/**
	 * Objeto que contiene los datos de la tabla
	 */
	public $data;

        /**
         * Tipo de tabla: normal o vista
         */

        public $type;
	
	/**
	 * Al inicializar este objeto, se recupera la estructura de la tabla (columnas, relaciones, etc)
	 * @param $tableName
	 */
	function __construct($tableName=null) {
		$this->data=new \stdClass();
		$this->columns=Array();
		$this->relations=Array();
                $this->inverseRelations=Array();
		if(!is_null($tableName))$this->name=$tableName;
	}

	/**
	 * Devuelve el nombre de la tabla
	 * @return $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Devuelve las columnas de la tabla
	 * @return $columns
	 */
	public function getColumns() {
		return $this->columns;
	}
        
        public function getColumnNames(){
            return array_keys($this->columns);
        }

	/**
	 * Establece el nombre de la tabla
	 * @param string $name nombre de la tabla a establecer
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Establece las columnas de la tabla
	 * @param array $columns Columnas a establecer
	 */
	public function setColumns($columns) {
		$this->columns = $columns;
	}
	
	/**
	 * Agrega una columna a las columnas de la tabla
	 * @param Column $column Columna a agregar
	 */
	public function addColumn(Column  $column){
		$this->columns[$column->name]=$column;
	}

}

?>