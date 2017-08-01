<?php
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiModel extends EloquentModel
{
    protected $table = 'api';
    public $timestamps = false;#false不维护update_at字段 
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
     * [get_api_info 根据接口id获取接口详情]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-07-26
     * @param    [type]     $apiid [description]
     * @return   [type]            [description]
     */
    public static function get_api_info($apiid){
        return self::where('id','=',$apiid)->get()->toArray();
    }
    /**
     * [get_api_list_of_cate 获取分类下接口]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-07-26
     * @param    [type]     $cate [description]
     * @return   [type]           [description]
     */
    public static function get_api_list_of_cate($cate){
        return self::where('aid','=',$cate)->get()->toArray();
    }

    /**
     * [update_api 接口修改]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-07-28
     * @param    [type]     $id   [description]
     * @param    [type]     $data [description]
     * @return   [type]           [description]
     */
    public static function update_api($id,$data){
        return self::where('id','=',$id)->update($data);
    }
    /**
     * [get_api 获取接口]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-07-13
     * @param    array     $where [查询条件]
     * @return   [type]            [description]
     */
    public static function get_api($where){
        return self::where($where)->get()->toArray();
    }
}

