<?php
namespace Moolty\DB;
include_once(__DIR__ . "/DatabaseManager.class.php");
include_once(__DIR__ . "/Condition.class.php");
include_once(__DIR__ . "/Conditions.class.php");
include_once(__DIR__ . "/../cache/Cache.class.php");
include_once(__DIR__ . "/QueryError.class.php");
include_once(__DIR__ . "/../util/Array.php");


class Query{
	/**
	 * Tipos de consultas
	 */
	const SELECT="SELECT";
	const INSERT="INSERT INTO";
	const UPDATE="UPDATE";
	const DELETE="DELETE FROM";

	/**
	 * Tipos de JOIN
	 */
	
	const JOIN_INNER=1;
	const JOIN_LEFT_OUTER=2;
	const JOIN_LEFT_INNER=3;
	const JOIN_RIGHT_OUTER=4;
	const JOIN_RIGHT_INNER=5;


	/**
	 * Tipo de query (SELECT|INSERT|UPDATE|DELETE)
	 * @var $type string
	 */
	public $type;
	
	
	/**
	 * Tipo de Base de datos
	 * @var $dbType string
	 */
	public $dbType;
	
	
	/**
	 * Nombre de la tabla o tablas a consultar
	 * @var $table string
	 */
	public $tableName;
	
	/**
	 * Establece si en la selecicon se muestran solo los datos distintos
	 * @var $distinct boolean
	 */
	public $distinct;
	
	/**
	 * Sentencia JOIN
	 * @var $join string
	 */
	public $join;
	
	/**
	 * Guarda las tablas referenciadas por los joins
	 * @var $joinTables array
	 */
	private $joinTables;
	
	/**
	 * Array de campos a seleccionar. Se aplican al SELECT
	 * @var $fields Array
	 */
	public $fields;
	
	/**
	 * Condiciones para la seleccion de datos. Se aplican al WHERE
	 * @var $conditions Array
	 */
	public $conditions;
	
	/**
	 * Indica el nivel actual de sub grupo en el que estamos. 
	 * Se utiliza para saber en que sub grupo debe incluir la proxima condicion
	 * @var $conditionLevel int
	 */
	public $conditionLevel;
	
	/**
	 * Columnas que van a agruparse en la seleccion de datos.
	 * @var $groupBy string
	 */
	public $groupBy;
	
	/**
	 * Si es true, al agrupar columnas se incluye informacion extra tipo corte de control
	 * 
	 * Si se agrupan la columna X y se suma la columna Y, agrega una fila mas con la sumatoria total.
	 * En la columna X aparecera el valor NULL y en la Y la sumatoria.
	 * Si agrupamos X, Y y sumamos Z, entonces agregara filas cno sumatorias parciales en cada cambio de X y de Y.
	 * 
	 * @var boolean
	 */
	public $withRollup;
		
	/**
	 * Se utiliza para agregar una condicion sobre funciones de agregacion
	 * @var $having string
	 */
	public $having;
	
	/**
	 * Columnas sobre las que se va a ordenar la seleccion 
	 * @var string
	 */
	public $orderBy;
	
	/**
	 * Limitacion de los resultados obtenidos en la selecciones. 
	 * @var $limit string
	 */
	public $limit;


	/**
	 * Nombre con el que se va a guardar el Query en el Cache
	 * @var $saveId string
	 */
	public $saveId;	

	
	
	
	/**
	 * Crea un objeto Query de forma estatica.
	 * Sirve para que al realizar las llamadas a metodos se haga de una forma mas natural.
	 * 
	 * Ejemplo: Query::create(....)
	 *          ->where(...);
	 * 
	 * @param string $type Tipo de consulta
	 * @param string_type $tableName Nombre de la tabla
	 * @param string $saveId Nombre de la clave del cache
	 * @param strnig $dbType Tipo de base de datos
	 * @return Query
	 */
	final public static function create($tableName, $type=Query::SELECT, $saveId=null, $dbType=DBManager::DB_MYSQL){
		return new Query($type, $tableName, $saveId, $dbType);
	}
	
