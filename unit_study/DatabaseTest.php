<?php
use PHPUnit\Framework\TestCase;
class DatabaseTest extends TestCase{
    protected static $dbh;
    public static function serUpBeforeClass(){
        self::$dbh=new PDO('sqlite::memory:');
    }
    public static function tearDownAfterClass(){
        self::$dbh=null;
    }
}