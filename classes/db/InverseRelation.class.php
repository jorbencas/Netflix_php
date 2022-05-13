<?php
namespace Moolty\DB;

/**
 * Representa una relacion inversa de una tabla
 * @author matias
 * @version 0.1
 * 
 */
class InverseRelation {

        public $foreignTable;
	public $foreignColumn;
        public $column;
	
        function __construct($column) {
            $this->column = $column;
        }

}

?>