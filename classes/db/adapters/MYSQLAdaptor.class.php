<?php
namespace Moolty\DB;
include_once(__DIR__ . "/../DatabaseAdaptor.interface.php");
include_once(__DIR__ . "/../InverseRelation.class.php");

/**
 * Query se utiliza para generar sentencias SQL.
 *
 * Nos facilita la generacion de sentencias SQL brindandonos un marco de trabajo
 * orientado a objetos para establecer las sentencias.
 * Esta clase tambien permite guardar y recuperar sentencias del cache, con lo que se
 * pueden generar queries reutilizables de rapido acceso.
 *
 * @version $Revision: 0.1 $
 * @author Matias
 * @package libs/db
 **/
class MYSQLAdaptor implements DatabaseAdaptorInterface{



	public static function getTableStructure($tableName, $dbManager){
		$dbManager->executeQuery("SHOW COLUMNS FROM $tableName");
		$table=new Table($tableName);
		while($rowColumn=$dbManager->getResult()){
			$column=new Column($rowColumn["Field"]);
			$column->type=$rowColumn["Type"];
			$column->null=$rowColumn["Null"];
			$column->key=$rowColumn["Key"];
			$column->default=$rowColumn["Default"];
			$column->extra=$rowColumn["Extra"];
			if(strpos($column->type, "(")){
				$column->type=explode("(", $column->type);
				$length=$column->type[1];
				$length=explode(")", $length);
				$column->length=$length[0];
				$column->type=$column->type[0];
			}else{
				$column->length=0;
			}
			$column->getValidator();
			if($column->length>0){
				$maxLength=Array();
				$maxLength[]=\Validator::MAXLENGTH;
				$maxLength[]=$column->length;
				$column->validators[]=$maxLength;
			}
			$table->addColumn($column);
		}

	 	$sql=Query::select("information_schema.key_column_usage")
	             ->where("CONSTRAINT_SCHEMA", $dbManager->schema)
                     ->addGroup()   
                        ->where("TABLE_NAME",$tableName)
                        ->orWhere("REFERENCED_TABLE_NAME", $tableName);
                                

		$dbManager->executeQuery($sql);
		while($rowIndexColumn=$dbManager->getResult()){
			if($rowIndexColumn["CONSTRAINT_NAME"]=="PRIMARY"){
				if(!isset($table->primaryKey))$table->primaryKey=$rowIndexColumn["COLUMN_NAME"];
			}else{
                                if($rowIndexColumn["TABLE_NAME"]==$tableName){
                                    $table->relations[$rowIndexColumn["REFERENCED_TABLE_NAME"]]=$rowIndexColumn["COLUMN_NAME"];
                                    $table->columns[$rowIndexColumn["COLUMN_NAME"]]->relationTable=$rowIndexColumn["REFERENCED_TABLE_NAME"];
                                    $table->columns[$rowIndexColumn["COLUMN_NAME"]]->relationField=$rowIndexColumn["REFERENCED_COLUMN_NAME"];
                                }else{
                                    $inverseRelation=new InverseRelation($rowIndexColumn["REFERENCED_COLUMN_NAME"]);
                                    $inverseRelation->foreignTable=$rowIndexColumn["TABLE_NAME"];
                                    $inverseRelation->foreignColumn=$rowIndexColumn["COLUMN_NAME"];
                                    $table->inverseRelations[$rowIndexColumn["TABLE_NAME"]]=$inverseRelation;
                                }
			}
		}

                $sql=Query::select("information_schema.tables")
                      ->where("TABLE_NAME",$tableName);
                $dbManager->executeQuery($sql);
                $result=$dbManager->getResult();
                if($result["TABLE_TYPE"]=="VIEW")$table->type=Table::TYPE_VIEW;
                else {
                    $table->type=Table::TYPE_TABLE;
                }
		return $table;
	}

        public static function getTables($dbManager){
            $tables=$dbManager->getAllAsArray("SHOW TABLES");
            return $tables;

        }

	/**
	 * Arma el string del query con la estructura del SELECT
	 */
	public static function getSelectQueryString(Query $query){
		$queryString=Array();
		$queryString[]=$query->type;
		if($query->distinct)$queryString[]="DISTINCT";
		$queryString[]=$query->getColumns();
		$queryString[]="FROM";
		$queryString[]=$query->tableName;
		if(!is_null($query->join))$queryString[]=$query->join;
		if(count($query->conditions)>0)$queryString[]=$query->getConditions();
		if(!is_null($query->groupBy))$queryString[]=$query->groupBy;
		if(!is_null($query->orderBy))$queryString[]=$query->orderBy;
		if(!is_null($query->limit))$queryString[]=$query->limit;
		return implode(" ", $queryString);
	}



	/**
	* Arma el string del query con la estructura del INSERT
	*/
	public static function getInsertQueryString(Query $query){
		$queryString=Array();
		$queryString[]=$query->type;
		$queryString[]=$query->tableName;
		$queryString[]="SET";
		$queryString[]=$query->getColumns();
		return implode(" ", $queryString);
	}

	/**
	* Arma el string del query con la estructura del UPDATE
	*/
	public static function getUpdateQueryString(Query $query){
		$queryString=Array();
		$queryString[]=$query->type;
		$queryString[]=$query->tableName;
		$queryString[]="SET";
		$queryString[]=$query->getColumns();
		if(count($query->conditions)>0)$queryString[]=$query->getConditions();
		return  implode(" ", $queryString);
	}

	/**
	* Arma el string del query con la estructura del DELETE
	*/
	public static function getDeleteQueryString(Query $query){
		$queryString=Array();
		$queryString[]=$query->type;
		$queryString[]=$query->tableName;
		if(count($query->conditions)>0)$queryString[]=$query->getConditions();
		return implode(" ", $queryString);

	}


}


?>