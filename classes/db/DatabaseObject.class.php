<?php

namespace Moolty\DB;

/**
 * DatabaseOject
 *
 * @author Matias Zublena <matias@ontinet.com>
 * @version 2.2
 * @package moolty/db
 * @todo agregar eventos onUpdate, etc
 *
 */
include_once(__DIR__ . "/DatabaseManager.class.php");
include_once(__DIR__ . "/Table.class.php");
include_once(__DIR__ . "/Query.class.php");
include_once(__DIR__ . "/../events/Event.class.php");
include_once(__DIR__ . "/../remote/ResponseObject.interface.php");
include_once(__DIR__ . "/../util/String.class.php");
include_once(__DIR__ . "/../validators/Validator.class.php");

/**
 * Esta clase representa un objeto con acceso a DB
 * Puede representar una entidad o utilizarse para recuperar entidades
 * @package tienda
 * @subpackage backend
 */
class DatabaseObject extends Query implements \Iterator {

    private $db;
    private $inQuery = false;
    public $className;
    private $actualObject;
    private $position = 0;
    public $table;
    public static $finders = array("findBy", "findFirstBy");
    private $plural = false;
    private $config;

    const EVENT_BEFORE_ADD = "beforeAdd";
    const EVENT_AFTER_ADD = "afterAdd";
    const EVENT_BEFORE_MODIFY = "beforeModify";
    const EVENT_AFTER_MODIFY = "afterModify";
    const EVENT_BEFORE_SAVE = "beforeSave";
    const EVENT_AFTER_SAVE = "afterSave";
    const EVENT_BEFORE_REMOVE = "beforeRemove";
    const EVENT_AFTER_REMOVE = "afterRemove";

    /*
     * Se disparan eventos adicionales after y before para cada clase hija del DatabaseObject
     * Ej: beforeAddUsuario
     *     afterAddUsuario
     */


    public function __construct($id=null, $className=null, $table=null, $tableName=null ,$config=null) {
        if (is_null($className))$className = get_class($this);
        $this->className = $className;
        if (!is_null($tableName))
            $this->tableName = $tableName;
        if (is_null($this->tableName)) {
            if ($this->plural) {
                $this->tableName = \String::getPlural($className);
            } else {
                $this->tableName = $className;
            }
        }
        if ($config)
            $this->config = $config;
//
        if (is_null($table)) {
            $this->connect();
            $this->table = $this->db->getTableStructure($this->tableName);
        } else {
            $this->table = clone $table;
        }
        parent::__construct(Query::SELECT, $this->tableName);

        if (!is_null($id)) {
            $this->where($this->table->primaryKey, $id);
            $this->table->data = $this->getData();
            if (!is_null($this->table->data) && !$this->table->data)
                throw new DatabaseError($this->className . " in table " . $this->table->name . " with " . $this->table->primaryKey . "=$id does not exist.");
        }
    }

    public function setTable($tableName) {
        $this->plural = false;
        $this->tableName = $tableName;
    }

    public function addProperty($propertyName, $value) {
        if (array_key_exists($propertyName, $this->table->columns)) {
            $this->table->data->$propertyName = $value;
        } else {
            throw new DatabaseError("Class " . $this->className . " assigned to table " . $this->tableName . " must have the property/column : $propertyName of type TIMESTAMP");
        }
    }

    private function connect() {
        if (is_null($this->db))
            if(!is_null($this->config)){
                $this->db = new DatabaseManager($this->config);
            }else{
                $this->db = new DatabaseManager();
            }
    }
    
    public function setConnect($config){
        $this->db = new DatabaseManager($config);
    }

    public function setData($data) {
        if (is_array($data))
            $data = (object) $data;
        $this->table->data = $data;
    }

    private function getData() {
        if (!$this->inQuery) {
            $this->connect();
            $this->type = Query::SELECT;
//        if ($this->tableName == 'ps_orders'/* || $this->tableName == 'ordenes_tienda'*/)
//        {
//            error_log('--------------------------------------------');
//            error_log('-------- :: '.$this->tableName.' :: --------');
//            error_log('00000 ::'.print_r($this->getQueryString(), true));
//            error_log('::::::::::::::::::::::::::::::::::::::::::::');
//            $this->db->executeQuery($this->getQueryString(), $this->);
//        }
            $this->db->executeQuery($this->getQueryString());
            $this->inQuery = true;
        }
        return $this->db->getResultAsObject();
    }

