<?php
use Illuminate\Database\Eloquent\SoftDeletes;

class UserModel extends EloquentModel
{
    protected $table = 'user';
    public static function getone($id=''){
        return self::where('id','=',$id)->get();
    }
    public static function get_user_by_name($username=''){
        return self::where('username','=',$username)->find(1);
    }
    public static function get_user_by_namepwd($user=array()){
        return self::where($user)->get();
    }
}
