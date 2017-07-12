<?php
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiModel extends EloquentModel
{
    protected $table = 'api';
    public static function insert_data($data=array()){
        return self::insert($data);
    }
}