    public function beforeGet() {
        
    }

    public function afterGet() {
        
    }

    public function get() {
        $data = $this->getData();

        if ($data != false) {
            $newObject = clone($this);
            $newObject->setData($data);
            return $newObject;
        }
        else{
            return false;
        }
    }

    public function rewind() {
        $this->position = 0;
        $this->actualObject = $this->get();
    }

    public function key() {
        return $this->position;
    }

    public function valid() {
        return ($this->actualObject !== false && !is_null($this->actualObject));
    }

    public function current() {
        return $this->actualObject;
    }

    public function next() {
        $this->position++;
        $this->actualObject = $this->get();
    }

    public static function find() {
        $calledClass = get_called_class();
        return new $calledClass();
    }

    public static function findAll() {
        $calledClass = get_called_class();
        return new $calledClass(null, $calledClass);
    }

    public function getId() {
        $primaryKey = $this->table->primaryKey;
        if (property_exists($this->table->data, $primaryKey)) {
            return $this->table->data->$primaryKey;
        }
    }

    public function checkId() {
        if (is_null($this->getId()))
            throw new DatabaseError("Primary key " . $this->table->primaryKey . " not set in " . $this->className);
    }

    final public function add() {
        if ($this->table->type != Table::TYPE_TABLE
        )
            throw new DatabaseError("Method " . __FUNCTION__ . "() not allowd. Only allowed in regular tables (Table::TYPE_TABLE)");
        $this->connect();
        $continueDefault = \Event::raise(DatabaseObject::EVENT_BEFORE_ADD);
        $continueSpecific = \Event::raise(DatabaseObject::EVENT_BEFORE_ADD . $this->className, $this);
        if ($continueDefault && $continueSpecific) {
            $keys = array_keys((array) $this->table->data);
            $this->type = Query::INSERT;
            $this->fields = array();
            $this->db->executeQuery($this->columns($keys), $this->table->data);
            if (!is_null($this->table->primaryKey)) {
                $this->table->data->{$this->table->primaryKey} = $this->db->getLastId();
            }
            $this->inQuery = true;
            $this->actualObject = null;
        }

        \Event::raise(DatabaseObject::EVENT_AFTER_ADD);
        \Event::raise(DatabaseObject::EVENT_AFTER_ADD . $this->className, $this);
    }

    final public function modify() {
        if ($this->table->type != Table::TYPE_TABLE
        )
            throw new DatabaseError("Method " . __FUNCTION__ . "() not allowd. Only allowed in regular tables (Table::TYPE_TABLE)");
        $this->connect();
        $continueDefault = \Event::raise(DatabaseObject::EVENT_BEFORE_MODIFY);
        $continueSpecific = \Event::raise(DatabaseObject::EVENT_BEFORE_MODIFY . $this->className, $this);
        if ($continueDefault && $continueSpecific) {
            $this->checkId();
            $data = (array) $this->table->data;
            unset($data[$this->table->primaryKey]);
            $keys = array_keys($data);
            $data[$this->table->primaryKey] = $this->getId();
            $this->type = Query::UPDATE;
            $this->conditions = array();
            $this->fields = array();
            $this->where($this->table->primaryKey);
            $rowsUpdated = $this->db->simpleQuery($this->columns($keys), $data);
        } else {
            $rowsUpdated = 1;
        }
        \Event::raise(DatabaseObject::EVENT_AFTER_MODIFY);
        \Event::raise(DatabaseObject::EVENT_AFTER_MODIFY . $this->className);

        $this->inQuery = true;
        $this->actualObject = null;
    }

