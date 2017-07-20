<?php
use Illuminate\Database\Eloquent\SoftDeletes;

class UserModel extends EloquentModel
{
    protected $table = 'user';
    public static function getone($id=''){
        return self::where('id','=',$id)->get();
    }
    /**
     * [get_user_by_name 获取用户信息]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-07-20
     * @param    string     $username [description]
     * @return   obj               [description]
     */
    public static function get_user_by_name($username=''){
        $res= self::where('username','=',$username)->get()->toArray();
        return $res[0];
    }
    public static function get_user_by_namepwd($user=array()){
        return self::where($user)->get();
    }
}
