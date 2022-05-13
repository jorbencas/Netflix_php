<?php
namespace Moolty\DB;
class Condition{
	public $conditional;
	public $name;
	public $comparator;
	public $value;
	
	
	public function __construct($name, $value=null, $conditional="AND", $comparator="=", $not=false){
                if ($not){
					if ($comparator =='LIKE') $comparator= "NOT $comparator";
					else if ($comparator =='IN') $comparator =  "NOT $comparator";
                    else if ($comparator =='IS') $comparator = "$comparator NOT ";
                        else $comparator="!$comparator";
                }
		$this->name=$name;
		$this->value=$value;
		$this->conditional=$conditional;
		$this->comparator=$comparator;
	}

}