    final public function save() {
        if ($this->table->type != Table::TYPE_TABLE
        )
            throw new DatabaseError("Method " . __FUNCTION__ . "() not allowd. Only allowed in regular tables (Table::TYPE_TABLE)");
        $continueDefault = \Event::raise(DatabaseObject::EVENT_BEFORE_SAVE);
        $continueSpecific = \Event::raise(DatabaseObject::EVENT_BEFORE_SAVE . $this->className, $this);
        //        if ($continueDefault && $continueSpecific) {
        //            try {
        //                $this->add();
        //            } catch (DatabaseError $e) {
        //                try{
        //                    $this->modify();
        //                }catch(DatabaseError $e2){
        //                    $error="Add error: " . $e->getMessage() . "\nModify error:" . $e2->getMessage();
        //                    throw new DatabaseError("Could not add or modify: $error");
        //                }
        //            }
        //        }
        if ($continueDefault && $continueSpecific) {
            $new = false;
            try {
                $this->checkId();
            } catch (DatabaseError $e) {
                $new = true;
            }
            if ($new) {
                $this->add();
            } else {
                try {
                    $this->modify();
                } catch (DatabaseError $e) {
                    //Product not updated...same data
                }
            }
        }
        $this->inQuery = true;
        $this->actualObject = null;
        \Event::raise(DatabaseObject::EVENT_AFTER_SAVE);
        \Event::raise(DatabaseObject::EVENT_AFTER_SAVE . $this->className);
    }

    final public function remove() {
        if ($this->table->type != Table::TYPE_TABLE
        )
            throw new DatabaseError("Method " . __FUNCTION__ . "() not allowd. Only allowed in regular tables (Table::TYPE_TABLE)");
        $this->connect();
        $continueDefault = \Event::raise(DatabaseObject::EVENT_BEFORE_REMOVE);
        $continueSpecific = \Event::raise(DatabaseObject::EVENT_BEFORE_REMOVE . $this->className, $this);
        if ($continueDefault && $continueSpecific) {
            $this->checkId();
            $this->type = Query::DELETE;
            $rowsDeleted = $this->db->simpleQuery($this->where($this->table->primaryKey), array($this->table->primaryKey => $this->getId()));
        } else {
            $rowsDeleted = 1;
        }
        \Event::raise(DatabaseObject::EVENT_AFTER_REMOVE);
        \Event::raise(DatabaseObject::EVENT_AFTER_REMOVE . $this->className);

        if ($rowsDeleted == 0) {
            throw new DatabaseError($this->className . " not deleted");
        } else {
            $this->inQuery = true;
            //$this->get();
            //$this->actualObject = null;
        }
    }

    protected function getPlural() {
        return $this->plural;
    }

    protected function setPlural($plural) {
        $this->plural = $plural;
    }

    public function __call($method, $arguments) {
        $prefix = substr($method, 0, 2);
        if ($prefix == "is") {
            $variable = lcfirst(substr($method, 2));
            if (empty($prefix)) {
                throw new DatabaseError("Undefined method");
            }
            if ($this->table->columns[$variable]->type != "tinyint")
                throw new DatabaseError("$variable is not a boolean field");
            return (bool) $this->__get($variable);
        }




        $prefix = substr($method, 0, 3);
        $variable = lcfirst(substr($method, 3));
        if (empty($prefix)) {
            throw new DatabaseError("Undefined method");
        }


        switch ($prefix) {
            case "get":
                if (empty($variable))
                    throw new DatabaseError("Value is empty");
                return $this->__get($variable);
                break;
            case "set":
                if (empty($variable) && !($arguments[0] instanceof DatabaseObject)) {
                    throw new DatabaseError("Value is empty");
                }
                $this->__set($variable, $arguments[0]);
                return true;
                break;
        }

        if (substr($method, 0, 4) == "find") {
            $key = lcfirst(substr($method, 6));
            $value = $arguments[0];
            $comparator = null;
            if (isset($arguments[1]))
                $comparator = $arguments[1];
            $result = $this->finder($key, $value, $comparator);
            if (substr($method, 0, 11) == "findFirstBy") {
                return $result->get();
            } else {
                return $result;
            }
        } else {
            throw new DatabaseError("Method $method does not exist.");
        }
    }

    private function finder($variable, $value, $comparator = "=") {
        $found = false;
        $class = ucfirst($variable);
        if ($value instanceof DatabaseObject && $value instanceof $class) {
            if (array_key_exists($value->tableName, $this->table->relations)) {
                $found = true;
                $variable = $this->table->relations[$value->tableName];
                $value = $value->getId();
            }
        } else if (!$value instanceof stdClass) {
            $found = array_key_exists($variable, $this->table->columns);
        }
        if (!$found
        )
            throw new DatabaseError("Column $variable not found in . " . $this->className . " - table: " . $this->tableName);
        $this->where($variable, $value, $comparator);
        return $this;
    }

