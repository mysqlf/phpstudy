<?php
use Illuminate\Database\Eloquent\SoftDeletes;

class CateModel extends EloquentModel
{
    protected  $table= 'cate';
    public $timestamps = false;#false不维护update_at字段 
    /**
     * [get_cate_list 分类列表]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-07-20
     * @return   [type]     [description]
     */
    public static function get_cate_list(){
        return self::orderBy('aid', 'desc')->get()->toArray();
    }
    /**
     * [insert_data 插入数据]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-07-20
     * @param    array      $data [description]
     * @return   [type]           [description]
     */
    public static function insert_cate($data=array()){
        return self::insert($data); 
    }
}