	/**
	 * Helper para crear Querys del tipo SELECT directamente.
	 * Es una alias de la funcion create, preseleccionando el tipo SELECT
	 * 
	 * Ejemplo: Query::select(....)
	 *          ->where(...);
	 * 
	 * @param string_type $tableName Nombre de la tabla
	 * @param string $saveId Nombre de la clave del cache
	 * @return Query
	 */
	final public static function select($tableName, $saveId=null, $dbType=DatabaseType::MYSQL ){
		return Query::create($tableName, Query::SELECT, $saveId, $dbType);
	}
	
	/**
	 * Helper para crear Querys del tipo INSERT directamente.
	 * Es una alias de la funcion create, preseleccionando el tipo INSERT
	 * 
	 * Ejemplo: Query::insert(....)
	 *          ->xxx(...);
	 * 
	 * @param string_type $tableName Nombre de la tabla
	 * @param string $saveId Nombre de la clave del cache
	 * @return Query
	 */
	final public static function insert($tableName, $saveId=null, $dbType=DBManager::DB_MYSQL){
		return Query::create($tableName, Query::INSERT, $saveId, $dbType);
	}

	/**
	 * Helper para crear Querys del tipo UPDATE directamente.
	 * Es una alias de la funcion create, preseleccionando el tipo UPDATE
	 * 
	 * Ejemplo: Query::update(....)
	 *          ->xxx(...);
	 * 
	 * @param string_type $tableName Nombre de la tabla
	 * @param string $saveId Nombre de la clave del cache
	 * @return Query
	 */
	final public static function update($tableName, $saveId=null, $dbType=DBManager::DB_MYSQL){
		return Query::create($tableName, Query::UPDATE, $saveId, $dbType);
	}

	/**
	 * Helper para crear Querys del tipo DELETE directamente.
	 * Es una alias de la funcion create, preseleccionando el tipo DELETE
	 * 
	 * Ejemplo: Query::delete(....)
	 *          ->xxx(...);
	 * 
	 * @param string_type $tableName Nombre de la tabla
	 * @param string $saveId Nombre de la clave del cache
	 * @return Query
	 */
	public static function delete($tableName, $saveId=null, $dbType=DatabaseType::MYSQL){
		return Query::create($tableName, Query::DELETE, $saveId, $dbType);
	}	
	
	/**
	 * Crea el query e inicializa las variables
	 * 
	 * @param string $type Tipo de consulta
	 * @param string_type $tableName Nombre de la tabla
	 * @param string $saveId Nombre de la clave del cache
	 */
	public function __construct($type, $tableName, $saveId=null, $dbType=DatabaseType::MYSQL){
		$this->fields=Array();
		$this->conditions=Array();

		$this->distinct=false;
		$this->conditionLevel=0;
		$this->type=$type;
		$this->dbType=$dbType;
		$this->tableName=$tableName;
		$this->saveId=$saveId;
	}

	/**
	 * Al destruirse este objeto, si se seteo $saveId
	 * se guarda automaticamente en cache
	 */
	public function __destruct(){
		if(!is_null($this->saveId)){
			try{
				$cache=new Cache();
				$cache->set($this->saveId, $this);
			}catch(CacheError $e){
				throw new QueryError("Can't save $saveId query.");			
			}
		}
	}	

	/**
	 * Devuelve un objeto Query guardado previamente en cache
	 * @param string $saveId Nombre de la clave del query en cache a recuperar
	 * @return Query
	 */
	public static function open($saveId){
		
		try{
			$cache=new Cache();
			$query=$cache->get($saveId);
			if($query===false)throw new CacheError("$saveId not found");
			return $query;
		}catch(CacheError $e){
			throw new QueryError("Can't open $saveId query.");
		}
	}	

	
	/**
	 * Agrega una columna a seleccionar con select()
	 * o a agregar en un insert()
	 * Si la consulta es select(), $value sirve para especificar un alias al nombre de la columna
	 * Si la consulta es insert(), $value sirve para especificar el valor de la columna a agregar
	 * 
	 * @param sring $columnName Nombre de la columna
	 * @param string $value Alias de la columna en select o valor en insert/update
	 * @return Query
	 */
	public function column($columnName, $value=null){
		if(!is_null($value)){
			switch ($this->type){
				case Query::SELECT:
					$columnName.=" AS $value";
					break;
				default:
					$columnName.="='$value'";		
			}
		}else if($this->type==Query::INSERT||$this->type==Query::UPDATE){
			$columnName.="=:$columnName";		
		}
		if(!in_array($columnName, $this->fields))
			$this->fields[]=$columnName;
		return $this;
	}

