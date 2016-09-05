<?php
namespace Admin\Model;
use Think\Model;
class SystemModel extends Model {
	//自动验证
	protected $_validate = array(     
		array('title','require','网站标题不能为空'), //默认情况下用正则进行验证
	);
    //自动完成
    protected $_auto = array (
        array('smtp_password','encrypt',3,'function') , // 对password字段在新增的时候使md5函数处理
    );
}