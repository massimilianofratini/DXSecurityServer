<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/DXSecServer/secsrv_includes/config.php');
class ConnectionFactory
{
    private static $dbpath=Cfg::USERDB;
    private static $factory;
    private $db;

    public static function getFactory()
    {
        if (!self::$factory)
            self::$factory = new ConnectionFactory();
        return self::$factory;
    }

    public function getConnection() {
        if (!$this->db) {
            $this->db = new SQLite3(self::$dbpath,SQLITE3_OPEN_READWRITE);
            $this->db->exec('PRAGMA foreign_keys = OFF;');
        }
        return $this->db;
    }
}
?>