	/**
	 * Agrega columnas de distintas formas para seleccionar con select()
	 * o para insertar en insert() 
	 * Puede recibir: 
	 * 	- uno o varios string con una sola columna<br/>
	 *  - uno o varios string con varias columnas separadas por coma<br/>
	 *  - uno o varios array de strings con las columnas<br/>
	 *  - uno o varios array asociativos de strings con las columnas y los alias/valores<br/>
	 *  	   alias si es select/valores si es update
	 *  - uno o varios objecto con las columnas como propiedades<br/>
	 *  
	 *  Ejemplo: 
	 *  Query->select("tabla")->columns("nombre, apellido);
	 *  Query->select("tabla")->columns(Array("nombre", "apellido"));
	 *  
	 *  $a=new stdClass();
	 *  $a->name="Pepe";
	 *  $a->dni="2";
	 *  Query->insert("tabla")->columns($a);
	 *  
	 *  
	 * @param string|array|object Columnas a agregar
	 * @return Query
	 */
	public function columns(){
		$args=func_get_args();
		foreach($args as $columns){
			if(is_string($columns)){
				$columns=str_replace(" ", "", $columns);
				$columns=explode(",", $columns);	
			}
			
			if(is_object($columns)){
				$columns=(array)$columns;
			}

			if(is_array($columns)){
				if(!is_assoc($columns)){
					foreach($columns as $column){
						$this->column($column);
					}
				}else{
					foreach($columns as $column=>$value){
						
						$this->column($column, $value);
					}
				}
			}
		}
		return $this;
	}
	
	/**
	 * Agrega una cuenta a una columna para una seleccion
	 * 
	 * @param $columnName Nombre de la columna a contar
	 * @param $as alias de la cuenta de la columna
	 * @return Query
	 */	
	public function count($columnName="*", $as=null){
		if(is_null($columnName))$columnName="*";
		if($this->type!=Query::SELECT)throw new QueryError("Only select() support this method");
		$countColumn="COUNT($columnName)";
		return $this->column($countColumn, $as);
	}
	
	
	public function all(){
		return $this->column("*");
	}	

        /**
         * @TODO: implementar BETWEEN
         */

	/**
	 * Agrega una condicion sobre una columna para el where
	 * 
	 * @param string $columnName Nombre de la columna
	 * @param mixed $value Valor a evaluar
	 * @param string $comparator Comparacion que se va a realizar
	 * @param string $conditional Condicional de agregacion al where
	 * @param boolean $not Invierte la condicion
	 * @return Query 
	 */	
	public function whereColumn($columnName, $value=null,  $comparator="=", $conditional="AND",$not=false){
		if($this->type==Query::INSERT)throw new QueryError("insert() doesn't support this method");
		if(is_null($comparator))$comparator="=";
		if(is_null($conditional))$conditional="AND";
		if(is_array($value))$comparator="IN";
		else if(strpos($value, "%")!==false)$comparator="LIKE";
		$condition=new Condition($columnName, $value, $conditional, $comparator, $not);
		$this->addCondition($condition);
		return $this;
	}
		
	/**
	 * Agrega una condicion sobre una columna para el where
	 * Helper de whereColumn() para que sea mas legible la sentencia
	 *  
	 * @param string $columnName Nombre de la columna
	 * @param mixed $value Valor a evaluar
	 * @param string $comparator Comparacion que se va a realizar
	 * @param string $conditional Condicional de agregacion al where
	 * @param boolean $not Invierte la condicion
	 * @return Query 
	 */
	public function where($columnName, $value=null,  $comparator="=", $conditional="AND", $not=false){
		return $this->whereColumn($columnName, $value,  $comparator, $conditional, $not);
	}

