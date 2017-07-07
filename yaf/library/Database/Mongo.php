<?php
namespace Database;
class Mongo{
    public function __construct(){
        $this->manager = new \MongoDB\Driver\Manager("mongodb://localhost:27017");
    }
    /**
     * [add 插入]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-04-11
     * @param    [arr]     $list  [数据]
     * @param    [str]     $table [表]
     * @return   [type]            [description]
     */
    public function add($table,$list){
        $bulk = new \MongoDB\Driver\BulkWrite;
        $writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $bulk->insert($list);
        $this->manager->executeBulkWrite($table, $bulk,$writeConcern);
        return '';
    }
    /**
     * [search 查询数据]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-04-11
     * @param    [str]     $table   [表]
     * @param    [arr]     $filter  [条件]
     * @param    [arr]     $options [选项]
     * @return   [arr]              [查询结果]
     */
    public function search($table,$filter,$options){
        $query = new \MongoDB\Driver\Query($filter, $options);
        $cursor = $this->manager->executeQuery($table, $query);
        return $cursor;
    }
    /**
     * [update 修改]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-04-11
     * @param    [str]     $table [表]
     * @param    [arr]     $where [条件]
     * @param    [arr]     $set   [修改值]
     * @param    [arr]     $multi [限制选项]
     * @return   [type]            [description]
     */
    public function update($table,$where,$set,$multi=['multi' => false, 'upsert' => false]){
        if (empty($where)) {
            return 'where is empty';
        }
        $bulk = new \MongoDB\Driver\BulkWrite;
        $bulk->update($where, ['$set' => $set], $multi);
        $writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $result = $this->manager->executeBulkWrite($table, $bulk,$writeConcern);
        return '';
    }
    /**
     * [delete 删除数据]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-04-11
     * @param    [str]     $table [表]
     * @param    [arr]     $where [条件]
     * @param    [arr]     $limit [限制]
     * @return   [type]            [description]
     */
    public function delete($table,$where,$limit=['limit' => 1]){
        if (empty($where)) {
            return 'where is empty';
        }
        $bulk = new \MongoDB\Driver\BulkWrite;
        $bulk->delete($where,$limit);   // limit 为 1 时，删除第一条匹配数据
        $writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $result = $this->manager->executeBulkWrite($table, $bulk, $writeConcern);
    }
}
