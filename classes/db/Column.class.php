<?php
namespace Moolty\DB;
include_once(__DIR__ . "/../validators/Validator.class.php");

/**
 * Representa una columna de una tabla de una base de datos; 
 * @author matias
 * @version 0.1
 * 
 */
class Column {

	public $name;
	public $type;
	public $null;
	public $key;
	public $default;
	public $extra;
	public $length;
	public $relationTable;
	public $relationField;
	public $validators;
	
	function __construct($name=null) {
		if(!is_null($name))$this->name=$name;
		$this->validators=Array();
	}
	
	public function getValidator(){
		if($this->null=="NO"&&($this->default=="NULL"||trim($this->default)=="")&&$this->extra!="auto_increment"){				
			$this->validators[]=\Validator::REQUIRED;
		}
		switch($this->type){
			case "int":
				$this->validators[]=\Validator::INTEGER;
				break;
			case "date":
			case "datetime";
			case "time":
			case "timestamp":
			case "blob":
			case "char":
			case "varchar":
				$this->validators[]=\Validator::STRING;
				break;
			case "decimal":
				$this->validators[]=\Validator::DECIMAL;
				break;
		}
	}
	

}

?>