	/**
	 * Agrega una condicion negada sobre una columna para el where 
	 * Helper de where
	 * Facilita la lectura de la sentencia 
	 *  
	 * @param string $columnName Nombre de la columna
	 * @param mixed $value Valor a evaluar
	 * @param string $comparator Comparacion que se va a realizar
	 * @param string $conditional Condicional de agregacion al where
	 * @return Query
	 */
	public function whereNot($columnName, $value=null, $comparator="=", $conditional="AND"){
		return $this->where($columnName, $value,  $comparator, $conditional,  true);
	}
	
	/**
	 * Agrega una condicion OR sobre una columna para el where 
	 * Helper de where
	 * Facilita la lectura de la sentencia 
	 *  
	 * @param string $columnName Nombre de la columna
	 * @param mixed $value Valor a evaluar
	 * @param string $comparator Comparacion que se va a realizar
	 * @param string $conditional Condicional de agregacion al where
	 * @param boolean $not Invierte la condicion 
	 * @return Query
	 */
	public function orWhere($columnName, $value=null, $comparator="=", $not=false){
		return $this->where($columnName, $value,  $comparator, "OR", $not);
	}

	/**
	 * Agrega una condicion OR negada sobre una columna para el where 
	 * Helper de where y de orWhere
	 * Facilita la lectura de la sentencia 
	 *  
	 * @param string $columnName Nombre de la columna
	 * @param mixed $value Valor a evaluar
	 * @param string $comparator Comparacion que se va a realizar
	 * @param string $conditional Condicional de agregacion al where
	 * @return Query
	 */
	public function orWhereNot($columnName, $value=null, $comparator="="){
		return $this->where($columnName, $value, $comparator, "OR" , true);
	}
	
	/**
	 * Agrega una condicion AND sobre una columna para el where 
	 * Helper de where
	 * Facilita la lectura de la sentencia 
	 *  
	 * @param string $columnName Nombre de la columna
	 * @param mixed $value Valor a evaluar
	 * @param string $comparator Comparacion que se va a realizar
	 * @param string $conditional Condicional de agregacion al where
	 * @param boolean $not Invierte la condicion
	 * @return Query
	 */
	public function andWhere($columnName, $value=null, $comparator="=", $not=false){
		return $this->where($columnName, $value, $comparator, null, $not);
	}

	/**
	 * Agrega una condicion AND negada sobre una columna para el where 
	 * Helper de where
	 * Facilita la lectura de la sentencia 
	 *  
	 * @param string $columnName Nombre de la columna
	 * @param mixed $value Valor a evaluar
	 * @param string $comparator Comparacion que se va a realizar
	 * @param string $conditional Condicional de agregacion al where
	 * @return Query
	 */
	public function andWhereNot($columnName, $value=null, $comparator="="){
		return $this->where($columnName, $value, $comparator, null, true);
	}
		

	/**
	 * Devuelve solamente la union de los elementos que se relacionan en las 2 tablas
	 * 
	 * @param string $column Nombre de la columna sobre la cual se va a establecer la union
	 * @param string $nextTable Nombre de la tabla que se va a unir
	 * @param string $nextColumn Nombre de la columna de la tabla que se va a unir
	 * @return Query
	 */
	public function innerJoin($joinedTable){
		return $this->join($joinedTable, Query::JOIN_INNER);
	}

	/**
	 * Devuelve la union de todos los elementos de la tabla original se relacionen o no (sino pone nulls)
	 * 
	 * @param string $joinedTable Nombre de la tabla que se va a unir
	 * @return Query
	 */
	public function leftJoin($joinedTable){
		return $this->join($joinedTable, Query::JOIN_LEFT_OUTER);
	}

	/**
	 * Devuelve la union de todos los elementos de la tabla original se relacionen o no (sino pone nulls)
	 * 
	 * @param string $joinedTable Nombre de la tabla que se va a unir
	 * @return Query
	 */
	public function leftOuterJoin($joinedTable){
		return $this->leftJoin($joinedTable);
	}