    public static function __callStatic($method, $arguments) {
        foreach (self::$finders as $finder) {
            $finderLength = strlen($finder);
            if (substr($method, 0, $finderLength) == $finder) {
                $variable = lcfirst(substr($method, $finderLength));
                $DatabaseObject = self::finderStatic($variable, $arguments[0]);
                switch (substr($method, 0, $finderLength)) {
                    case "findFirstBy":$DatabaseObject = $DatabaseObject->get();
                    default: return $DatabaseObject;
                }
                break;
            }
        }
    }

    public static function finderStatic($variable, $value) {
        $calledClass = get_called_class();
        $DatabaseObject = new $calledClass();
        $DatabaseObject->where($variable, $value);
        return $DatabaseObject;
    }

    public function __get($variable) {
        // @todo mejorar valores null (JAVI) -> SOLUCIONADO - DE MOMENTO EN FASE DE PRUEBAS DESDE 2017-09-14
        if (array_key_exists($variable, get_object_vars($this->table->data))) {
            return $this->table->data->$variable;
        } elseif ( isset($this->table->columns[$variable]) && $this->table->columns[$variable]->null == "YES" ) {
            return null;
        } elseif ( isset($this->table->columns[$variable]) && $this->table->columns[$variable]->null == "NO" ) {
            return $this->table->columns[$variable]->default;
        } else {
            //TODO: revisar case sensitives con varias palabras
            $className = ucfirst($variable);
            $instance = new $className();
            $relationTable = $instance->table->name;
            if (array_key_exists($relationTable, $this->table->relations)) {
                $foreignKeys = $this->table->relations[$relationTable];
                if (count($foreignKeys) == 1) {
                    $foreignKey = current($foreignKeys);
                    $foreignObjects = new $className($this->table->data->$foreignKey, $className, $instance->table, $instance->table->name);
                } else if (count($foreignKeys) > 1) {
                    foreach ($foreignKeys as $foreignKey) {
                        $foreignObjects[$foreignKey] = new $className($this->table->data->$foreignKey, $className, $instance->table, $instance->table->name);
                    }
                }
                $this->table->data->$variable = $foreignObjects;
                return $foreignObjects;
            } else if (array_key_exists($relationTable, $this->table->inverseRelations)) {
                $inverseRelations = $this->table->inverseRelations[$relationTable];
                if (count($inverseRelations) == 1) {
                    $inverseRelation = current($inverseRelations);
                    $inverseObjects = $className::finderStatic($inverseRelation->foreignColumn, $this->table->data->{$inverseRelation->column});
                } else if (count($inverseRelations) > 1) {
                    foreach ($inverseRelations as $inverseRelation) {
                        $inverseObjects[$inverseRelation->foreignColumn] = $className::finderStatic($inverseRelation->foreignColumn, $this->table->data->{$inverseRelation->column});
                    }
                }

                return $inverseObjects;
            }
            else
                throw new DatabaseError("Property $variable don't exists in " . $this->className);
        }
    }

    public function __set($variable, $value) {
        $variable_tmp = $variable;

        $keyDataArray = (array) $this->table->columns;

        if (array_key_exists($variable_tmp, $keyDataArray)) {

            if (is_object($value)) {
                if (!$value instanceof DatabaseObject)
                    throw new DatabaseError("Objet set is not a DatabaseObject");
                $this->table->$variable = $value->getId();
            }else {
                foreach ($this->table->columns[$variable]->validators as $validator) {
                    if (!is_array($validator)) {
                        $result = \Validator::staticValidate($validator, $variable, $value);
                    } else {
                        $type = $validator[0];
                        $opt = $validator[1];
                        $result = \Validator::staticValidate($type, $variable, $value, $opt);
                    }
                    if (!$result->result)
                        throw new \ValidationError($variable . " : " . $result->msg);
                }
                $this->table->data->$variable = $value;
            }
        }else if (is_object($value)) {
            if (!$value instanceof DatabaseObject)
                throw new DatabaseError("Objet set is not a DatabaseObject");
            $foreignKey = $this->table->relations[$value->table->name];
            $this->table->data->$foreignKey = $value->getId();
        }else {
            error_log("-----");
            error_log(print_r($keyDataArray,1));
            error_log("-----");
            throw new DatabaseError("Property $variable don't exists in " . $this->className);
        }
    }

    public function __clone() {
        $this->actualObject = null;
    }

}

?>
