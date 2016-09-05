<?php
namespace Admin\Model;
use Think\Model;
class ModelModel extends Model {
	//自动验证
	protected $_validate = array(     
		array('title','require','名称必须填写'), //默认情况下用正则进行验证   
        array('title','','名称已经存在',0,'unique',3),
        array('mark','require','标识必须填写'), 
        array('mark','','标识已经存在',0,'unique',3),
	);
    //检测新建的表是否存在
    public function checkTableExist($mark){
        $table_name = $this->table_name = C('DB_PREFIX').strtolower($mark);
        $sql = <<<sql
                SHOW TABLES LIKE '{$table_name}';
sql;
        $res = M()->query($sql);
        return count($res);
    }
    /**
     * 删除一个模型
     * @param integer $id 模型id
     * @author huajie <banhuajie@163.com>
     */
    public function delTable($id){
        //获取表名
        $model = $this->field('mark')->find($id);
        $table_name = C('DB_PREFIX')."doc_".strtolower($model['mark']);
        //删除属性数据
        M('Field')->where(array('mid'=>$id))->delete();
        //删除该表
        $sql = <<<sql
                DROP TABLE {$table_name};
sql;
        $res = M()->execute($sql);
        return $res !== false;
    }
}