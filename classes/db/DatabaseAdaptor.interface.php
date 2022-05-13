<?php
namespace Moolty\DB;
include_once(__DIR__ . "/QueryAdaptor.interface.php");
interface DatabaseAdaptorInterface extends QueryAdaptorInterface{
	static function getTableStructure($tableName, $dbManager);
        static function getTables($dbManager);
}
?>
