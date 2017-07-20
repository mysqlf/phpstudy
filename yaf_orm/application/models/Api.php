<?php
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiModel extends EloquentModel
{
    protected $table = 'api';
    /**
     * [insert_data 数据写入]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-07-13
     * @param    array      $data [description]
     * @return   [type]           [description]
     */
    public static function insert_api($data=array()){
        return self::insert($data); 
    }
    /**
     * [get_api 获取接口]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-07-13
     * @param    array     $where [查询条件]
     * @return   [type]            [description]
     */
    public static function get_api($where){
        return self::where($where)->find(1)->toArray();
    }
}

