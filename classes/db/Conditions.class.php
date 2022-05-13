<?php
namespace Moolty\DB;
include_once(__DIR__ . "/Condition.class.php");


/**
 * Conjunto de Condiciones
 * Se utiliza para agrupar condiciones para un query estableciendo un condicional de agregacion para todo el grupo
 * 
 * @example
 * 
 * $a=new Condition(1);
 * $b=new Condition(2);
 * 
 * $p=new Conditions();
 * $p[]=$a;
 * $p[]=$b;
 * 
 * @author Matias Zublena
 * @version 0.1
 */
class Conditions implements \Countable, \IteratorAggregate, \ArrayAccess{
	
	/**
	 * Condicional de agregacion de todo el grupo
	 * @var string
	 */
	public $conditional;
	
	/**
	 * Array de condicionales Condition
	 * @var Array
	 */
	protected $conditions = array();

	/**
	 * Incicializa el grupo de condiciones y abre la agrupacion
	 * @param string $conditional Condicional de agregacion del grupo
	 */
	public function __construct($conditional="AND"){
		$this->conditional = $conditional;
		$this->open=true;
	}

	/**
	 * Agrega una condicion al grupo actual o anida un grupo Conditions
	 * 
	 * @param Condition|Conditions $condition Condicion o grupo de condiciones a agregar/anidar
	 */
	public function add($condition){
		$this->conditions[] = $condition;
	}

	/**
	 * Agrega una condicion al array
	 * 
	 * @param int $index Indice
	 * @param Condition|Conditions $condition
	 */
	public function set($index, $condition)
	{
		if(!$condition instanceof Condition&&!$condition instanceof Conditions)
			throw new InvalidArgumentException('You can only add Condition/s objects');
		if(is_null($index)){
			$this->conditions[] = $condition;
		}else{
			$this->conditions[$index] = $condition;
		}
	}

	/**
	 * Comprueba que exista el indice en el array de condiciones
	 * @param int $index
	 */
	private function checkIndex($index){
		if($index >= $this->count())
			throw new OutOfRangeException('Index out of range');
	}
		
	/**
	 * Elimina la condicion en la posicion $index
	 * @param int $index
	 */
	public function remove($index)	{
		$this->checkIndex($index);
		array_splice($this->conditions, $index, 1);
	}
	
	/**
	 * Devuelve la condicion en la posicion $index
	 * @param int $index
	 * @return Condition
	 */
	public function get($index){
		$this->checkIndex($index);
		return $this->conditions[$index];
	}

	/**
	 * Comprueba que exista el indice  
	 * @param int $index
	 * @return boolean
	 */
	public function exists($index){
		if($index >= $this->count())
			return false;
		return true;
	}

	/**
	 * Devuelve la cantidad de condiciones
	 * Satisface Countable
	 * 
	 * @return int
	 */
	public function count()
	{
		return count($this->conditions);
	}


	/**
	 * Devuelve el Iterator
	 * Satisface IteratorAggregate
	 * 
	 * @return ArrayIterator
	 */
	public function getIterator(){
		return new \ArrayIterator($this->conditions);
	}

	/**
	 * Setea el offset
	 * Satisface ArrayAccess
	 * 
	 * @param int $offset
	 * @param Condition|Conditions $value
	 */
	public function offsetSet($offset, $value)
	{
		$this->set($offset, $value);
	}
	
	/**
	 * Elimina un offset
	 * Satisface ArrayAccess
	 * 
	 * @param int $offset
	 */
	public function offsetUnset($offset){
		$this->remove($offset);
	}

	/**
	 * Obtiene un offset
	 * Satisface ArrayAccess
	 * 
	 * @param int $offset
	 */
	public function offsetGet($offset)
	{
		return $this->get($offset);
	}

	/**
	 * Devuelve true si existe el offset
	 * Satisface ArrayAccess
	 * 
	 * @param int $offset
	 * @return boolean
	 */
	public function offsetExists($offset){
		return $this->exists($offset);
	}

	
}



