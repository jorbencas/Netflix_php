<?php
namespace Moolty\DB;
include_once(dirname(__FILE__) . "/../exceptions/Error.class.php");
class DatabaseError extends \Moolty\Error{
	function __construct($message){
		parent::__construct($message);
	}
}
?>