	/**
	 * Devuelve solamente la union los elementos de la tabla original que no tiene relacion con la otra tabla
	 * 
	 * @param string $joinedTable Nombre de la tabla que se va a unir
	 * @return Query
	 */
	public function leftInnerJoin($joinedTable){
		return $this->join($joinedTable, Query::JOIN_LEFT_INNER);
	}
	
	
	/**
	 * Devuelve la union de todos los elementos de la tabla a unir se relacionen o no (sino pone nulls)
	 * 
	 * @param string $joinedTable Nombre de la tabla que se va a unir
	 * @return Query
	 */
	public function rightJoin($joinedTable){
		return $this->join($joinedTable, Query::JOIN_RIGHT_OUTER);
	}

	/**
	 * Devuelve la union de todos los elementos de la tabla a unir se relacionen o no (sino pone nulls)
	 * 
	 * @param string $joinedTable Nombre de la tabla que se va a unir
	 * @return Query
	 */
	public function rightOuterJoin($joinedTable){
		return $this->rightJoin($joinedTable);
	}

	/**
	 * Devuelve solamente la union de los elementos de la tabla a unir que no tiene relacion con la tabla original
	 * 
	 * @param string $joinedTable Nombre de la tabla que se va a unir
	 * @return Query
	 */
	public function rightInnerJoin($joinedTable){
		return $this->join($joinedTable, Query::JOIN_RIGHT_INNER);
	}
	
	/**
	 * Agrega una agrupacion al string de la sentencia SQL
	 * 
	 * @param string $columnName Nombre de la columna
	 * @param boolean $descending  Si es true se ordena de forma descendente.
	 */
	protected function addGroupBy($columnName, $descending){
		if($this->type!=Query::SELECT)throw new QueryError("Only select() support this method");
		if($this->groupBy=="")$this->groupBy.="GROUP BY ";
		else $this->groupBy.=",";
		
		$this->groupBy.= $columnName;
		if($descending)$this->groupBy.=" DESC";
	}	
	
	/**
	 * Agrega una agrupacion a una o varias columnas separadas por coma
	 * para la seleccion de datos
	 * @param string $columnName Nombre o nombres de las columnas a agrupar (separado por coma)
	 * @param boolean $descending Si es true se ordena de forma descendente.
	 * @return Query
	 */
	public function groupBy($columnName, $descending=false){
		if(!is_array($columnName))$columns=explode(",", str_replace(" ", "", $columnName));
		foreach($columns as $column){
			$this->addGroupBy($column, $descending);
		}
		return $this; 
	}	
	
	/**
	 * Agrega una ordenacion al string de la sentencia SQL
	 * 
	 * @param string $columnName Nombre o nombres de las columnas por las que se va a ordenar
	 * @param boolean $descending Si es true se ordena de forma descendente.
	 */	
	protected function addOrderBy($columnName, $descending){
		if($this->type!=Query::SELECT)throw new QueryError("Only select() support this method");
		if($this->orderBy=="")$this->orderBy.="ORDER BY ";
		else $this->orderBy.=",";
		
		$this->orderBy.= $columnName;
		if($descending)$this->orderBy.=" DESC";
	}
		
	/**
	 * Ordena la seleccion por una o varias columnas separadas por coma
	 * 
	 * @param string $columnName Nombre o nombres de las columnas por las que se va a ordenar
	 * @param boolean $descending Si es true se ordena de forma descendente.
	 * @return Query
	 */	
	public function orderBy($columnName, $descending=false){
		if(!is_array($columnName))$columns=explode(",", str_replace(" ", "", $columnName));
		foreach($columns as $column){
			$this->addOrderBy($column, $descending);
		}
		return $this; 
	}	
				
	/**
	 * Devuelve el ultimo grupo () abierto de condiciones anidadas
	 * sobre el cual debe agregarse el proximo where
	 * @return Conditions ultimo grupo de condiciones
	 */
	protected function getLastConditions(){
		$cond=&$this->conditions;
		for($x=0;$x<$this->conditionLevel;$x++){
			if(is_array($cond)||$cond instanceof Conditions){
				$cond=&$cond[count($cond)-1];
			}
		}
		return $cond;	
	}
		
	/**
	 * Agrega una condicion para ser evaluada por el where
	 * Si se agrego agrupacion de condiciones, esta funcion agrega la condicion
	 * al ultimo grupo abierto
	 * 
	 * @param Condition $condition Condicion a agregar
	 */
	protected function addCondition($condition){
		if($this->conditionLevel==0)
			$this->conditions[]=$condition;
		else{
			$lastConditions=$this->getLastConditions();
			if($lastConditions===true){
				array_pop($this->conditions);
				$this->addCondition($condition);
			}else $lastConditions[]=$condition;
		}
	}

