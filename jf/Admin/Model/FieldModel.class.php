<?php
namespace Admin\Model;
use Think\Model;
class FieldModel extends Model {
	//自动验证
	protected $_validate = array(     
		array('title','require','标题必须填写'), //默认情况下用正则进行验证   
        array('name','require','必须填写'),
        array('type','require','类型必须选择'), 
        array('name','check_name','字段标识已经存在',0,'callback',1),
        array('mid','require','未选择操作的模型'),
	);
    /* 操作的表名 */
    protected $table_name = null;
    //检测字段名是否存在
    protected function check_name($data){
        $d=M('Field')->where(array('name'=>$data,'mid'=>I('post.mid')))->find();
        $r=$d ? false : true;
        return $r;
    }
    //检查表名是否存在
    protected function checkTableExist($model_id){
        $Model = M('Model');
        //当前操作的表
        $model = $Model->where(array('id'=>$model_id))->field('mark')->find();
        $table_name = $this->table_name = C('DB_PREFIX')."doc_".strtolower($model['mark']);
        $sql = <<<sql
                SHOW TABLES LIKE '{$table_name}';
sql;
        $res = M()->query($sql);
        return count($res);
    }
    /**
     * 新建表字段
     * @param array $field 需要新建的字段属性
     * @return boolean true 成功 ， false 失败
     * @author huajie <banhuajie@163.com>
     */
    public function addField($field){
        //检查表是否存在
        $table_exist = $this->checkTableExist($field['mid']);
        //获取默认值
        if($field['value'] === ''){
            $default = '';
        }elseif (is_numeric($field['value'])){
            $default = ' DEFAULT '.$field['value'];
        }elseif (is_string($field['value'])){
            $default = ' DEFAULT \''.$field['value'].'\'';
        }else {
            $default = '';
        }

        if($table_exist){
            $sql = <<<sql
                ALTER TABLE `{$this->table_name}`
ADD COLUMN `{$field['name']}`  {$field['sql']} {$default} COMMENT '{$field['title']}';
sql;
        }else{
                $sql = <<<sql
                CREATE TABLE IF NOT EXISTS `{$this->table_name}` (
                `id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键' ,
                `update_time`  int(10) UNSIGNED NOT NULL COMMENT '更新时间' ,
                `{$field['name']}`  {$field['sql']} {$default} COMMENT '{$field['title']}' ,
                PRIMARY KEY (`id`)
                )
                ENGINE=MyISAM
                DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
                CHECKSUM=0
                ROW_FORMAT=DYNAMIC
                DELAY_KEY_WRITE=0
                ;
sql;
        }
        $res = M()->execute($sql);
        return $res !== false;
    }
    /**
     * 删除一个字段
     * @param array $field 需要删除的字段属性
     * @return boolean true 成功 ， false 失败
     * @author huajie <banhuajie@163.com>
     */
    public function deleteField($field){
        //检查表是否存在
        $table_exist = $this->checkTableExist($field['mid']);
        $sql = <<<sql
            ALTER TABLE `{$this->table_name}`
DROP COLUMN `{$field['name']}`;
sql;
        $res = M()->execute($sql);
        return $res !== false;
    }
}