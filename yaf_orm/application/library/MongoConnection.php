<?php
class MongoConnection{
    public function __construct($config){
        
        //$mongo->connect($config['mongodb']['server'],$config['mongodb']['port']);
        return $mongo;
    }
}