	/**
	 * Anida un grupo para agregar condiciones dentro de el 
	 * @param string $conditional Condicional con el que se anidara el grupo
	 * @return Query
	 */
	public function addGroup($conditional="AND"){
		$this->addCondition(new Conditions($conditional));
		$this->conditionLevel++;
		return $this;
	}
	
	/**
	 * Anida un grupo OR para agregar condiciones dentro de el
	 * Helper de addGroup
	 * @return Query
	 */
	public function addOrGroup(){
		return $this->addGroup("OR");
	}
	
	/**
	 * Cierra el ultimo grupo () de condiciones abierto
	 * @return Query
	 */
	public function endGroup(){
		if($this->conditionLevel>0)$this->conditionLevel--;
		$this->conditions[]=true;
		return $this;
	}
	
	
	/**
	 * Al usar junto con un select(), selecciona solo las filas 
	 * distintas
	 * @return Query
	 */
	public function distinct(){
		$this->distinct=true;
		return $this;
	}
	
	/**
	 * Especifica la tabla que se va a unir a la tabla del query o la ultima tabla unida
	 * 
	 * Tipos de uniones:
	 * INNER: Devuelve solamente la union los elementos que se relacionan en las 2 tablas
	 * LEFT INNER: Devuelve solamente la union los elementos de la tabla original que no tiene relacion con la otra tabla
	 * LEFT OUTER: Devuelve la union de todos los elementos de la tabla original se relacionen o no (sino pone nulls)
	 * RIGHT INNER: Devuelve solamente  la union de los elementos de la tabla a unir que no tiene relacion con la tabla original
	 * RIGHT OUTER: Devuelve la union de todos los elementos de la tabla a unir se relacionen o no (sino pone nulls)
	 * 
	 * @param string $joinedTable Nombre de la tabla que se va a unir
	 * @param string $type Tipo de join
	 * @return Query
	 */	
	public function join($joinedTable, $type=Query::JOIN_INNER){
		$this->joinTables[$joinedTable]=$type;
		end($this->joinTables);
		switch($type){
			case Query::JOIN_INNER:
				$joinType="INNER";
				break;
			case Query::JOIN_LEFT_INNER:
			case Query::JOIN_LEFT_OUTER:
				$joinType="LEFT";
				break;
			case Query::JOIN_RIGHT_INNER:
			case Query::JOIN_RIGHT_OUTER:
				$joinType="RIGHT";
				break;
		}
		$this->join.=" $joinType JOIN ($joinedTable";
		return $this;
	}
	
	/**
	 * Establece las columnas sobre las que se va a realizar la union
	 * 
	 * @param $joinColumn Nombre de la columna sobre la cual se va a establecer la union (columna de la tabla del Query o de la penultima columna agredada) 
	 * @param $joinedColumn Nombre de la columna de la tabla que se va a unir (columna de la ultima tabla agredada al join)
	 * @return Query
	 */
	public function on($joinColumn, $joinedColumn=null){
		if(is_null($joinedColumn))$joinedColumn=$joinColumn;
		
		$joinedTableType=current($this->joinTables);
		$joinedTable=key($this->joinTables);
		
		prev($this->joinTables);
		if(!$joinTable=key($this->joinTables))$joinTable=$this->tableName;
		
		$this->join.=") ON $joinTable.$joinColumn = $joinedTable.$joinedColumn ";
		
		switch($joinedTableType){
			case Query::JOIN_LEFT_INNER:
				$this->where("$joinedTable.$joinedColumn","NULL", "IS");
				break;
			case Query::JOIN_RIGHT_INNER:
				$this->where("$joinTable.$joinColumn","NULL", "IS");
				break;
		}
		return $this;
	}	
	
	/**
	 * Agrega informacion extra tipo corte de control para las funciones de agregacion
	 * IMPORTANTE: Solo funciona en MYSQL
	 */
	public function withRollup(){
		if($this->type!=Query::SELECT)throw new QueryError("Only select() support this method");
		$this->withRollup=true;
		return $this;
	}

