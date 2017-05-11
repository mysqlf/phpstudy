<?php
require_once('Model\Lib\DB\MysqlPDO.class.php');
class oaMsgPDO extends MysqlPDO
{
    const DB_DNS = 'mysql:host=192.168.70.168;dbname=oa';
    const DB_USER = 'oa';
    const DB_PASS = '123456';
    const DB_CHARSET = 'utf8';
    // public function __construct() {
    //     try {
    //         $this->_pdo = new PDO('mysql:host=192.168.70.168;dbname=oa', 'oa', self::DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES '.self::DB_CHARSET));
    //         $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //     } catch (PDOException $e) {
    //         exit($e->getMessage());
    //     }
    // }
    // public function add($_tables, Array $_addData)
    // {
    //     if (is_string($_tables)) $_tables = array ($_tables);
    //     parent::add($_tables,$_addData);
    // }

}
?>