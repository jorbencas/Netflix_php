<?php
namespace Moolty\DB;
/**
 * Description of DBConnection
 *
 * @author rubenbataller
 */
class DatabaseConnection {
       
    private static $instance;

    public static function getConnection($dsn, $user, $password, $options, $force=false) {
        if (!isset(self::$instance) || $force)
            self::$instance = new \PDO($dsn, $user, $password, $options);
        
        return self::$instance;
    }
}
?>