	/**
	 * Agrega condiciones en having
	 * Solo acepta un string con toda la sentecia del having
	 * 
	 * @todo implementar multiples havings
	 * @param string $having Condiciones a agregar
	 * @return Query
	 */
	public function having($having){
		if($this->type==Query::INSERT)throw new QueryError("insert() doesn't support this method");
		$this->having="HAVING $having";
		return $this;
	}

	/**
	 * Limita la cantidad de datos devueltos de una seleccion
	 * Sirve por ejemplo para paginar.
	 * 
	 * @param int $howManyRows Cuantas filas de datos se devolveran
	 * @param int $startingAt Posicion inicla desde la cual se devolveran los datos
	 * @return Query
	 */
	public function limit($howManyRows, $startingAt=null){
		if($this->type!=Query::SELECT)throw new QueryError("Only select() support this method");
		$this->limit="LIMIT ";
		if($startingAt>0)$this->limit.=" $startingAt,";
		$this->limit.=$howManyRows;
		return $this;
	}

	/**
	 * Devuelve el array de columnas convertido a string para la sentencia SQL
	 * @return array
	 */
	public function getColumns(){
		$columns="";
		if(count($this->fields)==0&&$this->type==Query::SELECT)$this->all();
		$first=true;
		foreach($this->fields as $column){
			if(!$first)$columns.=",";
			$columns.=$column;
			$first=false;
		}
		return $columns;
	}

	/**
	 * Devuelve el array de condiciones convertido a string para la sentencia SQL
	 * @param Conditions $conditionsArray
	 * @return array
	 */
	public function getConditions($conditionsArray=null){
		$conditions="";

		if(is_null($conditionsArray)){
			$conditionsArray=$this->conditions;
			if(count($conditionsArray)>0)
				$conditions.="WHERE";	
		}
		
		if(count($conditionsArray)>0){
			$first=true;
			foreach($conditionsArray as $condition){
				if($condition instanceof Conditions){
					if(!$first)$conditions.=" " . $condition->conditional;
					$conditions.= " ( " . $this->getConditions($condition) . " ) "; 
				}else if($condition instanceof Condition){
					if(!$first){
						$conditions.=" " . $condition->conditional;
					}else{
						$first=false;
					}
					$conditions.=" ";
					$conditions.= $condition->name;
					$conditions.=" " . $condition->comparator . " ";
					if(is_null($condition->value)){
						$conditions.=":" . $condition->name;
					}else{
						if(is_array($condition->value)){
							$conditions.="(";
							$subFirst=true;
							foreach($condition->value as $value){
								if(!$subFirst)$conditions.=",";
								if($value=="NULL")$conditions.=$value;
                                                                else if(substr(strtoupper($value), 0,6)=="SELECT") $conditions.="($value)"; 
								else $conditions.="'$value'";
								$subFirst=false;
							}
							$conditions.=")";
						}else{
							if($condition->value=="NULL")$conditions.=$condition->value;                                                        
                                                        else if(substr(strtoupper($condition->value), 0,6)=="SELECT") $conditions.="($condition->value)"; 
							else $conditions.="'" . $condition->value . "'";
						} 	
					}
				}				
			}
		}

		return $conditions;
	}
		
	/**
	 * Devuelve el Query en forma de String
	 * @return string querystring
	 */
	public function getQueryString(){
		$queryAdaptor=strtoupper($this->dbType) ."Adaptor";
		include_once(__DIR__ . "/adapters/" . $queryAdaptor . ".class.php");
		$type=\explode(" ", $this->type);
                $type=ucfirst(\strtolower($type[0]));
                $method="get" . $type . "QueryString";
                //return MYSQLAdaptor::$method($this);
                $queryAdaptor=__NAMESPACE__ . "\\$queryAdaptor";
		return $queryAdaptor::$method($this);
	}	
		
	/**
	 * Devuelve el Query en forma de string para que se pueda castear o imprimir
	 * (string)Query::select("tabla")
	 * echo Query::select("tabla");
	 * @return string querystring
	 */
	public function __toString(){
		return $this->getQueryString();
	}	
	
}